<?php

namespace App\Services\Xero;

use App\Models\Xero\XeroDepartment;
use App\Models\Xero\XeroInvoice;
use App\Models\Xero\XeroClient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    private const CACHE_TTL = 600;

    private function monthExpr(string $dateCol): string
    {
        return "CONCAT(YEAR({$dateCol}),'-',RIGHT(CONCAT('0',MONTH({$dateCol})),2))";
    }

    // ── CENTRALIZED FILTER BUILDERS ─────────────────────────────────

    private function baseFilteredQuery(Carbon $from, Carbon $to, array $filters = [])
    {
        $q = XeroInvoice::sales()->authorised()
            ->whereBetween('invoice_date', [$from->toDateString(), $to->toDateString()]);

        if (!empty($filters['client_id'])) {
            $q->where('xero_client_id', $filters['client_id']);
        }
        if (!empty($filters['min_amount'])) {
            $q->where('total', '>=', (float) $filters['min_amount']);
        }
        if (!empty($filters['max_amount'])) {
            $q->where('total', '<=', (float) $filters['max_amount']);
        }
        if (!empty($filters['department_id']) || !empty($filters['service'])) {
            $q->whereHas('items', function ($iq) use ($filters) {
                if (!empty($filters['department_id'])) $iq->where('department_id', $filters['department_id']);
                if (!empty($filters['service'])) $iq->where('normalized_service', $filters['service']);
            });
        }
        return $q;
    }

    private function baseFilteredItemQuery(Carbon $from, Carbon $to, array $filters = [])
    {
        $q = DB::table('xero_invoice_items as ii')
            ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
            ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
            ->where('inv.type', 'ACCREC')
            ->whereBetween('inv.invoice_date', [$from->toDateString(), $to->toDateString()])
            ->whereNull('inv.deleted_at');

        if (!empty($filters['client_id'])) $q->where('inv.xero_client_id', $filters['client_id']);
        if (!empty($filters['min_amount'])) $q->where('inv.total', '>=', (float) $filters['min_amount']);
        if (!empty($filters['max_amount'])) $q->where('inv.total', '<=', (float) $filters['max_amount']);
        if (!empty($filters['department_id'])) $q->where('ii.department_id', $filters['department_id']);
        if (!empty($filters['service'])) $q->where('ii.normalized_service', $filters['service']);

        return $q;
    }

    // ── 1. FINANCIAL SUMMARY ─────────────────────────────────────────

    public function financialSummary(Carbon $from, Carbon $to, array $filters = []): array
    {
        $hash = md5(json_encode($filters));
        $key = "xero_summary_{$from->toDateString()}_{$to->toDateString()}_{$hash}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $filters) {
            $stats = $this->baseFilteredQuery($from, $to, $filters)->selectRaw('
                COUNT(*) as invoice_count,
                SUM(total) as total_revenue,
                SUM(CASE WHEN status = "PAID" THEN total ELSE 0 END) as paid_revenue,
                SUM(CASE WHEN status = "AUTHORISED" THEN amount_due ELSE 0 END) as outstanding_revenue,
                AVG(total) as avg_invoice_value
            ')->first();

            return [
                'total_revenue'       => (float) ($stats->total_revenue ?? 0),
                'paid_revenue'        => (float) ($stats->paid_revenue ?? 0),
                'outstanding_revenue' => (float) ($stats->outstanding_revenue ?? 0),
                'invoice_count'       => (int)   ($stats->invoice_count ?? 0),
                'avg_invoice_value'   => (float) ($stats->avg_invoice_value ?? 0),
                'currency'            => 'AED',
            ];
        });
    }

    public function monthComparison(array $filters = []): array
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_month_comparison_{$hash}", self::CACHE_TTL, function () use ($filters) {
            $current  = $this->monthRevenue(now()->year, now()->month, $filters);
            $previous = $this->monthRevenue(now()->subMonth()->year, now()->subMonth()->month, $filters);
            $growth   = $previous > 0 ? round((($current - $previous) / $previous) * 100, 1) : ($current > 0 ? 100 : 0);

            return [
                'current_month'  => $current,
                'previous_month' => $previous,
                'growth_pct'     => $growth,
                'growth_abs'     => $current - $previous,
                'currency'       => 'AED',
            ];
        });
    }

    private function monthRevenue(int $year, int $month, array $filters = []): float
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();
        return (float) $this->baseFilteredQuery($start, $end, $filters)->sum('total');
    }

    // ── 2. TRENDS ────────────────────────────────────────────────────

    public function monthlyTrend(int $months = 12, array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_monthly_trend_{$months}_{$hash}", self::CACHE_TTL, function () use ($months, $filters) {
            $start = Carbon::now()->subMonths($months - 1)->startOfMonth();
            $to    = Carbon::now()->endOfMonth();
            $expr  = $this->monthExpr('invoice_date');

            $rows = $this->baseFilteredQuery($start, $to, $filters)
                ->selectRaw("{$expr} AS ym, SUM(total) AS revenue, SUM(CASE WHEN status='PAID' THEN total ELSE 0 END) AS paid, COUNT(*) AS invoice_count")
                ->groupByRaw("YEAR(invoice_date), MONTH(invoice_date), {$expr}")
                ->get()->keyBy('ym');

            return collect(range(0, $months - 1))->map(function ($i) use ($start, $rows) {
                $ym  = $start->copy()->addMonths($i)->format('Y-m');
                $row = $rows->get($ym);
                return [
                    'year_month'    => $ym,
                    'label'         => Carbon::parse($ym . '-01')->format('M Y'),
                    'revenue'       => (float) ($row->revenue ?? 0),
                    'paid'          => (float) ($row->paid ?? 0),
                    'invoice_count' => (int)   ($row->invoice_count ?? 0),
                ];
            });
        });
    }

    public function highestRevenueMonth(array $filters = []): array
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_peak_month_{$hash}", self::CACHE_TTL * 6, function () use ($filters) {
            $expr = $this->monthExpr('invoice_date');
            
            $row = $this->baseFilteredQuery(Carbon::parse('2000-01-01'), Carbon::now()->addYear(), $filters)
                ->selectRaw("{$expr} AS ym, SUM(total) AS rev")
                ->groupByRaw("YEAR(invoice_date), MONTH(invoice_date), {$expr}")
                ->orderByDesc('rev')
                ->first();

            return [
                'year_month' => $row?->ym,
                'label'      => $row ? Carbon::parse($row->ym . '-01')->format('F Y') : null,
                'revenue'    => (float) ($row?->rev ?? 0),
            ];
        });
    }

    // ── 3. DEPARTMENT ANALYTICS ──────────────────────────────────────

    public function departmentRevenue(Carbon $from, Carbon $to, array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        $key = "xero_dept_rev_{$from->toDateString()}_{$to->toDateString()}_{$hash}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $filters) {
            $rows = $this->baseFilteredItemQuery($from, $to, $filters)
                ->join('xero_departments as d', 'd.id', '=', 'ii.department_id')
                ->selectRaw('d.id AS department_id, d.name AS department_name, d.color,
                             SUM(ii.line_amount) AS revenue,
                             SUM(CASE WHEN inv.status = "AUTHORISED" THEN ii.line_amount ELSE 0 END) AS outstanding_revenue,
                             COUNT(DISTINCT inv.id) AS invoice_count')
                ->groupBy('d.id', 'd.name', 'd.color')
                ->orderByDesc('revenue')
                ->get();

            $total = $rows->sum('revenue');
            return $rows->map(fn($row) => [
                'department_id'       => $row->department_id,
                'department_name'     => $row->department_name,
                'color'               => $row->color,
                'revenue'             => (float) $row->revenue,
                'outstanding_revenue' => (float) $row->outstanding_revenue,
                'invoice_count'       => (int)   $row->invoice_count,
                'contribution_pct'    => $total > 0 ? round(($row->revenue / $total) * 100, 1) : 0,
            ]);
        });
    }

    public function departmentMonthlyTrend(int $months = 6, array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_dept_trend_{$months}_{$hash}", self::CACHE_TTL, function () use ($months, $filters) {
            $start = Carbon::now()->subMonths($months - 1)->startOfMonth();
            $expr  = $this->monthExpr('inv.invoice_date');

            $rows = $this->baseFilteredItemQuery($start, Carbon::now()->endOfMonth(), $filters)
                ->join('xero_departments as d', 'd.id', '=', 'ii.department_id')
                ->selectRaw("d.name AS department_name, d.color, {$expr} AS ym, SUM(ii.line_amount) AS revenue")
                ->groupByRaw("d.name, d.color, YEAR(inv.invoice_date), MONTH(inv.invoice_date), {$expr}")
                ->orderByRaw("YEAR(inv.invoice_date) ASC, MONTH(inv.invoice_date) ASC")
                ->get();

            return collect($rows)->map(fn($r)=>(array)$r)->groupBy('department_name');
        });
    }

    // ── 4. SERVICE ANALYTICS ─────────────────────────────────────────

    public function serviceRevenue(Carbon $from, Carbon $to, array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        $key = "xero_service_rev_{$from->toDateString()}_{$to->toDateString()}_{$hash}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $filters) {
            return $this->baseFilteredItemQuery($from, $to, $filters)
                ->selectRaw('ii.normalized_service, SUM(ii.line_amount) AS revenue, COUNT(DISTINCT inv.id) AS invoice_count')
                ->groupBy('ii.normalized_service')
                ->orderByDesc('revenue')
                ->get()
                ->map(fn($r) => (array) $r); // Force return array to prevent stdClass blade errors
        });
    }

    // ── 5. CLIENT ANALYTICS ──────────────────────────────────────────

    public function allClientPerformance(Carbon $from, Carbon $to, array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        $key = "xero_all_clients_{$from->toDateString()}_{$to->toDateString()}_{$hash}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $filters) {
            $invoices = $this->baseFilteredQuery($from, $to, $filters)
                ->selectRaw('
                    xero_client_id, 
                    SUM(total) AS revenue, 
                    SUM(CASE WHEN status = "AUTHORISED" THEN amount_due ELSE 0 END) AS outstanding_revenue,
                    COUNT(*) AS invoice_count
                ')
                ->groupBy('xero_client_id');

            $query = DB::table('xero_clients as c')
                ->leftJoinSub($invoices, 'inv', 'c.id', '=', 'inv.xero_client_id')
                ->where('c.status', 'ACTIVE')
                ->selectRaw('
                    c.id as client_id,
                    c.name as client_name,
                    c.email,
                    COALESCE(inv.revenue, 0) as revenue,
                    COALESCE(inv.outstanding_revenue, 0) as outstanding_revenue,
                    COALESCE(inv.invoice_count, 0) as invoice_count
                ');

            // Hide empty clients if filters are active
            if (!empty($filters['client_id'])) {
                $query->where('c.id', $filters['client_id']);
            } elseif (!empty($filters)) {
                $query->whereNotNull('inv.xero_client_id'); 
            }

            return $query
                ->orderByDesc('revenue')
                ->orderBy('client_name')
                ->get()
                ->map(fn($r) => (array) $r);
        });
    }

    public function clientAnalytics(int $clientId): array
    {
        return Cache::remember("xero_client_{$clientId}_analytics", self::CACHE_TTL, function () use ($clientId) {
            $client      = XeroClient::findOrFail($clientId);
            $expr        = $this->monthExpr('invoice_date');
            $fromDate    = Carbon::now()->subYear()->startOfMonth()->toDateString();

            $totalPaid   = XeroInvoice::sales()->where('xero_client_id', $clientId)->where('status', 'PAID')->sum('total');
            $outstanding = XeroInvoice::sales()->where('xero_client_id', $clientId)->where('status', 'AUTHORISED')->sum('amount_due');

            $serviceBreakdown = DB::table('xero_invoice_items as ii')
                ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
                ->where('inv.xero_client_id', $clientId)
                ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
                ->selectRaw('ii.normalized_service, SUM(ii.line_amount) AS revenue')
                ->groupBy('ii.normalized_service')
                ->orderByDesc('revenue')
                ->get();

            $monthlyTrend = collect(DB::select("
                SELECT {$expr} AS ym, SUM(total) AS revenue
                FROM xero_invoices
                WHERE xero_client_id = {$clientId}
                  AND type = 'ACCREC'
                  AND status IN ('AUTHORISED','PAID')
                  AND deleted_at IS NULL
                  AND invoice_date >= '{$fromDate}'
                GROUP BY YEAR(invoice_date), MONTH(invoice_date), {$expr}
                ORDER BY YEAR(invoice_date) ASC, MONTH(invoice_date) ASC
            "));

            $peakMonth = DB::selectOne("
                SELECT {$expr} AS ym, SUM(total) AS revenue
                FROM xero_invoices
                WHERE xero_client_id = {$clientId}
                  AND type = 'ACCREC'
                  AND status IN ('AUTHORISED','PAID')
                  AND deleted_at IS NULL
                GROUP BY YEAR(invoice_date), MONTH(invoice_date), {$expr}
                ORDER BY SUM(total) DESC
                LIMIT 1
            ");

            return [
                'client'                => $client->only(['id', 'name', 'email', 'city', 'country']),
                'total_paid'            => (float) $totalPaid,
                'outstanding'           => (float) $outstanding,
                'service_breakdown'     => $serviceBreakdown,
                'monthly_trend'         => $monthlyTrend,
                'peak_month'            => $peakMonth,
                'next_month_prediction' => $this->predictClientNextMonth($clientId),
            ];
        });
    }

    // ── 6. PREDICTION ENGINE ─────────────────────────────────────────

    public function predictNextMonthRevenue(array $filters = []): array
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_prediction_overall_{$hash}", self::CACHE_TTL * 2, function () use ($filters) {
            $trend  = $this->monthlyTrend(12, $filters);
            $values = $trend->pluck('revenue')->toArray();
            $labels = $trend->pluck('label')->toArray();
            $wma    = $this->weightedMovingAverage($values, 3);
            $lr     = $this->linearRegressionPredict($values);
            $final  = round(($wma + $lr) / 2, 2);

            return [
                'predicted_revenue' => $final,
                'method_wma'        => $wma,
                'method_lr'         => $lr,
                'confidence'        => $this->confidenceScore($values),
                'currency'          => 'AED',
                'for_month'         => Carbon::now()->addMonth()->format('F Y'),
                'trend_labels'      => $labels,
                'trend_values'      => $values,
            ];
        });
    }

    public function predictDepartmentRevenue(array $filters = []): Collection
    {
        $hash = md5(json_encode($filters));
        return Cache::remember("xero_prediction_departments_{$hash}", self::CACHE_TTL * 2, function () use ($filters) {
            $departments = XeroDepartment::when(!empty($filters['department_id']), function($q) use ($filters) {
                return $q->where('id', $filters['department_id']);
            })->get();
            
            $start       = Carbon::now()->subMonths(11)->startOfMonth();
            $to          = Carbon::now()->endOfMonth();
            $expr        = $this->monthExpr('inv.invoice_date');

            $rows = $this->baseFilteredItemQuery($start, $to, $filters)
                ->selectRaw("ii.department_id, {$expr} AS ym, SUM(ii.line_amount) AS rev")
                ->groupByRaw("ii.department_id, YEAR(inv.invoice_date), MONTH(inv.invoice_date), {$expr}")
                ->get()
                ->groupBy('department_id');

            return $departments->map(function ($dept) use ($start, $rows) {
                $deptRows = collect($rows->get($dept->id) ?? [])->pluck('rev', 'ym');
                
                $values = collect(range(0, 11))->map(fn($i) =>
                    (float) ($deptRows->get($start->copy()->addMonths($i)->format('Y-m')) ?? 0)
                )->toArray();

                return [
                    'department_id'   => $dept->id,
                    'department_name' => $dept->name,
                    'color'           => $dept->color,
                    'historical'      => $values,
                    'predicted'       => round(($this->weightedMovingAverage($values, 3) + $this->linearRegressionPredict($values)) / 2, 2),
                ];
            })
            ->filter(fn($d) => array_sum($d['historical']) > 0 || !empty($filters['department_id'])) 
            ->sortByDesc('predicted')->values();
        });
    }

    private function predictClientNextMonth(int $clientId): float
    {
        $start = Carbon::now()->subMonths(11)->startOfMonth();
        $expr  = $this->monthExpr('invoice_date');

        $rows = XeroInvoice::sales()->authorised()
            ->where('xero_client_id', $clientId)
            ->where('invoice_date', '>=', $start->toDateString())
            ->selectRaw("{$expr} AS ym, SUM(total) AS rev")
            ->groupByRaw("YEAR(invoice_date), MONTH(invoice_date), {$expr}")
            ->orderByRaw("YEAR(invoice_date) ASC, MONTH(invoice_date) ASC")
            ->get()
            ->pluck('rev', 'ym');

        $values = collect(range(0, 11))->map(fn($i) =>
            (float) ($rows->get($start->copy()->addMonths($i)->format('Y-m')) ?? 0)
        )->toArray();

        return round(($this->weightedMovingAverage($values, 3) + $this->linearRegressionPredict($values)) / 2, 2);
    }

    // ── Prediction algorithms ──────────────────────────────────────

    private function weightedMovingAverage(array $values, int $window = 3): float
    {
        $n = count($values);
        if ($n < $window) return $n > 0 ? array_sum($values) / $n : 0;
        $slice    = array_slice($values, -$window);
        $weights  = range(1, $window);
        $total    = array_sum($weights);
        $weighted = 0;
        foreach ($slice as $i => $v) $weighted += $v * $weights[$i];
        return $total > 0 ? round($weighted / $total, 2) : 0;
    }

    private function linearRegressionPredict(array $values): float
    {
        $n = count($values);
        if ($n < 2) return $values[0] ?? 0;
        $xs = range(0, $n - 1);
        $sumX = array_sum($xs); $sumY = array_sum($values);
        $sumXY = 0; $sumX2 = 0;
        for ($i = 0; $i < $n; $i++) { $sumXY += $xs[$i] * $values[$i]; $sumX2 += $xs[$i] ** 2; }
        $denom = $n * $sumX2 - $sumX ** 2;
        if ($denom == 0) return $sumY / $n;
        $b = ($n * $sumXY - $sumX * $sumY) / $denom;
        $a = ($sumY - $b * $sumX) / $n;
        return max(0, round($a + $b * $n, 2));
    }

    private function confidenceScore(array $values): int
    {
        if (count($values) < 3) return 50;
        $mean = array_sum($values) / count($values);
        if ($mean == 0) return 50;
        $variance = array_sum(array_map(fn($v) => ($v - $mean) ** 2, $values)) / count($values);
        return max(10, min(99, (int) round((1 - min(sqrt($variance) / $mean, 1)) * 100)));
    }
}