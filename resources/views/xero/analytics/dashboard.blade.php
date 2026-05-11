<x-app-layout>
    <x-slot name="header">Financial Analytics</x-slot>

    <style>
        :root {
            --bg: #f4f6fb;
            --surface: #ffffff;
            --surface2: #f0f2f8;
            --border: #e4e8f0;
            --text: #1a1f36;
            --muted: #7a8299;
            --accent: #3b5bdb;
            --green: #087f5b;
            --red: #c92a2a;
            --orange: #d4820a;
            --radius: 14px;
            --font: 'DM Sans', sans-serif;
            --shadow: 0 1px 4px rgba(0, 0, 0, .06), 0 4px 16px rgba(0, 0, 0, .04);
        }

        .xd {
            background: var(--bg);
            min-height: 100vh;
            padding: 2rem 2rem 4rem;
            font-family: var(--font);
            color: var(--text);
        }

        /* ── Header ── */
        .xd-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .xd-head h1 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -.5px;
            margin: 0;
        }

        .xd-head h1 span {
            color: var(--accent);
        }

        .xd-head p {
            font-size: .78rem;
            color: var(--muted);
            margin: .15rem 0 0;
        }

        .sync-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #d3f9d8;
            color: var(--green);
            border: 1px solid #b2f2bb;
            border-radius: 20px;
            padding: .25rem .75rem;
            font-size: .72rem;
            font-weight: 600;
        }

        .xd-actions {
            display: flex;
            gap: .6rem;
        }

        .xd-btn {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: .78rem;
            font-weight: 600;
            border-radius: 10px;
            padding: .45rem .9rem;
            cursor: pointer;
            transition: all .2s;
            box-shadow: var(--shadow);
        }

        .xd-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ── Modern Filter Bar ── */
        .xd-filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
        }

        .xd-filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
        }

        .xd-filter-group {
            flex: 1;
            min-width: 180px;
        }

        .xd-filter-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            margin-bottom: .4rem;
            display: block;
        }

        .xd-filter-select,
        .xd-filter-input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .45rem .75rem;
            font-size: .8rem;
            color: var(--text);
            background: var(--surface2);
            transition: border .2s;
        }

        .xd-filter-select:focus,
        .xd-filter-input:focus {
            border-color: var(--accent);
            outline: none;
        }

        .xd-filter-submit {
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 8px;
            padding: .5rem 1.2rem;
            font-weight: 700;
            font-size: .8rem;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 91, 219, .2);
            transition: transform .15s;
        }

        .xd-filter-submit:hover {
            transform: translateY(-1px);
        }

        .xd-filter-clear {
            color: var(--muted);
            font-size: .8rem;
            text-decoration: none;
            font-weight: 600;
            padding: .5rem .75rem;
        }

        .xd-filter-clear:hover {
            color: var(--red);
        }

        /* ── Active Filters Badges ── */
        .xd-active-filters {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--border);
        }

        .xd-active-badge {
            background: #eef2ff;
            color: var(--accent);
            border: 1px solid #c5d0fc;
            padding: .25rem .75rem;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }

        /* ── KPI Row ── */
        .xd-kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .xd-kpi {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.4rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            box-shadow: var(--shadow);
        }

        .xd-kpi:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, .09);
        }

        .xd-kpi-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            margin-bottom: .5rem;
        }

        .xd-kpi-value {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -.5px;
            line-height: 1;
        }

        .xd-kpi-sub {
            font-size: .72rem;
            color: var(--muted);
            margin-top: .4rem;
        }

        .xd-kpi-badge {
            display: inline-flex;
            align-items: center;
            gap: .25rem;
            font-size: .7rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 20px;
            margin-top: .5rem;
        }

        .badge-up {
            background: #d3f9d8;
            color: var(--green);
        }

        .badge-down {
            background: #ffe3e3;
            color: var(--red);
        }

        .badge-flat {
            background: #e9ecef;
            color: var(--muted);
        }

        .kpi-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
        }

        /* ── Insight cards ── */
        .xd-insights {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .xd-insight {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.4rem;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            box-shadow: var(--shadow);
        }

        .xd-insight:hover {
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(59, 91, 219, .1);
        }

        .xd-insight-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: .85rem;
        }

        .xd-insight-title {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            margin-bottom: .35rem;
        }

        .xd-insight-main {
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: .25rem;
        }

        .xd-insight-sub {
            font-size: .75rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .xd-insight-arrow {
            position: absolute;
            top: 1.1rem;
            right: 1.1rem;
            color: var(--muted);
            font-size: .75rem;
            transition: transform .2s;
        }

        .xd-insight:hover .xd-insight-arrow {
            transform: translateX(3px);
            color: var(--accent);
        }

        /* ── Sections ── */
        .xd-section {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .xd-section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.4rem;
            cursor: pointer;
            user-select: none;
            transition: background .15s;
        }

        .xd-section-head:hover {
            background: var(--surface2);
        }

        .xd-section-title {
            font-size: .88rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: .6rem;
        }

        .xd-section-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
        }

        .xd-chevron {
            color: var(--muted);
            font-size: .75rem;
            transition: transform .25s;
        }

        .xd-section.open .xd-chevron {
            transform: rotate(180deg);
        }

        .xd-section-body {
            display: none;
            padding: 0 1.4rem 1.4rem;
        }

        .xd-section.open .xd-section-body {
            display: block;
        }

        /* ── Charts ── */
        .xd-chart-box {
            background: var(--surface2);
            border-radius: 10px;
            padding: 1rem;
        }

        .xd-chart-label {
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            margin-bottom: .75rem;
        }

        /* ── Dept rows ── */
        .xd-dept-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: .65rem .85rem;
            border-radius: 9px;
            margin-bottom: .4rem;
            background: var(--surface2);
            transition: background .15s;
        }

        .xd-dept-row:hover {
            background: #eef2ff;
        }

        .xd-dept-name {
            font-size: .82rem;
            font-weight: 700;
        }

        .xd-dept-rev {
            font-size: .82rem;
            font-weight: 800;
        }

        .xd-dept-pct {
            font-size: .7rem;
            color: var(--muted);
            margin-top: .1rem;
        }

        .xd-dept-bar-wrap {
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            margin-top: .4rem;
            overflow: hidden;
        }

        .xd-dept-bar-fill {
            height: 100%;
            border-radius: 2px;
            transition: width .6s ease;
        }

        /* ── Client table ── */
        .xd-client-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .xd-client-table thead th {
            padding: .6rem .85rem;
            text-align: left;
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            background: var(--surface2);
        }

        .xd-client-table tbody td {
            padding: .7rem .85rem;
            border-bottom: 1px solid var(--border);
        }

        .xd-client-table tbody tr:hover {
            background: #f5f7ff;
            cursor: pointer;
        }

        .xd-client-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── Prediction ── */
        .xd-pred {
            background: linear-gradient(135deg, #eef2ff 0%, #f3f0ff 100%);
            border: 1px solid #c5d0fc;
            border-radius: var(--radius);
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .xd-pred-label {
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--accent);
            margin-bottom: .5rem;
        }

        .xd-pred-amount {
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: .3rem;
            color: var(--orange);
        }

        .xd-pred-month {
            font-size: .82rem;
            color: var(--muted);
            margin-bottom: .75rem;
        }

        .xd-pred-methods {
            display: flex;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .xd-pred-method {
            background: rgba(255, 255, 255, .7);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: .5rem .9rem;
        }

        .xd-pred-method-label {
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
        }

        .xd-pred-method-val {
            font-size: .9rem;
            font-weight: 800;
            color: var(--text);
        }

        .xd-confidence {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: #eef2ff;
            border: 1px solid #c5d0fc;
            color: var(--accent);
            border-radius: 20px;
            padding: .2rem .7rem;
            font-size: .72rem;
            font-weight: 600;
            margin-bottom: .75rem;
        }

        /* ── Modal ── */
        .xd-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .xd-modal-overlay.show {
            display: flex;
        }

        .xd-modal {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        }

        .xd-modal-head {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: var(--surface);
            z-index: 1;
        }

        .xd-modal-title {
            font-size: 1rem;
            font-weight: 800;
        }

        .xd-modal-close {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--muted);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .xd-modal-close:hover {
            background: var(--border);
        }

        .xd-modal-body {
            padding: 1.5rem;
        }

        /* ── Yearly summary ── */
        .xd-year-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .85rem 1.1rem;
            flex: 1;
            min-width: 150px;
        }

        .xd-year-label {
            font-size: .67rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            margin-bottom: .3rem;
        }

        .xd-year-value {
            font-size: 1.3rem;
            font-weight: 800;
        }

        @media(max-width:640px) {
            .xd {
                padding: 1rem .85rem 3rem;
            }

            .xd-kpi-value {
                font-size: 1.4rem;
            }

            .xd-pred-amount {
                font-size: 2rem;
            }
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

    @if (auth()->user()->hasRole('ADMIN'))
        <div class="xd">

            {{-- Header --}}
            <div class="xd-head">
                <div>
                    <h1>Financial <span>Analytics</span></h1>
                    <p>
                        Live data from Xero ·
                        @if ($lastSync)
                            Synced {{ $lastSync->completed_at?->diffForHumans() }}
                            <span class="sync-pill ms-1">● In sync</span>
                        @else
                            <span class="sync-pill"
                                style="background:#fff9db;color:var(--orange);border-color:#ffe066;">No
                                sync yet</span>
                        @endif
                    </p>
                </div>
                <div class="xd-actions">
                    <button class="xd-btn" id="syncNowBtn"><i class="bi bi-arrow-clockwise me-1"></i>Sync Now</button>
                    <button class="xd-btn" id="connectButton"><i class="bi bi-link-45deg me-1"></i>Connect Xero</button>
                </div>
            </div>

            {{-- Modern Inline Filter Bar --}}
            <div class="xd-filter-bar">
                <form id="filterForm" method="GET" action="{{ route('xero.analytics.dashboard') }}">
                    <div class="xd-filter-row">

                        {{-- Date Range --}}
                        <div class="xd-filter-group">
                            <label class="xd-filter-label">Date Range</label>
                            <select name="range" id="rangeSelect" class="xd-filter-select">
                                <option value="this_month"
                                    {{ request('range', 'this_month') === 'this_month' ? 'selected' : '' }}>This Month
                                </option>
                                <option value="last_month" {{ request('range') === 'last_month' ? 'selected' : '' }}>
                                    Last
                                    Month</option>
                                <option value="last_3m" {{ request('range') === 'last_3m' ? 'selected' : '' }}>Last 3
                                    Months
                                </option>
                                <option value="last_6m" {{ request('range') === 'last_6m' ? 'selected' : '' }}>Last 6
                                    Months
                                </option>
                                <option value="this_year" {{ request('range') === 'this_year' ? 'selected' : '' }}>This
                                    Year
                                </option>
                                <option value="custom" {{ request('range') === 'custom' ? 'selected' : '' }}>Custom
                                    Dates...
                                </option>
                            </select>
                        </div>

                        {{-- Custom Dates (Hidden by default) --}}
                        <div class="xd-filter-group" id="customDatesFrom"
                            style="{{ request('range') === 'custom' ? '' : 'display:none;' }}">
                            <label class="xd-filter-label">From Date</label>
                            <input type="date" name="from" value="{{ request('from') }}" class="xd-filter-input">
                        </div>
                        <div class="xd-filter-group" id="customDatesTo"
                            style="{{ request('range') === 'custom' ? '' : 'display:none;' }}">
                            <label class="xd-filter-label">To Date</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="xd-filter-input">
                        </div>

                        {{-- Client Filter --}}
                        <div class="xd-filter-group">
                            <label class="xd-filter-label">Client</label>
                            <select name="client_id" class="xd-filter-select">
                                <option value="">All Clients</option>
                                @foreach ($clients as $c)
                                    <option value="{{ $c->id }}"
                                        {{ request('client_id') == $c->id ? 'selected' : '' }}>
                                        {{ Str::limit($c->name, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service Filter --}}
                        <div class="xd-filter-group">
                            <label class="xd-filter-label">Service Type</label>
                            <select name="service" class="xd-filter-select">
                                <option value="">All Services</option>
                                @foreach ($services as $s)
                                    <option value="{{ $s }}"
                                        {{ request('service') == $s ? 'selected' : '' }}>
                                        {{ ucwords(str_replace('_', ' ', $s)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Submit actions --}}
                        <div class="xd-filter-group"
                            style="flex: 0; min-width: auto; display: flex; align-items: center; gap: 0.5rem;">
                            <button type="submit" class="xd-filter-submit">Apply</button>
                            @if (!empty($filters))
                                <a href="{{ route('xero.analytics.dashboard') }}" class="xd-filter-clear">Clear</a>
                            @endif
                        </div>
                    </div>

                    {{-- Active Badges Row --}}
                    @if (!empty($filters))
                        <div class="xd-active-filters">
                            <span
                                style="font-size: .7rem; font-weight: 700; color: var(--muted); margin-right: .25rem; display: flex; align-items: center;">Active
                                Filters:</span>

                            @if (!empty($filters['client_id']))
                                <div class="xd-active-badge">
                                    <i class="bi bi-person"></i>
                                    {{ $clients->firstWhere('id', $filters['client_id'])?->name ?? 'Client' }}
                                </div>
                            @endif

                            @if (!empty($filters['service']))
                                <div class="xd-active-badge">
                                    <i class="bi bi-gear"></i>
                                    {{ ucwords(str_replace('_', ' ', $filters['service'])) }}
                                </div>
                            @endif

                            @if (!empty($filters['min_amount']) || !empty($filters['max_amount']))
                                <div class="xd-active-badge">
                                    <i class="bi bi-cash"></i>
                                    Amount: {{ $filters['min_amount'] ?? '0' }} -
                                    {{ $filters['max_amount'] ?? 'Max' }}
                                </div>
                            @endif
                        </div>
                    @endif
                </form>
            </div>

            {{-- KPI Strip --}}
            <div class="xd-kpi-row">
                <div class="xd-kpi">
                    <div class="kpi-accent-bar" style="background:linear-gradient(180deg,#3b5bdb,#748ffc);"></div>
                    <div class="xd-kpi-label">Total Revenue</div>
                    <div class="xd-kpi-value">AED {{ number_format($summary['total_revenue'], 0) }}</div>
                    <div class="xd-kpi-sub">{{ $from->format('M d') }} – {{ $to->format('M d, Y') }}</div>
                </div>
                <div class="xd-kpi">
                    <div class="kpi-accent-bar" style="background:linear-gradient(180deg,#087f5b,#20c997);"></div>
                    <div class="xd-kpi-label">Collected</div>
                    <div class="xd-kpi-value" style="color:var(--green);">AED
                        {{ number_format($summary['paid_revenue'], 0) }}</div>
                    <div class="xd-kpi-sub">{{ $summary['invoice_count'] }} invoices paid</div>
                </div>
                <div class="xd-kpi">
                    <div class="kpi-accent-bar" style="background:linear-gradient(180deg,#d4820a,#ffa94d);"></div>
                    <div class="xd-kpi-label">Outstanding</div>
                    <div class="xd-kpi-value" style="color:var(--orange);">AED
                        {{ number_format($summary['outstanding_revenue'], 0) }}</div>
                    <div class="xd-kpi-sub">Awaiting payment</div>
                </div>
                <div class="xd-kpi">
                    @php $g = $monthComparison['growth_pct']; @endphp
                    <div class="kpi-accent-bar"
                        style="background:{{ $g >= 0 ? 'linear-gradient(180deg,#087f5b,#20c997)' : 'linear-gradient(180deg,#c92a2a,#f03e3e)' }};">
                    </div>
                    <div class="xd-kpi-label">vs Last Month</div>
                    <div class="xd-kpi-value" style="color:{{ $g >= 0 ? 'var(--green)' : 'var(--red)' }};">
                        {{ $g > 0 ? '+' : '' }}{{ $g }}%</div>
                    <div class="xd-kpi-sub">AED {{ number_format($monthComparison['current_month'], 0) }} this month
                    </div>
                    @if ($g > 0)
                        <span class="xd-kpi-badge badge-up">↑ Growing</span>
                    @elseif($g < 0)
                        <span class="xd-kpi-badge badge-down">↓ Declining</span>
                    @else<span class="xd-kpi-badge badge-flat">→ Flat</span>
                    @endif
                </div>
                <div class="xd-kpi">
                    <div class="kpi-accent-bar" style="background:linear-gradient(180deg,#c92a2a,#f03e3e);"></div>
                    <div class="xd-kpi-label">Peak Month Ever</div>
                    <div class="xd-kpi-value">AED {{ number_format($peakMonth['revenue'], 0) }}</div>
                    <div class="xd-kpi-sub">{{ $peakMonth['label'] ?? '—' }}</div>
                </div>
                <div class="xd-kpi">
                    <div class="kpi-accent-bar" style="background:linear-gradient(180deg,#7048e8,#9775fa);"></div>
                    <div class="xd-kpi-label">Avg Invoice</div>
                    <div class="xd-kpi-value">AED {{ number_format($summary['avg_invoice_value'], 0) }}</div>
                    <div class="xd-kpi-sub">This period</div>
                </div>
            </div>

            {{-- Executive Insights --}}
            @php
                $topDept = $departmentRev->first();
                $bottomDept = $departmentRev->last();
                $topClient = $clientPerformance->first();
            @endphp
            <div class="xd-insights">
                @if ($topDept)
                    <div class="xd-insight" onclick="openSection('sec-departments')">
                        <div class="xd-insight-icon" style="background:#d3f9d8;color:var(--green);">🏆</div>
                        <div class="xd-insight-title">Top Department</div>
                        <div class="xd-insight-main" style="color:var(--green);">{{ $topDept['department_name'] }}
                        </div>
                        <div class="xd-insight-sub">AED {{ number_format($topDept['revenue'], 0) }} ·
                            {{ $topDept['contribution_pct'] }}% of revenue</div>
                        <i class="bi bi-arrow-right xd-insight-arrow"></i>
                    </div>
                @endif

                @if ($bottomDept && $bottomDept['department_id'] !== $topDept['department_id'])
                    <div class="xd-insight" onclick="openSection('sec-departments')">
                        <div class="xd-insight-icon" style="background:#ffe3e3;color:var(--red);">📉</div>
                        <div class="xd-insight-title">Lowest Department</div>
                        <div class="xd-insight-main" style="color:var(--red);">{{ $bottomDept['department_name'] }}
                        </div>
                        <div class="xd-insight-sub">AED {{ number_format($bottomDept['revenue'], 0) }} ·
                            {{ $bottomDept['contribution_pct'] }}% of revenue</div>
                        <i class="bi bi-arrow-right xd-insight-arrow"></i>
                    </div>
                @endif

                @if ($topClient)
                    <div class="xd-insight" onclick="openSection('sec-clients')">
                        <div class="xd-insight-icon" style="background:#eef2ff;color:var(--accent);">⭐</div>
                        <div class="xd-insight-title">Top Client</div>
                        <div class="xd-insight-main" style="color:var(--accent);">
                            {{ Str::limit($topClient['client_name'], 28) }}</div>
                        <div class="xd-insight-sub">AED {{ number_format($topClient['revenue'], 0) }} ·
                            {{ $topClient['invoice_count'] }} invoices</div>
                        <i class="bi bi-arrow-right xd-insight-arrow"></i>
                    </div>
                @endif

                <div class="xd-insight" onclick="openSection('sec-prediction')">
                    <div class="xd-insight-icon" style="background:#fff9db;color:var(--orange);">🔮</div>
                    <div class="xd-insight-title">Next Month Forecast</div>
                    <div class="xd-insight-main" style="color:var(--orange);">AED
                        {{ number_format($prediction['predicted_revenue'], 0) }}</div>
                    <div class="xd-insight-sub">{{ $prediction['for_month'] }} · {{ $prediction['confidence'] }}%
                        confidence</div>
                    <i class="bi bi-arrow-right xd-insight-arrow"></i>
                </div>
            </div>

            {{-- Revenue Trend --}}
            <div class="xd-section open" id="sec-trend">
                <div class="xd-section-head" onclick="toggleSection('sec-trend')">
                    <div class="xd-section-title">
                        <div class="xd-section-icon" style="background:#eef2ff;color:var(--accent);">📈</div>
                        12-Month Revenue Trend
                    </div>
                    <i class="bi bi-chevron-down xd-chevron"></i>
                </div>
                <div class="xd-section-body">
                    <div class="xd-chart-box mb-3">
                        <div class="xd-chart-label">Monthly Revenue — Last 12 Months</div>
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                    @php
                        $thisYear = $trend12
                            ->filter(fn($m) => str_starts_with($m['year_month'], now()->year))
                            ->sum('revenue');
                        $lastYear = $trend12
                            ->filter(fn($m) => str_starts_with($m['year_month'], now()->subYear()->year))
                            ->sum('revenue');
                    @endphp
                    <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                        <div class="xd-year-box">
                            <div class="xd-year-label">{{ now()->year }} YTD</div>
                            <div class="xd-year-value">AED {{ number_format($thisYear, 0) }}</div>
                        </div>
                        <div class="xd-year-box">
                            <div class="xd-year-label">{{ now()->subYear()->year }} Total</div>
                            <div class="xd-year-value">AED {{ number_format($lastYear, 0) }}</div>
                        </div>
                        <div class="xd-year-box">
                            <div class="xd-year-label">Best Month (12mo)</div>
                            <div class="xd-year-value">AED {{ number_format($trend12->max('revenue'), 0) }}</div>
                            <div style="font-size:.72rem;color:var(--muted);">
                                {{ $trend12->sortByDesc('revenue')->first()['label'] ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Departments --}}
            @if ($departmentRev->count() > 0)
                <div class="xd-section" id="sec-departments">
                    <div class="xd-section-head" onclick="toggleSection('sec-departments')">
                        <div class="xd-section-title">
                            <div class="xd-section-icon" style="background:#d3f9d8;color:var(--green);">🏢</div>
                            Department Performance
                            <span
                                style="font-size:.68rem;background:#eef2ff;color:var(--accent);padding:.15rem .5rem;border-radius:20px;font-weight:600;">{{ $departmentRev->count() }}
                                depts</span>
                        </div>
                        <i class="bi bi-chevron-down xd-chevron"></i>
                    </div>
                    <div class="xd-section-body">
                        @php $maxRev = $departmentRev->max('revenue') ?: 1; @endphp
                        @foreach ($departmentRev as $dept)
                            <div class="xd-dept-row">
                                <div style="flex:1;">
                                    <div class="xd-dept-name" style="color:{{ $dept['color'] }};">
                                        {{ $dept['department_name'] }}</div>
                                    <div class="xd-dept-bar-wrap">
                                        <div class="xd-dept-bar-fill"
                                            style="width:{{ ($dept['revenue'] / $maxRev) * 100 }}%;background:{{ $dept['color'] }};">
                                        </div>
                                    </div>
                                </div>
                                <div style="text-align:right;margin-left:1rem;">
                                    <div class="xd-dept-rev">AED {{ number_format($dept['revenue'], 0) }}</div>
                                    <div class="xd-dept-pct">
                                        <span style="color:var(--orange);font-weight:600;">Out: AED
                                            {{ number_format($dept['outstanding_revenue'], 0) }}</span>
                                        <span style="color:var(--border);">|</span>
                                        {{ $dept['contribution_pct'] }}% · {{ $dept['invoice_count'] }} inv
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="xd-chart-box mt-3">
                            <div class="xd-chart-label">Department Monthly Trend</div>
                            <canvas id="deptTrendChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Service Breakdown --}}
            <div class="xd-section open" id="sec-services">
                <div class="xd-section-head" onclick="toggleSection('sec-services')">
                    <div class="xd-section-title">
                        <div class="xd-section-icon" style="background:#eef2ff;color:var(--accent);">⚙️</div>
                        Service Breakdown
                    </div>
                    <i class="bi bi-chevron-down xd-chevron"></i>
                </div>
                <div class="xd-section-body">
                    @php $maxSrv = $serviceBreakdown->max('revenue') ?: 1; @endphp
                    @forelse ($serviceBreakdown as $srv)
                        <div class="xd-dept-row">
                            <div style="flex:1;">
                                {{-- Now safely using array syntax since we map() cast it in AnalyticsService --}}
                                <div class="xd-dept-name">
                                    {{ ucwords(str_replace('_', ' ', $srv['normalized_service'] ?: 'Uncategorized')) }}
                                </div>
                                <div class="xd-dept-bar-wrap">
                                    <div class="xd-dept-bar-fill"
                                        style="width:{{ ($srv['revenue'] / $maxSrv) * 100 }}%;background:var(--accent);">
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right;margin-left:1rem;">
                                <div class="xd-dept-rev">AED {{ number_format($srv['revenue'], 0) }}</div>
                                <div class="xd-dept-pct">{{ $srv['invoice_count'] }} invoices</div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:1rem;text-align:center;color:var(--muted);">No services logged for this
                            filter.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Clients --}}
            @if (empty(request('client_id')))
                <div class="xd-section" id="sec-clients">
                    <div class="xd-section-head" onclick="toggleSection('sec-clients')">
                        <div class="xd-section-title">
                            <div class="xd-section-icon" style="background:#eef2ff;color:var(--accent);">👥</div>
                            All Clients Overview
                            <span
                                style="font-size:.68rem;background:#eef2ff;color:var(--accent);padding:.15rem .5rem;border-radius:20px;font-weight:600;">{{ count($clientPerformance) }}
                                Total</span>
                        </div>
                        <i class="bi bi-chevron-down xd-chevron"></i>
                    </div>
                    <div class="xd-section-body">
                        <div
                            style="max-height: 500px; overflow-y: auto; border: 1px solid var(--border); border-radius: 8px;">
                            <table class="xd-client-table" style="margin: 0;">
                                <thead style="position: sticky; top: 0; z-index: 10;">
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th style="text-align:right;">Total Rev</th>
                                        <th style="text-align:right;">Outstanding</th>
                                        <th style="text-align:center;">Inv.</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($clientPerformance as $i => $client)
                                        <tr
                                            onclick="loadClientDetail({{ $client['client_id'] }}, '{{ addslashes($client['client_name']) }}')">
                                            <td style="color:var(--muted);font-weight:700;">{{ $i + 1 }}</td>
                                            <td>
                                                <div style="font-weight:700;">{{ $client['client_name'] }}</div>
                                                <div style="font-size:.72rem;color:var(--muted);">
                                                    {{ $client['email'] ?? 'No email' }}</div>
                                            </td>
                                            <td style="text-align:right;font-weight:800;color:var(--text);">AED
                                                {{ number_format($client['revenue'] ?? 0, 0) }}</td>
                                            <td style="text-align:right;font-weight:700;color:var(--orange);">AED
                                                {{ number_format($client['outstanding_revenue'] ?? 0, 0) }}</td>
                                            <td style="text-align:center;color:var(--muted);">
                                                {{ $client['invoice_count'] ?? 0 }}</td>
                                            <td style="color:var(--muted);font-size:.75rem;">→</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6"
                                                style="text-align:center;color:var(--muted);padding:2rem;">
                                                No clients found matching filters.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--muted); margin-top: 0.75rem;">
                            <i class="bi bi-info-circle me-1"></i> Click on any client row to view their monthly trend,
                            total lifetime paid, and a breakdown of revenue by service type.
                        </div>
                    </div>
                </div>
            @endif

            {{-- Prediction --}}
            <div class="xd-section" id="sec-prediction">
                <div class="xd-section-head" onclick="toggleSection('sec-prediction')">
                    <div class="xd-section-title">
                        <div class="xd-section-icon" style="background:#fff9db;color:var(--orange);">🔮</div>
                        Revenue Prediction
                    </div>
                    <i class="bi bi-chevron-down xd-chevron"></i>
                </div>
                <div class="xd-section-body">
                    <div class="xd-pred">
                        <div class="xd-pred-label"><i class="bi bi-cpu me-1"></i>AI Forecast</div>
                        <div class="xd-pred-amount">AED {{ number_format($prediction['predicted_revenue'], 0) }}</div>
                        <div class="xd-pred-month">Forecast for {{ $prediction['for_month'] }}</div>
                        <div class="xd-confidence"><i
                                class="bi bi-shield-check me-1"></i>{{ $prediction['confidence'] }}% confidence</div>
                        <div class="xd-pred-methods">
                            <div class="xd-pred-method">
                                <div class="xd-pred-method-label">Weighted MA</div>
                                <div class="xd-pred-method-val">AED {{ number_format($prediction['method_wma'], 0) }}
                                </div>
                            </div>
                            <div class="xd-pred-method">
                                <div class="xd-pred-method-label">Linear Regression</div>
                                <div class="xd-pred-method-val">AED {{ number_format($prediction['method_lr'], 0) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($deptPrediction->count() > 0)
                        @php $maxPred = $deptPrediction->max('predicted') ?: 1; @endphp
                        <div
                            style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:.75rem;">
                            By Department</div>
                        @foreach ($deptPrediction as $dp)
                            <div style="margin-bottom:.75rem;">
                                <div style="display:flex;justify-content:space-between;margin-bottom:.3rem;">
                                    <span
                                        style="font-size:.82rem;font-weight:700;color:{{ $dp['color'] }};">{{ $dp['department_name'] }}</span>
                                    <span style="font-size:.82rem;font-weight:800;">AED
                                        {{ number_format($dp['predicted'], 0) }}</span>
                                </div>
                                <div style="background:var(--border);height:5px;border-radius:3px;overflow:hidden;">
                                    <div
                                        style="background:{{ $dp['color'] }};height:100%;width:{{ ($dp['predicted'] / $maxPred) * 100 }}%;border-radius:3px;">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    @else
        @include('vendor.not-allowed')
    @endif

    {{-- Client Modal --}}
    <div class="xd-modal-overlay" id="clientModalOverlay" onclick="closeClientModal(event)">
        <div class="xd-modal">
            <div class="xd-modal-head">
                <div class="xd-modal-title" id="clientModalTitle">Client Analytics</div>
                <button class="xd-modal-close"
                    onclick="document.getElementById('clientModalOverlay').classList.remove('show')">✕</button>
            </div>
            <div class="xd-modal-body" id="clientModalBody">
                <div style="text-align:center;padding:3rem;color:var(--muted);">Loading…</div>
            </div>
        </div>
    </div>

    <script>
        // Custom Date Filter Toggle
        document.getElementById('rangeSelect').addEventListener('change', function() {
            const isCustom = this.value === 'custom';
            document.getElementById('customDatesFrom').style.display = isCustom ? 'block' : 'none';
            document.getElementById('customDatesTo').style.display = isCustom ? 'block' : 'none';
        });

        Chart.defaults.color = '#7a8299';
        Chart.defaults.font.family = 'DM Sans, sans-serif';

        const trendData = @json($trend12);
        const deptData = @json($departmentRev);

        // Revenue trend
        (function() {
            const ctx = document.getElementById('trendChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: trendData.map(d => d.label),
                    datasets: [{
                            label: 'Total Revenue',
                            data: trendData.map(d => d.revenue),
                            backgroundColor: 'rgba(59,91,219,.15)',
                            borderColor: 'rgba(59,91,219,.6)',
                            borderWidth: 1,
                            borderRadius: 5,
                            order: 2
                        },
                        {
                            type: 'line',
                            label: 'Paid',
                            data: trendData.map(d => d.paid),
                            borderColor: '#087f5b',
                            backgroundColor: 'rgba(8,127,91,.08)',
                            fill: true,
                            tension: .4,
                            pointRadius: 3,
                            pointBackgroundColor: '#087f5b',
                            borderWidth: 2,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#7a8299',
                                boxWidth: 10
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#7a8299'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0,0,0,.04)'
                            },
                            ticks: {
                                callback: v => 'AED ' + (v / 1000).toFixed(0) + 'k',
                                color: '#7a8299'
                            }
                        }
                    }
                }
            });
        })();

        // Dept trend (Requires existing canvas, which is hidden if 0 depts)
        const deptCanvas = document.getElementById('deptTrendChart');
        if (deptCanvas) {
            (async function() {
                // IMPORTANT: Automatically inherit all current URL parameters so the chart matches the filters!
                const searchParams = new URLSearchParams(window.location.search);
                searchParams.set('months', '12'); // override months parameter specifically for chart

                const res = await fetch("{{ route('xero.analytics.dept-trend') }}?" + searchParams.toString());
                const json = await res.json();
                const trend = json.trend;
                const allMonths = [...new Set(Object.values(trend).flatMap(rows => rows.map(r => r.ym || r
                    .year_month)))].sort();

                const datasets = Object.entries(trend).map(([name, rows]) => {
                    const color = rows[0]?.color ?? '#aaa';
                    const map = Object.fromEntries(rows.map(r => [r.ym || r.year_month, parseFloat(r
                        .revenue)]));
                    return {
                        label: name,
                        data: allMonths.map(m => map[m] ?? 0),
                        backgroundColor: color + '55',
                        borderColor: color,
                        borderWidth: 1,
                        borderRadius: 4
                    };
                });

                new Chart(deptCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: allMonths,
                        datasets
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#7a8299'
                                }
                            },
                            y: {
                                stacked: true,
                                grid: {
                                    color: 'rgba(0,0,0,.04)'
                                },
                                ticks: {
                                    callback: v => 'AED ' + (v / 1000).toFixed(0) + 'k',
                                    color: '#7a8299'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: '#7a8299',
                                    boxWidth: 10
                                }
                            }
                        }
                    }
                });
            })();
        }

        // Accordion
        function toggleSection(id) {
            document.getElementById(id).classList.toggle('open');
        }

        function openSection(id) {
            const s = document.getElementById(id);
            s.classList.add('open');
            s.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        // Client detail
        let clientChart;
        async function loadClientDetail(clientId, clientName) {
            document.getElementById('clientModalTitle').textContent = clientName;
            document.getElementById('clientModalBody').innerHTML =
                '<div style="text-align:center;padding:3rem;color:var(--muted);">Loading…</div>';
            document.getElementById('clientModalOverlay').classList.add('show');

            const url = "{{ route('xero.analytics.client', ':id') }}".replace(':id', clientId);
            const res = await fetch(url);
            const data = await res.json();

            const bars = (data.service_breakdown || []).map(s => {
                const max = data.service_breakdown[0]?.revenue || 1;
                return `<div style="margin-bottom:.6rem;">
                <div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:.25rem;">
                    <span style="font-weight:700;">${(s.normalized_service||'').replace(/_/g,' ').toUpperCase()}</span>
                    <span style="color:var(--green);font-weight:800;">AED ${parseFloat(s.revenue).toLocaleString()}</span>
                </div>
                <div style="background:var(--border);height:4px;border-radius:2px;overflow:hidden;">
                    <div style="background:var(--accent);height:100%;width:${(s.revenue/max*100).toFixed(0)}%;"></div>
                </div></div>`;
            }).join('');

            document.getElementById('clientModalBody').innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.75rem;margin-bottom:1.5rem;">
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:.85rem;">
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:.3rem;">Total Paid</div>
                    <div style="font-size:1.2rem;font-weight:800;color:var(--green);">AED ${parseFloat(data.total_paid).toLocaleString()}</div>
                </div>
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:.85rem;">
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:.3rem;">Outstanding</div>
                    <div style="font-size:1.2rem;font-weight:800;color:var(--orange);">AED ${parseFloat(data.outstanding).toLocaleString()}</div>
                </div>
                <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:.85rem;">
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:.3rem;">Next Month</div>
                    <div style="font-size:1.2rem;font-weight:800;color:var(--orange);">AED ${parseFloat(data.next_month_prediction).toLocaleString()}</div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                <div>
                    <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:.75rem;">Monthly Trend</div>
                    <canvas id="clientTrendCanvas" height="140"></canvas>
                </div>
                <div>
                    <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--muted);margin-bottom:.75rem;">Services</div>
                    ${bars}
                    ${data.peak_month?`<div style="background:#eef2ff;border:1px solid #c5d0fc;border-radius:8px;padding:.6rem .85rem;font-size:.75rem;margin-top:.75rem;">
                                                    🏆 Peak: <strong>${data.peak_month.ym||data.peak_month.year_month}</strong> — AED ${parseFloat(data.peak_month.revenue).toLocaleString()}
                                                </div>`:''}
                </div>
            </div>`;

            const cCtx = document.getElementById('clientTrendCanvas').getContext('2d');
            if (clientChart) clientChart.destroy();
            clientChart = new Chart(cCtx, {
                type: 'line',
                data: {
                    labels: (data.monthly_trend || []).map(d => d.ym || d.year_month),
                    datasets: [{
                        data: (data.monthly_trend || []).map(d => parseFloat(d.revenue)),
                        borderColor: '#3b5bdb',
                        backgroundColor: 'rgba(59,91,219,.08)',
                        fill: true,
                        tension: .4,
                        pointRadius: 3,
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: v => 'AED ' + (v / 1000).toFixed(0) + 'k',
                                color: '#7a8299'
                            },
                            grid: {
                                color: 'rgba(0,0,0,.04)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#7a8299'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function closeClientModal(e) {
            if (e.target === document.getElementById('clientModalOverlay'))
                document.getElementById('clientModalOverlay').classList.remove('show');
        }

        document.getElementById('syncNowBtn')?.addEventListener('click', async function() {
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing…';
            try {
                const res = await fetch("{{ route('xero.sync.incremental') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                alert((await res.json()).message || 'Sync queued.');
            } catch (e) {
                alert('Sync failed.');
            }
            this.disabled = false;
            this.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Sync Now';
        });
        document.getElementById('connectButton')?.addEventListener('click', async function() {
            window.location.href = "{{ route('xero.connect') }}"
        });
    </script>
</x-app-layout>
