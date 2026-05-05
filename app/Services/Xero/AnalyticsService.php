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

    // helper — builds the month string without reserved words or LPAD
    private function monthExpr(string $dateCol): string
    {
        return "CONCAT(YEAR({$dateCol}),'-',RIGHT(CONCAT('0',MONTH({$dateCol})),2))";
    }

    // ═══════════════════════════════════════════════════════════
    // 1. FINANCIAL SUMMARY
    // ═══════════════════════════════════════════════════════════

    public function financialSummary(
        Carbon $from,
        Carbon $to,
        ?int   $clientId = null,
        ?int   $departmentId = null
    ): array {
        $key = "xero_summary_{$from->toDateString()}_{$to->toDateString()}_{$clientId}_{$departmentId}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $clientId, $departmentId) {
            $base = $this->baseInvoiceQuery($from, $to, $clientId);
            if ($departmentId) {
                $base->whereHas('items', fn($q) => $q->where('department_id', $departmentId));
            }
            $stats = $base->selectRaw('
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

    public function monthComparison(): array
    {
        return Cache::remember('xero_month_comparison', self::CACHE_TTL, function () {
            $current  = $this->monthRevenue(now()->year, now()->month);
            $previous = $this->monthRevenue(now()->subMonth()->year, now()->subMonth()->month);
            $growth   = $previous > 0
                ? round((($current - $previous) / $previous) * 100, 1)
                : ($current > 0 ? 100 : 0);

            return [
                'current_month'  => $current,
                'previous_month' => $previous,
                'growth_pct'     => $growth,
                'growth_abs'     => $current - $previous,
                'currency'       => 'AED',
            ];
        });
    }

    private function monthRevenue(int $year, int $month): float
    {
        return (float) XeroInvoice::sales()->authorised()
            ->whereYear('invoice_date', $year)
            ->whereMonth('invoice_date', $month)
            ->sum('total');
    }

    // ═══════════════════════════════════════════════════════════
    // 2. TRENDS
    // ═══════════════════════════════════════════════════════════

    public function monthlyTrend(int $months = 12): Collection
    {
        return Cache::remember("xero_monthly_trend_{$months}", self::CACHE_TTL, function () use ($months) {
            $from  = Carbon::now()->subMonths($months - 1)->startOfMonth()->toDateString();
            $to    = Carbon::now()->endOfMonth()->toDateString();
            $expr  = $this->monthExpr('invoice_date');
            $start = Carbon::now()->subMonths($months - 1)->startOfMonth();

            $rows = collect(DB::select("
                SELECT {$expr} AS ym,
                       SUM(total) AS revenue,
                       SUM(CASE WHEN status='PAID' THEN total ELSE 0 END) AS paid,
                       COUNT(*) AS invoice_count
                FROM xero_invoices
                WHERE type = 'ACCREC'
                  AND status IN ('AUTHORISED','PAID')
                  AND deleted_at IS NULL
                  AND invoice_date BETWEEN '{$from}' AND '{$to}'
                GROUP BY YEAR(invoice_date), MONTH(invoice_date), {$expr}
                ORDER BY YEAR(invoice_date) ASC, MONTH(invoice_date) ASC
            "))->keyBy('ym');

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

    public function highestRevenueMonth(): array
    {
        return Cache::remember('xero_peak_month', self::CACHE_TTL * 6, function () {
            $expr = $this->monthExpr('invoice_date');
            $row  = DB::selectOne("
                SELECT {$expr} AS ym, SUM(total) AS rev
                FROM xero_invoices
                WHERE type = 'ACCREC'
                  AND status IN ('AUTHORISED','PAID')
                  AND deleted_at IS NULL
                GROUP BY YEAR(invoice_date), MONTH(invoice_date), {$expr}
                ORDER BY SUM(total) DESC
                LIMIT 1
            ");

            return [
                'year_month' => $row?->ym,
                'label'      => $row ? Carbon::parse($row->ym . '-01')->format('F Y') : null,
                'revenue'    => (float) ($row?->rev ?? 0),
            ];
        });
    }

    // ═══════════════════════════════════════════════════════════
    // 3. DEPARTMENT ANALYTICS
    // ═══════════════════════════════════════════════════════════

    public function departmentRevenue(Carbon $from, Carbon $to): Collection
    {
        $key = "xero_dept_rev_{$from->toDateString()}_{$to->toDateString()}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to) {
            $rows = DB::table('xero_invoice_items as ii')
                ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
                ->join('xero_departments as d', 'd.id', '=', 'ii.department_id')
                ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
                ->where('inv.type', 'ACCREC')
                ->whereBetween('inv.invoice_date', [$from->toDateString(), $to->toDateString()])
                ->whereNull('inv.deleted_at')
                ->selectRaw('d.id AS department_id, d.name AS department_name, d.color,
                             SUM(ii.line_amount) AS revenue, COUNT(DISTINCT inv.id) AS invoice_count')
                ->groupBy('d.id', 'd.name', 'd.color')
                ->orderByDesc('revenue')
                ->get();

            $total = $rows->sum('revenue');
            return $rows->map(fn($row) => [
                'department_id'    => $row->department_id,
                'department_name'  => $row->department_name,
                'color'            => $row->color,
                'revenue'          => (float) $row->revenue,
                'invoice_count'    => (int)   $row->invoice_count,
                'contribution_pct' => $total > 0 ? round(($row->revenue / $total) * 100, 1) : 0,
            ]);
        });
    }

    public function departmentMonthlyTrend(int $months = 6): Collection
    {
        return Cache::remember("xero_dept_trend_{$months}", self::CACHE_TTL, function () use ($months) {
            $from = Carbon::now()->subMonths($months - 1)->startOfMonth()->toDateString();
            $expr = $this->monthExpr('inv.invoice_date');

            return collect(DB::select("
                SELECT d.name AS department_name,
                       d.color,
                       {$expr} AS ym,
                       SUM(ii.line_amount) AS revenue
                FROM xero_invoice_items ii
                JOIN xero_invoices inv ON inv.id = ii.invoice_id
                JOIN xero_departments d ON d.id = ii.department_id
                WHERE inv.status IN ('PAID','AUTHORISED')
                  AND inv.type = 'ACCREC'
                  AND inv.invoice_date >= '{$from}'
                  AND inv.deleted_at IS NULL
                GROUP BY d.name, d.color, YEAR(inv.invoice_date), MONTH(inv.invoice_date), {$expr}
                ORDER BY YEAR(inv.invoice_date) ASC, MONTH(inv.invoice_date) ASC
            "))->groupBy('department_name');
        });
    }

    // ═══════════════════════════════════════════════════════════
    // 4. SERVICE ANALYTICS
    // ═══════════════════════════════════════════════════════════

    public function serviceRevenue(Carbon $from, Carbon $to): Collection
    {
        $key = "xero_service_rev_{$from->toDateString()}_{$to->toDateString()}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to) {
            return DB::table('xero_invoice_items as ii')
                ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
                ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
                ->where('inv.type', 'ACCREC')
                ->whereBetween('inv.invoice_date', [$from->toDateString(), $to->toDateString()])
                ->whereNull('inv.deleted_at')
                ->selectRaw('ii.normalized_service, SUM(ii.line_amount) AS revenue, COUNT(DISTINCT inv.id) AS invoice_count')
                ->groupBy('ii.normalized_service')
                ->orderByDesc('revenue')
                ->get();
        });
    }

    // ═══════════════════════════════════════════════════════════
    // 5. CLIENT ANALYTICS
    // ═══════════════════════════════════════════════════════════

    public function topClients(Carbon $from, Carbon $to, int $limit = 10): Collection
    {
        $key = "xero_top_clients_{$from->toDateString()}_{$to->toDateString()}_{$limit}";

        return Cache::remember($key, self::CACHE_TTL, function () use ($from, $to, $limit) {
            return XeroInvoice::sales()->authorised()
                ->whereBetween('invoice_date', [$from, $to])
                ->with('client:id,name,email')
                ->selectRaw('xero_client_id, SUM(total) AS revenue, COUNT(*) AS invoice_count')
                ->groupBy('xero_client_id')
                ->orderByDesc('revenue')
                ->limit($limit)
                ->get()
                ->map(fn($r) => [
                    'client_id'     => $r->xero_client_id,
                    'client_name'   => $r->client?->name ?? 'Unknown',
                    'email'         => $r->client?->email,
                    'revenue'       => (float) $r->revenue,
                    'invoice_count' => (int)   $r->invoice_count,
                ]);
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

    // ═══════════════════════════════════════════════════════════
    // 6. PREDICTION ENGINE
    // ═══════════════════════════════════════════════════════════

    public function predictNextMonthRevenue(): array
    {
        return Cache::remember('xero_prediction_overall', self::CACHE_TTL * 2, function () {
            $trend  = $this->monthlyTrend(12);
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

    public function predictDepartmentRevenue(): Collection
    {
        return Cache::remember('xero_prediction_departments', self::CACHE_TTL * 2, function () {
            $departments = XeroDepartment::all();
            $from        = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
            $expr        = $this->monthExpr('inv.invoice_date');
            $start       = Carbon::now()->subMonths(11)->startOfMonth();

            return $departments->map(function ($dept) use ($from, $expr, $start) {
                $rows = collect(DB::select("
                    SELECT {$expr} AS ym, SUM(ii.line_amount) AS rev
                    FROM xero_invoice_items ii
                    JOIN xero_invoices inv ON inv.id = ii.invoice_id
                    WHERE ii.department_id = {$dept->id}
                      AND inv.status IN ('PAID','AUTHORISED')
                      AND inv.invoice_date >= '{$from}'
                    GROUP BY YEAR(inv.invoice_date), MONTH(inv.invoice_date), {$expr}
                    ORDER BY YEAR(inv.invoice_date) ASC, MONTH(inv.invoice_date) ASC
                "))->pluck('rev', 'ym');

                $values = collect(range(0, 11))->map(fn($i) =>
                    (float) ($rows->get($start->copy()->addMonths($i)->format('Y-m')) ?? 0)
                )->toArray();

                return [
                    'department_id'   => $dept->id,
                    'department_name' => $dept->name,
                    'color'           => $dept->color,
                    'historical'      => $values,
                    'predicted'       => round(($this->weightedMovingAverage($values, 3) + $this->linearRegressionPredict($values)) / 2, 2),
                ];
            })->sortByDesc('predicted')->values();
        });
    }

    public function predictTopClientRevenue(int $limit = 5): Collection
    {
        return Cache::remember("xero_prediction_clients_{$limit}", self::CACHE_TTL * 2, function () use ($limit) {
            return XeroInvoice::sales()->authorised()
                ->where('invoice_date', '>=', Carbon::now()->subMonths(3)->startOfMonth())
                ->selectRaw('xero_client_id, SUM(total) AS rev')
                ->groupBy('xero_client_id')
                ->orderByDesc('rev')
                ->limit($limit)
                ->pluck('xero_client_id')
                ->map(function ($clientId) {
                    return [
                        'client_id'   => $clientId,
                        'client_name' => XeroClient::find($clientId)?->name ?? 'Unknown',
                        'predicted'   => $this->predictClientNextMonth($clientId),
                    ];
                });
        });
    }

    private function predictClientNextMonth(int $clientId): float
    {
        $from  = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
        $expr  = $this->monthExpr('invoice_date');
        $start = Carbon::now()->subMonths(11)->startOfMonth();

        $rows = collect(DB::select("
            SELECT {$expr} AS ym, SUM(total) AS rev
            FROM xero_invoices
            WHERE xero_client_id = {$clientId}
              AND type = 'ACCREC'
              AND status IN ('AUTHORISED','PAID')
              AND deleted_at IS NULL
              AND invoice_date >= '{$from}'
            GROUP BY YEAR(invoice_date), MONTH(invoice_date), {$expr}
            ORDER BY YEAR(invoice_date) ASC, MONTH(invoice_date) ASC
        "))->pluck('rev', 'ym');

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

    private function baseInvoiceQuery(Carbon $from, Carbon $to, ?int $clientId = null)
    {
        $q = XeroInvoice::sales()->authorised()
            ->whereBetween('invoice_date', [$from->toDateString(), $to->toDateString()]);
        if ($clientId) $q->where('xero_client_id', $clientId);
        return $q;
    }
}