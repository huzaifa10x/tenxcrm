<?php

namespace App\Services\Xero;

use App\Models\Xero\XeroInvoice;
use App\Models\Xero\XeroMonthlySnapshot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AnalyticsSnapshotService
{
    public function rebuildAll(): void
    {
        Log::info('Xero Snapshots: Starting full rebuild.');

        $this->rebuildTotalSnapshots();
        $this->rebuildDepartmentSnapshots();
        $this->rebuildServiceSnapshots();
        $this->rebuildClientSnapshots();

        Log::info('Xero Snapshots: Rebuild complete.');
    }

    public function rebuildRecentMonths(int $months = 3): void
    {
        $monthsToRebuild = collect(range(0, $months - 1))
            ->map(fn($i) => Carbon::now()->subMonths($i)->format('Y-m'))
            ->toArray();

        foreach ($monthsToRebuild as $ym) {
            $this->rebuildMonthTotal($ym);
            $this->rebuildMonthDepartments($ym);
            $this->rebuildMonthServices($ym);
            $this->rebuildMonthClients($ym);
        }
    }

    // ── Total snapshots ────────────────────────────────────────────

    private function rebuildTotalSnapshots(): void
    {
        foreach ($this->getAllMonths() as $ym) {
            $this->rebuildMonthTotal($ym);
        }
    }

    private function rebuildMonthTotal(string $ym): void
    {
        [$y, $m] = explode('-', $ym);

        $row = XeroInvoice::sales()
            ->authorised()
            ->whereYear('invoice_date', $y)
            ->whereMonth('invoice_date', $m)
            ->selectRaw('SUM(total) AS revenue, COUNT(*) AS invoice_count')
            ->first();

        XeroMonthlySnapshot::updateOrCreate(
            [
                'year_month'         => $ym,
                'snapshot_type'      => 'total',
                'department_id'      => null,
                'normalized_service' => null,
                'xero_client_id'     => null,
            ],
            [
                'revenue'       => $row->revenue ?? 0,
                'invoice_count' => $row->invoice_count ?? 0,
                'calculated_at' => now(),
            ]
        );
    }

    // ── Department snapshots ───────────────────────────────────────

    private function rebuildDepartmentSnapshots(): void
    {
        foreach ($this->getAllMonths() as $ym) {
            $this->rebuildMonthDepartments($ym);
        }
    }

    private function rebuildMonthDepartments(string $ym): void
    {
        [$y, $m] = explode('-', $ym);

        $rows = DB::table('xero_invoice_items as ii')
            ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
            ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
            ->where('inv.type', 'ACCREC')
            ->whereYear('inv.invoice_date', $y)
            ->whereMonth('inv.invoice_date', $m)
            ->whereNull('inv.deleted_at')
            ->whereNotNull('ii.department_id')
            ->selectRaw('ii.department_id, SUM(ii.line_amount) AS revenue, COUNT(DISTINCT inv.id) AS invoice_count')
            ->groupBy('ii.department_id')
            ->get();

        foreach ($rows as $row) {
            XeroMonthlySnapshot::updateOrCreate(
                [
                    'year_month'         => $ym,
                    'snapshot_type'      => 'department',
                    'department_id'      => $row->department_id,
                    'normalized_service' => null,
                    'xero_client_id'     => null,
                ],
                [
                    'revenue'       => $row->revenue,
                    'invoice_count' => $row->invoice_count,
                    'calculated_at' => now(),
                ]
            );
        }
    }

    // ── Service snapshots ──────────────────────────────────────────

    private function rebuildServiceSnapshots(): void
    {
        foreach ($this->getAllMonths() as $ym) {
            $this->rebuildMonthServices($ym);
        }
    }

    private function rebuildMonthServices(string $ym): void
    {
        [$y, $m] = explode('-', $ym);

        $rows = DB::table('xero_invoice_items as ii')
            ->join('xero_invoices as inv', 'inv.id', '=', 'ii.invoice_id')
            ->whereIn('inv.status', ['PAID', 'AUTHORISED'])
            ->where('inv.type', 'ACCREC')
            ->whereYear('inv.invoice_date', $y)
            ->whereMonth('inv.invoice_date', $m)
            ->whereNull('inv.deleted_at')
            ->whereNotNull('ii.normalized_service')
            ->selectRaw('ii.normalized_service, SUM(ii.line_amount) AS revenue, COUNT(DISTINCT inv.id) AS invoice_count')
            ->groupBy('ii.normalized_service')
            ->get();

        foreach ($rows as $row) {
            XeroMonthlySnapshot::updateOrCreate(
                [
                    'year_month'         => $ym,
                    'snapshot_type'      => 'service',
                    'department_id'      => null,
                    'normalized_service' => $row->normalized_service,
                    'xero_client_id'     => null,
                ],
                [
                    'revenue'       => $row->revenue,
                    'invoice_count' => $row->invoice_count,
                    'calculated_at' => now(),
                ]
            );
        }
    }

    // ── Client snapshots ───────────────────────────────────────────

    private function rebuildClientSnapshots(): void
    {
        foreach ($this->getAllMonths() as $ym) {
            $this->rebuildMonthClients($ym);
        }
    }

    private function rebuildMonthClients(string $ym): void
    {
        [$y, $m] = explode('-', $ym);

        $rows = XeroInvoice::sales()
            ->authorised()
            ->whereYear('invoice_date', $y)
            ->whereMonth('invoice_date', $m)
            ->selectRaw('xero_client_id, SUM(total) AS revenue, COUNT(*) AS invoice_count')
            ->groupBy('xero_client_id')
            ->get();

        foreach ($rows as $row) {
            XeroMonthlySnapshot::updateOrCreate(
                [
                    'year_month'         => $ym,
                    'snapshot_type'      => 'client',
                    'department_id'      => null,
                    'normalized_service' => null,
                    'xero_client_id'     => $row->xero_client_id,
                ],
                [
                    'revenue'       => $row->revenue,
                    'invoice_count' => $row->invoice_count,
                    'calculated_at' => now(),
                ]
            );
        }
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function getAllMonths(): array
    {
        $earliest = XeroInvoice::sales()
            ->authorised()
            ->selectRaw("DATE_FORMAT(MIN(invoice_date),'%Y-%m') AS ym")
            ->value('ym');

        if (!$earliest) return [];

        $start  = Carbon::parse($earliest . '-01');
        $end    = Carbon::now();
        $months = [];

        while ($start->lte($end)) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        return $months;
    }
}