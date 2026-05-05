<x-app-layout>
    <x-slot name="header">Xero Financial Analytics</x-slot>

    {{-- ═══════════════════════════════════════════════════════════
         STYLES
    ═══════════════════════════════════════════════════════════ --}}
    <style>
        :root {
            --xa-bg:      #f0f2f7;
            --xa-surface: #ffffff;
            --xa-border:  #e2e6ef;
            --xa-text:    #1a1f36;
            --xa-muted:   #6b7280;
            --xa-accent:  #3b5bdb;
            --xa-success: #087f5b;
            --xa-warning: #e67700;
            --xa-danger:  #c92a2a;
            --xa-radius:  12px;
            --xa-shadow:  0 1px 4px rgba(0,0,0,.07), 0 4px 16px rgba(0,0,0,.05);
        }

        .xa-page { background: var(--xa-bg); min-height: 100vh; padding: 1.5rem 1.5rem 3rem; }

        /* ── Topbar ── */
        .xa-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.75rem; }
        .xa-topbar h1 { font-size: 1.4rem; font-weight: 800; color: var(--xa-text); margin: 0; }
        .xa-topbar p  { font-size: .8rem; color: var(--xa-muted); margin: 0; }

        /* ── Filter bar ── */
        .xa-filters { background: var(--xa-surface); border: 1px solid var(--xa-border); border-radius: var(--xa-radius); padding: 1rem 1.25rem; margin-bottom: 1.5rem; box-shadow: var(--xa-shadow); }
        .xa-filters .form-label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: var(--xa-muted); margin-bottom: .3rem; }
        .xa-filters .form-control, .xa-filters .form-select { font-size: .82rem; border-radius: 8px; border-color: var(--xa-border); }

        /* ── Card ── */
        .xa-card { background: var(--xa-surface); border: 1px solid var(--xa-border); border-radius: var(--xa-radius); box-shadow: var(--xa-shadow); overflow: hidden; }
        .xa-card-header { padding: 1rem 1.25rem .75rem; border-bottom: 1px solid var(--xa-border); display: flex; align-items: center; justify-content: space-between; }
        .xa-card-title { font-size: .88rem; font-weight: 700; color: var(--xa-text); margin: 0; display: flex; align-items: center; gap: .5rem; }
        .xa-card-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: .85rem; }
        .xa-card-body { padding: 1.25rem; }

        /* ── KPI stats ── */
        .xa-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .85rem; margin-bottom: 1.5rem; }
        .xa-kpi { background: var(--xa-surface); border: 1px solid var(--xa-border); border-radius: var(--xa-radius); padding: 1.1rem 1.15rem; position: relative; overflow: hidden; box-shadow: var(--xa-shadow); }
        .xa-kpi::before { content:''; position:absolute; top:0; left:0; width:4px; height:100%; }
        .xa-kpi.accent-blue::before   { background: linear-gradient(180deg,#4c6ef5,#748ffc); }
        .xa-kpi.accent-green::before  { background: linear-gradient(180deg,#087f5b,#20c997); }
        .xa-kpi.accent-orange::before { background: linear-gradient(180deg,#e67700,#ffa94d); }
        .xa-kpi.accent-red::before    { background: linear-gradient(180deg,#c92a2a,#f03e3e); }
        .xa-kpi.accent-purple::before { background: linear-gradient(180deg,#7048e8,#9775fa); }
        .xa-kpi-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--xa-muted); margin-bottom:.3rem; }
        .xa-kpi-value { font-size:1.65rem; font-weight:800; color:var(--xa-text); line-height:1.1; }
        .xa-kpi-sub   { font-size:.73rem; color:var(--xa-muted); margin-top:.25rem; }
        .xa-kpi-badge { display:inline-block; font-size:.7rem; font-weight:700; padding:.15rem .5rem; border-radius:20px; margin-top:.4rem; }
        .badge-up   { background:#d3f9d8; color:var(--xa-success); }
        .badge-down { background:#ffe3e3; color:var(--xa-danger);  }
        .badge-flat { background:#e9ecef; color:var(--xa-muted);   }

        /* ── Charts grid ── */
        .xa-charts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(440px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem; }
        .xa-charts-grid.full { grid-template-columns: 1fr; }

        /* ── Department pills ── */
        .xa-dept-pill { display:flex; align-items:center; justify-content:space-between; padding:.6rem .85rem; border-radius:9px; margin-bottom:.5rem; }
        .xa-dept-pill-bar { height:6px; border-radius:3px; margin-top:.4rem; }

        /* ── Client table ── */
        .xa-table { font-size:.82rem; }
        .xa-table thead th { background:#f8f9fc; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px; color:var(--xa-muted); }
        .xa-table tbody tr:hover { background:#f8f9ff; cursor:pointer; }

        /* ── Prediction card ── */
        .xa-predict-banner { background:linear-gradient(135deg,#3b5bdb 0%,#7048e8 100%); border-radius:var(--xa-radius); padding:1.5rem; color:#fff; margin-bottom:1.5rem; }
        .xa-predict-amount { font-size:2.5rem; font-weight:800; line-height:1.1; }
        .xa-predict-label  { font-size:.8rem; opacity:.8; margin-bottom:.5rem; text-transform:uppercase; letter-spacing:.5px; }
        .xa-predict-confidence { display:inline-block; background:rgba(255,255,255,.2); border-radius:20px; padding:.2rem .8rem; font-size:.78rem; }
        .xa-methods { display:flex; gap:1rem; margin-top:.75rem; }
        .xa-method  { background:rgba(255,255,255,.15); border-radius:8px; padding:.5rem .85rem; font-size:.78rem; }

        /* ── Sync status ── */
        .xa-sync-badge { font-size:.72rem; font-weight:600; padding:.2rem .6rem; border-radius:20px; }
        .sync-ok   { background:#d3f9d8; color:var(--xa-success); }
        .sync-warn { background:#fff9db; color:var(--xa-warning); }
        .sync-fail { background:#ffe3e3; color:var(--xa-danger);  }

        /* ── Client detail modal ── */
        #clientModal .modal-content { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,.15); }
        #clientModal .modal-header  { background: var(--xa-text); color:#fff; border-radius: 16px 16px 0 0; }

        /* ── Loading overlay ── */
        .xa-loading { position:relative; }
        .xa-loading::after { content:''; position:absolute; inset:0; background:rgba(255,255,255,.7); border-radius:var(--xa-radius); display:flex; align-items:center; justify-content:center; }

        @media(max-width:767px) {
            .xa-charts-grid { grid-template-columns: 1fr; }
            .xa-kpi-grid    { grid-template-columns: repeat(2, 1fr); }
            .xa-page        { padding: 1rem .85rem 2rem; }
        }
    </style>

    <div class="xa-page">

        {{-- ── Top bar ── --}}
        <div class="xa-topbar">
            <div>
                <h1><i class="bi bi-bar-chart-line-fill me-2" style="color:#4c6ef5;"></i>Financial Analytics</h1>
                <p>
                    Data from Xero · Local DB ·
                    @if($lastSync)
                        Last synced: {{ $lastSync->completed_at?->diffForHumans() }}
                        <span class="xa-sync-badge sync-ok ms-1">✓ In sync</span>
                    @else
                        <span class="xa-sync-badge sync-warn">No sync yet</span>
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->user()->hasRole('ADMIN'))
                    <button class="btn btn-outline-secondary btn-sm" id="syncIncrementalBtn">
                        <i class="bi bi-arrow-clockwise me-1"></i>Sync Now
                    </button>
                    <button class="btn btn-outline-danger btn-sm" id="syncFullBtn">
                        <i class="bi bi-cloud-download me-1"></i>Full Sync
                    </button>
                @endif
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filtersModal">
                    <i class="bi bi-sliders me-1"></i>Filters
                </button>
            </div>
        </div>

        {{-- ── Active filter indicator ── --}}
        <div id="filterIndicator" class="mb-3 d-none">
            <span class="badge bg-primary">
                <i class="bi bi-funnel-fill me-1"></i>
                <span id="filterLabel"></span>
                <a href="{{ route('xero.analytics.dashboard') }}" class="text-white ms-2" style="text-decoration:none;">✕</a>
            </span>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             KPI CARDS
        ═══════════════════════════════════════════════════════ --}}
        <div class="xa-kpi-grid" id="kpiGrid">

            <div class="xa-kpi accent-blue">
                <div class="xa-kpi-label">Total Revenue</div>
                <div class="xa-kpi-value" id="kpiTotal">
                    AED {{ number_format($summary['total_revenue'], 0) }}
                </div>
                <div class="xa-kpi-sub">{{ $from->format('M d') }} – {{ $to->format('M d, Y') }}</div>
            </div>

            <div class="xa-kpi accent-green">
                <div class="xa-kpi-label">Collected (Paid)</div>
                <div class="xa-kpi-value" id="kpiPaid">
                    AED {{ number_format($summary['paid_revenue'], 0) }}
                </div>
                <div class="xa-kpi-sub">{{ $summary['invoice_count'] }} invoices</div>
            </div>

            <div class="xa-kpi accent-orange">
                <div class="xa-kpi-label">Outstanding</div>
                <div class="xa-kpi-value" id="kpiOutstanding">
                    AED {{ number_format($summary['outstanding_revenue'], 0) }}
                </div>
                <div class="xa-kpi-sub">Authorised, unpaid</div>
            </div>

            <div class="xa-kpi accent-purple">
                <div class="xa-kpi-label">vs Last Month</div>
                <div class="xa-kpi-value" id="kpiGrowth">
                    @php $g = $monthComparison['growth_pct']; @endphp
                    {{ $g > 0 ? '+' : '' }}{{ $g }}%
                </div>
                <div class="xa-kpi-sub">
                    AED {{ number_format($monthComparison['current_month'], 0) }} vs
                    AED {{ number_format($monthComparison['previous_month'], 0) }}
                </div>
                @if($g > 0)
                    <span class="xa-kpi-badge badge-up">↑ Growing</span>
                @elseif($g < 0)
                    <span class="xa-kpi-badge badge-down">↓ Declining</span>
                @else
                    <span class="xa-kpi-badge badge-flat">→ Flat</span>
                @endif
            </div>

            <div class="xa-kpi accent-red">
                <div class="xa-kpi-label">Peak Month Ever</div>
                <div class="xa-kpi-value">
                    AED {{ number_format($peakMonth['revenue'], 0) }}
                </div>
                <div class="xa-kpi-sub">{{ $peakMonth['label'] ?? '—' }}</div>
            </div>

            <div class="xa-kpi accent-blue">
                <div class="xa-kpi-label">Avg Invoice Value</div>
                <div class="xa-kpi-value">
                    AED {{ number_format($summary['avg_invoice_value'], 0) }}
                </div>
                <div class="xa-kpi-sub">This period</div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════
             PREDICTION BANNER
        ═══════════════════════════════════════════════════════ --}}
        <div class="xa-predict-banner mb-4" id="predictionBanner">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="xa-predict-label"><i class="bi bi-cpu me-1"></i>AI Revenue Prediction</div>
                    <div class="xa-predict-amount">
                        AED {{ number_format($prediction['predicted_revenue'], 0) }}
                    </div>
                    <div style="font-size:.85rem; opacity:.85; margin-top:.3rem;">
                        Forecast for {{ $prediction['for_month'] }}
                    </div>
                    <span class="xa-predict-confidence mt-2 d-inline-block">
                        <i class="bi bi-shield-check me-1"></i>{{ $prediction['confidence'] }}% confidence
                    </span>
                    <div class="xa-methods">
                        <div class="xa-method">
                            <div style="font-size:.65rem; opacity:.7; text-transform:uppercase;">Weighted MA</div>
                            <div style="font-weight:700;">AED {{ number_format($prediction['method_wma'], 0) }}</div>
                        </div>
                        <div class="xa-method">
                            <div style="font-size:.65rem; opacity:.7; text-transform:uppercase;">Linear Regression</div>
                            <div style="font-weight:700;">AED {{ number_format($prediction['method_lr'], 0) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mt-3 mt-md-0">
                    <canvas id="miniTrendChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             CHARTS ROW 1: Revenue Trend + Department Breakdown
        ═══════════════════════════════════════════════════════ --}}
        <div class="xa-charts-grid mb-4">

            {{-- Revenue trend ── --}}
            <div class="xa-card">
                <div class="xa-card-header">
                    <h6 class="xa-card-title">
                        <span class="xa-card-icon" style="background:#eef2ff;color:#4c6ef5;"><i class="bi bi-graph-up"></i></span>
                        12-Month Revenue Trend
                    </h6>
                    <select id="trendMonthsSelect" class="form-select form-select-sm w-auto" style="font-size:.78rem;border-radius:8px;">
                        <option value="6">6 Months</option>
                        <option value="12" selected>12 Months</option>
                    </select>
                </div>
                <div class="xa-card-body">
                    <canvas id="revenueTrendChart" height="220"></canvas>
                </div>
            </div>

            {{-- Department breakdown ── --}}
            <div class="xa-card">
                <div class="xa-card-header">
                    <h6 class="xa-card-title">
                        <span class="xa-card-icon" style="background:#f3f0ff;color:#7048e8;"><i class="bi bi-pie-chart"></i></span>
                        Revenue by Department
                    </h6>
                </div>
                <div class="xa-card-body">
                    <div class="row align-items-center">
                        <div class="col-5">
                            <canvas id="deptDonutChart" height="180"></canvas>
                        </div>
                        <div class="col-7" id="deptLegend">
                            @foreach($departmentRev as $dept)
                                <div class="xa-dept-pill" style="background:{{ $dept['color'] }}15;">
                                    <div>
                                        <div style="font-size:.82rem; font-weight:700; color:{{ $dept['color'] }};">{{ $dept['department_name'] }}</div>
                                        <div style="font-size:.75rem; color:var(--xa-muted);">AED {{ number_format($dept['revenue'], 0) }}</div>
                                    </div>
                                    <div style="font-size:.9rem; font-weight:800; color:var(--xa-text);">{{ $dept['contribution_pct'] }}%</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════
             CHARTS ROW 2: Department Trend + Predictions
        ═══════════════════════════════════════════════════════ --}}
        <div class="xa-charts-grid mb-4">

            {{-- Department monthly trend ── --}}
            <div class="xa-card">
                <div class="xa-card-header">
                    <h6 class="xa-card-title">
                        <span class="xa-card-icon" style="background:#e6fcf5;color:#087f5b;"><i class="bi bi-bar-chart-steps"></i></span>
                        Department Monthly Trend
                    </h6>
                    <select id="deptTrendMonths" class="form-select form-select-sm w-auto" style="font-size:.78rem;border-radius:8px;">
                        <option value="3">3 Months</option>
                        <option value="6" selected>6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
                <div class="xa-card-body">
                    <canvas id="deptTrendChart" height="220"></canvas>
                </div>
            </div>

            {{-- Department predictions ── --}}
            <div class="xa-card">
                <div class="xa-card-header">
                    <h6 class="xa-card-title">
                        <span class="xa-card-icon" style="background:#fff9db;color:#e67700;"><i class="bi bi-lightning"></i></span>
                        Dept. Predictions – {{ now()->addMonth()->format('F Y') }}
                    </h6>
                </div>
                <div class="xa-card-body">
                    @foreach($deptPrediction as $dp)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:.82rem; font-weight:700; color:{{ $dp['color'] }};">{{ $dp['department_name'] }}</span>
                                <span style="font-size:.85rem; font-weight:800;">AED {{ number_format($dp['predicted'], 0) }}</span>
                            </div>
                            @php
                                $maxPred = $deptPrediction->max('predicted');
                                $pct = $maxPred > 0 ? ($dp['predicted'] / $maxPred) * 100 : 0;
                            @endphp
                            <div style="background:#f0f2f7; height:6px; border-radius:3px; overflow:hidden;">
                                <div style="background:{{ $dp['color'] }}; height:100%; width:{{ $pct }}%; border-radius:3px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════
             TOP CLIENTS TABLE
        ═══════════════════════════════════════════════════════ --}}
        <div class="xa-card mb-4">
            <div class="xa-card-header">
                <h6 class="xa-card-title">
                    <span class="xa-card-icon" style="background:#fff0f6;color:#c2255c;"><i class="bi bi-people"></i></span>
                    Top Clients by Revenue
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div style="position:relative;">
                        <input type="text" id="clientSearchInput" class="form-control form-control-sm" placeholder="Search client…"
                               style="border-radius:8px;width:200px;font-size:.8rem;">
                        <div id="clientSearchDropdown" class="shadow rounded-3 bg-white" style="position:absolute;top:100%;left:0;width:280px;z-index:999;display:none;border:1px solid var(--xa-border);max-height:200px;overflow-y:auto;"></div>
                    </div>
                </div>
            </div>
            <div class="xa-card-body" style="padding:0;">
                <div style="overflow-x:auto;">
                    <table class="table xa-table mb-0" id="clientsTable">
                        <thead>
                            <tr>
                                <th style="padding:.65rem 1rem;">#</th>
                                <th>Client</th>
                                <th>Email</th>
                                <th class="text-end">Revenue (AED)</th>
                                <th class="text-center">Invoices</th>
                                <th class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topClients as $i => $client)
                                <tr>
                                    <td style="padding:.7rem 1rem;">{{ $i + 1 }}</td>
                                    <td><strong style="font-size:.85rem;">{{ $client['client_name'] }}</strong></td>
                                    <td><small class="text-muted">{{ $client['email'] ?? '—' }}</small></td>
                                    <td class="text-end fw-bold text-success" style="font-size:.85rem;">
                                        {{ number_format($client['revenue'], 2) }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $client['invoice_count'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" style="font-size:.75rem;"
                                                onclick="loadClientDetail({{ $client['client_id'] }}, '{{ addslashes($client['client_name']) }}')">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No data for this period.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>{{-- end .xa-page --}}

    {{-- ═══════════════════════════════════════════════════════════
         FILTERS MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="filtersModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;box-shadow:0 20px 60px rgba(0,0,0,.15);">
                <div class="modal-header" style="background:var(--xa-text);color:#fff;border-radius:16px 16px 0 0;">
                    <span class="modal-title fw-bold"><i class="bi bi-sliders me-2"></i>Dashboard Filters</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="filterForm" method="GET" action="{{ route('xero.analytics.dashboard') }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Date Range</label>
                            <select name="range" id="rangeSelect" class="form-select" style="font-size:.85rem;border-radius:8px;">
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="last_3m">Last 3 Months</option>
                                <option value="last_6m">Last 6 Months</option>
                                <option value="this_year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        <div id="customDateRow" class="row g-2 mb-3 d-none">
                            <div class="col">
                                <label class="form-label" style="font-size:.78rem;font-weight:600;">From</label>
                                <input type="date" name="from" class="form-control" style="font-size:.82rem;border-radius:8px;">
                            </div>
                            <div class="col">
                                <label class="form-label" style="font-size:.78rem;font-weight:600;">To</label>
                                <input type="date" name="to" class="form-control" style="font-size:.82rem;border-radius:8px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:.82rem;">Department</label>
                            <select name="department_id" class="form-select" style="font-size:.85rem;border-radius:8px;">
                                <option value="">All Departments</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex gap-2 pt-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">Apply Filters</button>
                            <a href="{{ route('xero.analytics.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         CLIENT DETAIL MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="clientModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" id="clientModalContent">
                <div class="modal-header" style="background:var(--xa-text);color:#fff;border-radius:16px 16px 0 0;">
                    <span class="modal-title fw-bold" id="clientModalTitle"><i class="bi bi-person-circle me-2"></i>Client Analytics</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="clientModalBody">
                    <div class="text-center py-5 text-muted">Loading…</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════════════ --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script>
    // ── Global chart defaults ──────────────────────────────────────
    Chart.defaults.font.family = "'Inter','Segoe UI',sans-serif";
    Chart.defaults.font.size   = 12;
    Chart.defaults.color       = '#6b7280';

    // ── Data from Blade ────────────────────────────────────────────
    const trendData = @json($trend12);
    const deptData  = @json($departmentRev);
    const predData  = @json($prediction);

    // ── Mini trend (in prediction banner) ─────────────────────────
    const miniCtx = document.getElementById('miniTrendChart').getContext('2d');
    new Chart(miniCtx, {
        type: 'line',
        data: {
            labels: trendData.map(d => d.label),
            datasets: [{
                data:            trendData.map(d => d.revenue),
                borderColor:     'rgba(255,255,255,.9)',
                backgroundColor: 'rgba(255,255,255,.15)',
                fill:            true,
                tension:         .4,
                pointRadius:     0,
                borderWidth:     2,
            }]
        },
        options: {
            animation: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales:  { x: { display: false }, y: { display: false } },
        }
    });

    // ── Revenue trend chart ────────────────────────────────────────
    let revChart;
    function buildRevChart(data) {
        if (revChart) revChart.destroy();
        const ctx = document.getElementById('revenueTrendChart').getContext('2d');
        revChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:   data.map(d => d.label),
                datasets: [
                    {
                        label:           'Total Revenue',
                        data:            data.map(d => d.revenue),
                        backgroundColor: '#4c6ef590',
                        borderColor:     '#4c6ef5',
                        borderWidth:     1,
                        borderRadius:    6,
                        order:           2,
                    },
                    {
                        type:        'line',
                        label:       'Paid',
                        data:        data.map(d => d.paid),
                        borderColor: '#087f5b',
                        backgroundColor: '#087f5b15',
                        fill:        true,
                        tension:     .4,
                        pointRadius: 4,
                        borderWidth: 2,
                        order:       1,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index' },
                plugins: { legend: { position: 'top', labels: { boxWidth: 12 } } },
                scales: {
                    y: {
                        ticks: { callback: v => 'AED ' + v.toLocaleString() },
                        grid:  { color: '#f0f2f7' },
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }
    buildRevChart(trendData);

    document.getElementById('trendMonthsSelect').addEventListener('change', async function() {
        const months = this.value;
        const res    = await fetch(`{{ route('xero.analytics.dept-trend') }}?months=${months}`);
        // Rebuild using months trend — refetch main trend too
        location.reload(); // simple approach for now
    });

    // ── Department donut ───────────────────────────────────────────
    (function() {
        const ctx = document.getElementById('deptDonutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels:   deptData.map(d => d.department_name),
                datasets: [{
                    data:             deptData.map(d => d.revenue),
                    backgroundColor:  deptData.map(d => d.color),
                    borderWidth:      2,
                    borderColor:      '#ffffff',
                    hoverOffset:      6,
                }]
            },
            options: {
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` AED ${ctx.parsed.toLocaleString()} (${ctx.dataset.data[ctx.dataIndex]})`
                        }
                    }
                }
            }
        });
    })();

    // ── Department trend (stacked bar) ────────────────────────────
    let deptTrendChart;
    async function buildDeptTrendChart(months = 6) {
        const res  = await fetch(`{{ route('xero.analytics.dept-trend') }}?months=${months}`);
        const json = await res.json();
        const trend = json.trend;

        // Build unified month labels
        const allMonths = [...new Set(
            Object.values(trend).flatMap(rows => rows.map(r => r.year_month))
        )].sort();

        const datasets = Object.entries(trend).map(([deptName, rows]) => {
            const color = rows[0]?.color ?? '#aaa';
            const dataMap = Object.fromEntries(rows.map(r => [r.year_month, parseFloat(r.revenue)]));
            return {
                label:           deptName,
                data:            allMonths.map(m => dataMap[m] ?? 0),
                backgroundColor: color + 'bb',
                borderColor:     color,
                borderWidth:     1,
                borderRadius:    4,
            };
        });

        if (deptTrendChart) deptTrendChart.destroy();
        const ctx = document.getElementById('deptTrendChart').getContext('2d');
        deptTrendChart = new Chart(ctx, {
            type: 'bar',
            data: { labels: allMonths, datasets },
            options: {
                responsive: true,
                scales: {
                    x:  { stacked: true, grid: { display: false } },
                    y:  { stacked: true, ticks: { callback: v => 'AED ' + v.toLocaleString() }, grid: { color: '#f0f2f7' } },
                },
                plugins: { legend: { position: 'top', labels: { boxWidth: 12 } } }
            }
        });
    }
    buildDeptTrendChart();

    document.getElementById('deptTrendMonths').addEventListener('change', function() {
        buildDeptTrendChart(this.value);
    });

    // ── Client detail modal ────────────────────────────────────────
    let clientDetailChart;
    async function loadClientDetail(clientId, clientName) {
        document.getElementById('clientModalTitle').innerHTML =
            `<i class="bi bi-person-circle me-2"></i>${clientName}`;
        document.getElementById('clientModalBody').innerHTML =
            '<div class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm"></div> Loading…</div>';

        const modal = new bootstrap.Modal(document.getElementById('clientModal'));
        modal.show();

        const res = await fetch(`/tenxcrm/xero/analytics/clients/${clientId}`);
        const data = await res.json();

        const serviceBars = data.service_breakdown.map(s =>
            `<div class="mb-2">
                <div class="d-flex justify-content-between" style="font-size:.8rem;">
                    <span style="font-weight:600;">${s.normalized_service.replace(/_/g,' ').toUpperCase()}</span>
                    <span class="text-success fw-bold">AED ${parseFloat(s.revenue).toLocaleString()}</span>
                </div>
                <div style="background:#f0f2f7;height:5px;border-radius:3px;overflow:hidden;">
                    <div style="background:#4c6ef5;height:100%;width:${(s.revenue / data.service_breakdown[0].revenue * 100).toFixed(0)}%;border-radius:3px;"></div>
                </div>
             </div>`
        ).join('');

        document.getElementById('clientModalBody').innerHTML = `
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="xa-kpi accent-green" style="margin:0;">
                        <div class="xa-kpi-label">Total Paid</div>
                        <div class="xa-kpi-value" style="font-size:1.3rem;">AED ${parseFloat(data.total_paid).toLocaleString()}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="xa-kpi accent-orange" style="margin:0;">
                        <div class="xa-kpi-label">Outstanding</div>
                        <div class="xa-kpi-value" style="font-size:1.3rem;">AED ${parseFloat(data.outstanding).toLocaleString()}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="xa-kpi accent-purple" style="margin:0;">
                        <div class="xa-kpi-label">Next Month Forecast</div>
                        <div class="xa-kpi-value" style="font-size:1.3rem;">AED ${parseFloat(data.next_month_prediction).toLocaleString()}</div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-7">
                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--xa-muted);margin-bottom:.5rem;">Monthly Revenue Trend</div>
                    <canvas id="clientTrendCanvas" height="120"></canvas>
                </div>
                <div class="col-md-5">
                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--xa-muted);margin-bottom:.5rem;">Service Breakdown</div>
                    ${serviceBars}
                    ${data.peak_month ? `<div class="mt-3 p-2 rounded-3" style="background:#f8f9ff;font-size:.78rem;">
                        🏆 Peak month: <strong>${data.peak_month.year_month}</strong> — AED ${parseFloat(data.peak_month.revenue).toLocaleString()}
                    </div>` : ''}
                </div>
            </div>
        `;

        // Build mini trend chart
        const cCtx = document.getElementById('clientTrendCanvas').getContext('2d');
        if (clientDetailChart) clientDetailChart.destroy();
        clientDetailChart = new Chart(cCtx, {
            type: 'line',
            data: {
                labels:   data.monthly_trend.map(d => d.year_month),
                datasets: [{
                    data:            data.monthly_trend.map(d => parseFloat(d.revenue)),
                    borderColor:     '#4c6ef5',
                    backgroundColor: '#4c6ef520',
                    fill:            true,
                    tension:         .4,
                    pointRadius:     3,
                    borderWidth:     2,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales:  {
                    y: { ticks: { callback: v => 'AED ' + v.toLocaleString() } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── Client search autocomplete ─────────────────────────────────
    const searchInput = document.getElementById('clientSearchInput');
    const searchDrop  = document.getElementById('clientSearchDropdown');
    let searchTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        if (q.length < 2) { searchDrop.style.display = 'none'; return; }

        searchTimer = setTimeout(async () => {
            const res  = await fetch(`{{ route('xero.analytics.client-search') }}?q=${encodeURIComponent(q)}`);
            const data = await res.json();

            searchDrop.innerHTML = data.map(c =>
                `<div class="dropdown-item py-2" style="cursor:pointer;font-size:.82rem;"
                      onclick="loadClientDetail(${c.id},'${c.name.replace(/'/g,"\\'")}')">
                    <strong>${c.name}</strong><br>
                    <small class="text-muted">${c.email ?? ''}</small>
                 </div>`
            ).join('') || '<div class="px-3 py-2 text-muted" style="font-size:.8rem;">No results</div>';

            searchDrop.style.display = 'block';
        }, 300);
    });

    document.addEventListener('click', e => {
        if (!searchInput.contains(e.target)) searchDrop.style.display = 'none';
    });

    // ── Sync controls ──────────────────────────────────────────────
    document.getElementById('syncIncrementalBtn')?.addEventListener('click', async function() {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing…';
        const res = await fetch("{{ route('xero.sync.incremental') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        alert(data.message);
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Sync Now';
    });

    document.getElementById('syncFullBtn')?.addEventListener('click', async function() {
        if (!confirm('Run a full sync? This may take several minutes.')) return;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Running…';
        const res = await fetch("{{ route('xero.sync.full') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        const data = await res.json();
        alert(data.message);
        this.disabled = false;
        this.innerHTML = '<i class="bi bi-cloud-download me-1"></i>Full Sync';
    });

    // ── Custom date range toggle ───────────────────────────────────
    document.getElementById('rangeSelect').addEventListener('change', function() {
        document.getElementById('customDateRow').classList.toggle('d-none', this.value !== 'custom');
    });
    </script>
</x-app-layout>