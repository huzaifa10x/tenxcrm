<?php

namespace App\Http\Controllers\Xero;

use App\Http\Controllers\Controller;
use App\Jobs\Xero\XeroFullSyncJob;
use App\Jobs\Xero\XeroIncrementalSyncJob;
use App\Models\Xero\XeroClient;
use App\Models\Xero\XeroDepartment;
use App\Models\Xero\XeroSyncLog;
use App\Services\Xero\AnalyticsService;
use App\Services\Xero\XeroAuthService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class XeroAnalyticsDashboardController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analytics,
        private readonly XeroAuthService  $auth,
    ) {}

    // ── OAuth ──────────────────────────────────────────────────────

    public function connect()
    {
        return redirect($this->auth->getAuthorizationUrl());
    }

    public function callback(Request $request)
    {
        try {
            $this->auth->handleCallback(
                $request->input('code'),
                $request->input('state')
            );

            XeroFullSyncJob::dispatch()->onQueue('xero');

            return redirect()->route('xero.analytics.dashboard')
                ->with('success', 'Xero connected! Full sync is running in background.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Xero auth failed: ' . $e->getMessage());
        }
    }

    // ── Main Dashboard ─────────────────────────────────────────────

    public function dashboard(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);
        $filters     = $this->extractFilters($request);

        $summary           = $this->analytics->financialSummary($from, $to, $filters);
        $monthComparison   = $this->analytics->monthComparison($filters);
        $peakMonth         = $this->analytics->highestRevenueMonth($filters);
        $trend12           = $this->analytics->monthlyTrend(12, $filters);
        $departmentRev     = $this->analytics->departmentRevenue($from, $to, $filters);
        $clientPerformance = $this->analytics->allClientPerformance($from, $to, $filters);
        $serviceBreakdown  = $this->analytics->serviceRevenue($from, $to, $filters);
        $prediction        = $this->analytics->predictNextMonthRevenue($filters);
        $deptPrediction    = $this->analytics->predictDepartmentRevenue($filters);
        $lastSync          = XeroSyncLog::where('entity', 'invoices')->where('status', 'completed')->latest('completed_at')->first();

        // Data for dropdowns
        $departments = XeroDepartment::all();
        $clients     = XeroClient::active()->orderBy('name')->get(['id', 'name']);
        $services    = DB::table('xero_invoice_items')->whereNotNull('normalized_service')->distinct()->pluck('normalized_service');

        return view('xero.analytics.dashboard', compact(
            'summary', 'monthComparison', 'peakMonth', 'trend12',
            'departmentRev', 'clientPerformance', 'prediction', 'deptPrediction',
            'serviceBreakdown', 'departments', 'clients', 'services', 
            'from', 'to', 'lastSync', 'filters'
        ));
    }

    // ── AJAX Endpoints ─────────────────────────────────────────────

    public function summaryAjax(Request $request): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $filters     = $this->extractFilters($request);

        return response()->json([
            'summary'              => $this->analytics->financialSummary($from, $to, $filters),
            'department_breakdown' => $this->analytics->departmentRevenue($from, $to, $filters),
            'service_breakdown'    => $this->analytics->serviceRevenue($from, $to, $filters),
            'top_clients'          => $this->analytics->allClientPerformance($from, $to, $filters),
        ]);
    }

    public function departmentTrendAjax(Request $request): JsonResponse
    {
        $months  = (int) $request->input('months', 6);
        $months  = in_array($months, [3, 6, 12]) ? $months : 6;
        $filters = $this->extractFilters($request);

        return response()->json([
            'trend' => $this->analytics->departmentMonthlyTrend($months, $filters),
        ]);
    }

    public function clientAnalyticsAjax(Request $request, int $clientId): JsonResponse
    {
        return response()->json(
            $this->analytics->clientAnalytics($clientId)
        );
    }

    public function predictionsAjax(Request $request): JsonResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $filters     = $this->extractFilters($request);

        return response()->json([
            'overall'     => $this->analytics->predictNextMonthRevenue($filters),
            'departments' => $this->analytics->predictDepartmentRevenue($filters),
            'top_clients' => $this->analytics->allClientPerformance($from, $to, $filters),
        ]);
    }

    public function clientSearch(Request $request): JsonResponse
    {
        $q = $request->input('q', '');

        $clients = XeroClient::active()
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'email']);

        return response()->json($clients);
    }

    // ── Sync Controls ──────────────────────────────────────────────

    public function triggerFullSync(): JsonResponse
    {
        XeroFullSyncJob::dispatch()->onQueue('xero');
        return response()->json(['message' => 'Full sync queued.']);
    }

    public function triggerIncrementalSync(): JsonResponse
    {
        XeroIncrementalSyncJob::dispatch()->onQueue('xero');
        return response()->json(['message' => 'Incremental sync queued.']);
    }

    public function syncStatus(): JsonResponse
    {
        $logs = XeroSyncLog::whereIn('entity', ['invoices', 'contacts'])
            ->orderByDesc('started_at')
            ->limit(10)
            ->get([
                'entity', 'sync_type', 'status',
                'records_fetched', 'records_created', 'records_updated',
                'started_at', 'completed_at',
            ]);

        return response()->json(['logs' => $logs]);
    }

    // ── Helpers ────────────────────────────────────────────────────

    private function resolveDateRange(Request $request): array
    {
        $preset = $request->input('range', 'this_month');

        return match ($preset) {
            'today'      => [Carbon::today(), Carbon::today()],
            'this_week'  => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'last_month' => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            'last_3m'    => [Carbon::now()->subMonths(3)->startOfMonth(), Carbon::now()->endOfMonth()],
            'last_6m'    => [Carbon::now()->subMonths(6)->startOfMonth(), Carbon::now()->endOfMonth()],
            'this_year'  => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom'     => [
                Carbon::parse($request->input('from', Carbon::now()->startOfMonth())),
                Carbon::parse($request->input('to', Carbon::now())),
            ],
            default      => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }

    private function extractFilters(Request $request): array
    {
        return array_filter(
            $request->only(['client_id', 'department_id', 'service', 'min_amount', 'max_amount']),
            fn($v) => $v !== null && $v !== ''
        );
    }
}