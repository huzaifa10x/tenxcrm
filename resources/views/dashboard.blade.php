<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>

    {{-- ═══════════════════════════════════════════════════════════
         DASHBOARD STYLES
    ═══════════════════════════════════════════════════════════ --}}
    <style>
        /* ── Design tokens ───────────────────────────────────────── */
        :root {
            --ds-bg: #f0f2f7;
            --ds-surface: #ffffff;
            --ds-border: #e2e6ef;
            --ds-text: #1a1f36;
            --ds-muted: #6b7280;
            --ds-accent: #3b5bdb;
            --ds-accent-soft: #eef2ff;
            --ds-success: #087f5b;
            --ds-warning: #e67700;
            --ds-danger: #c92a2a;
            --ds-info: #1971c2;
            --ds-radius: 12px;
            --ds-radius-lg: 18px;
            --ds-shadow: 0 1px 4px rgba(0, 0, 0, .07), 0 4px 16px rgba(0, 0, 0, .05);
            --ds-shadow-lg: 0 2px 8px rgba(0, 0, 0, .08), 0 8px 32px rgba(0, 0, 0, .08);
        }

        /* ── Page scaffold ───────────────────────────────────────── */
        .ds-page {
            background: var(--ds-bg);
            min-height: 100vh;
            padding: 1.5rem 1.5rem 3rem;
        }

        /* ── Greeting bar ────────────────────────────────────────── */
        .ds-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.75rem;
        }

        .ds-greeting h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ds-text);
            margin: 0;
        }

        .ds-greeting p {
            font-size: .85rem;
            color: var(--ds-muted);
            margin: 0;
        }

        /* ── Punch card ──────────────────────────────────────────── */
        .ds-punch-card {
            background: var(--ds-surface);
            border-radius: var(--ds-radius);
            border: 1px solid var(--ds-border);
            box-shadow: var(--ds-shadow);
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1rem;
        }

        .ds-punch-card .punch-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--ds-muted);
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .ds-punch-card .btn-punch-in {
            background: #d3f9d8;
            color: var(--ds-success);
            border: 1.5px solid #b2f2bb;
            font-weight: 600;
            font-size: .82rem;
            border-radius: 8px;
            padding: .4rem .9rem;
            transition: all .18s;
        }

        .ds-punch-card .btn-punch-in:hover {
            background: var(--ds-success);
            color: #fff;
        }

        .ds-punch-card .btn-punch-out {
            background: #ffe3e3;
            color: var(--ds-danger);
            border: 1.5px solid #ffc9c9;
            font-weight: 600;
            font-size: .82rem;
            border-radius: 8px;
            padding: .4rem .9rem;
            transition: all .18s;
        }

        .ds-punch-card .btn-punch-out:hover {
            background: var(--ds-danger);
            color: #fff;
        }

        .ds-punch-sep {
            width: 1px;
            height: 28px;
            background: var(--ds-border);
        }

        /* ── Accordion shell ─────────────────────────────────────── */
        .ds-accordion {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .ds-section {
            background: var(--ds-surface);
            border: 1px solid var(--ds-border);
            border-radius: var(--ds-radius-lg);
            box-shadow: var(--ds-shadow);
            overflow: hidden;
        }

        .ds-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            cursor: pointer;
            user-select: none;
            transition: background .15s;
        }

        .ds-section-header:hover {
            background: #fafbfc;
        }

        .ds-section-header .ds-section-title {
            display: flex;
            align-items: center;
            gap: .65rem;
            font-size: .95rem;
            font-weight: 700;
            color: var(--ds-text);
            margin: 0;
        }

        .ds-section-header .ds-section-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .ds-section-meta {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .ds-section-badge {
            font-size: .72rem;
            font-weight: 600;
            padding: .25rem .65rem;
            border-radius: 20px;
            letter-spacing: .3px;
        }

        .ds-chevron {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            background: var(--ds-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ds-muted);
            font-size: .8rem;
            transition: transform .25s;
        }

        .ds-section.open .ds-chevron {
            transform: rotate(180deg);
        }

        .ds-section-body {
            border-top: 1px solid var(--ds-border);
            padding: 1.25rem;
        }

        .ds-section-body.hidden {
            display: none;
        }

        /* ── Stat cards grid ─────────────────────────────────────── */
        .ds-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: .85rem;
        }

        .ds-stat {
            border-radius: var(--ds-radius);
            padding: 1.1rem 1.15rem;
            border: 1px solid var(--ds-border);
            position: relative;
            overflow: hidden;
        }

        .ds-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .ds-stat-accent-purple::before {
            background: linear-gradient(180deg, #7048e8, #4c6ef5);
        }

        .ds-stat-accent-green::before {
            background: linear-gradient(180deg, #099268, #20c997);
        }

        .ds-stat-accent-teal::before {
            background: linear-gradient(180deg, #1c7ed6, #339af0);
        }

        .ds-stat-accent-pink::before {
            background: linear-gradient(180deg, #d6336c, #f783ac);
        }

        .ds-stat-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--ds-muted);
            margin-bottom: .35rem;
        }

        .ds-stat-sub {
            font-size: .75rem;
            color: var(--ds-muted);
            margin-top: .2rem;
        }

        .ds-stat-value {
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--ds-text);
            line-height: 1.1;
        }

        .ds-stat-currency {
            font-size: .85rem;
            font-weight: 500;
            color: var(--ds-muted);
            margin-right: .15rem;
        }

        /* ── Inline summary strip ────────────────────────────────── */
        .ds-strip {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            padding: .85rem 1.25rem;
            background: var(--ds-bg);
            border-bottom: 1px solid var(--ds-border);
        }

        .ds-strip-item {
            display: flex;
            flex-direction: column;
        }

        .ds-strip-label {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--ds-muted);
        }

        .ds-strip-value {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--ds-text);
        }

        /* ── Filter bar ──────────────────────────────────────────── */
        .ds-filter-bar {
            background: var(--ds-bg);
            border-radius: var(--ds-radius);
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .ds-filter-bar .form-label {
            font-size: .78rem;
            font-weight: 600;
            margin-bottom: .3rem;
            color: var(--ds-text);
        }

        .ds-filter-bar .form-control,
        .ds-filter-bar .form-select {
            font-size: .82rem;
            border-radius: 8px;
            border-color: var(--ds-border);
        }

        /* ── Table ───────────────────────────────────────────────── */
        .ds-table {
            font-size: .82rem;
        }

        .ds-table thead th {
            background: #f8f9fc;
            font-weight: 700;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--ds-muted);
            border-bottom: 1px solid var(--ds-border);
            padding: .65rem .85rem;
            white-space: nowrap;
        }

        .ds-table tbody td {
            padding: .7rem .85rem;
            vertical-align: middle;
            border-color: #f0f2f7;
        }

        .ds-table tbody tr:hover {
            background: #f8f9ff;
        }

        .ds-table-wrap {
            border-radius: var(--ds-radius);
            border: 1px solid var(--ds-border);
            overflow: hidden;
        }

        .ds-table-scroll {
            max-height: 520px;
            overflow-y: auto;
        }

        /* ── Task bars ───────────────────────────────────────────── */
        .ds-task-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: .75rem;
        }

        .ds-task-bar-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .ds-task-bar-fill {
            width: 100%;
            border-radius: 10px 10px 0 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            padding: 1rem .5rem .75rem;
            min-height: 80px;
            transition: min-height .3s;
        }

        .ds-task-bar-fill.c-warning {
            background: #fff9db;
            border: 1.5px solid #ffe066;
        }

        .ds-task-bar-fill.c-lilac {
            background: #f3f0ff;
            border: 1.5px solid #d0bfff;
        }

        .ds-task-bar-fill.c-primary {
            background: #eef2ff;
            border: 1.5px solid #bac8ff;
        }

        .ds-task-bar-fill.c-success {
            background: #ebfbee;
            border: 1.5px solid #b2f2bb;
        }

        .ds-task-chip {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 800;
            margin-bottom: .4rem;
        }

        .c-warning .ds-task-chip {
            background: #ffd43b;
            color: #664d03;
        }

        .c-lilac .ds-task-chip {
            background: #9775fa;
            color: #fff;
        }

        .c-primary .ds-task-chip {
            background: #4c6ef5;
            color: #fff;
        }

        .c-success .ds-task-chip {
            background: #40c057;
            color: #fff;
        }

        .ds-task-bar-label {
            font-size: .73rem;
            font-weight: 600;
            color: var(--ds-muted);
            text-align: center;
            margin-top: .4rem;
        }

        /* ── Activity cards ──────────────────────────────────────── */
        .ds-activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: .85rem;
        }

        .ds-activity-card {
            border-radius: var(--ds-radius);
            padding: 1rem 1.1rem;
            border: 1px solid var(--ds-border);
        }

        .ds-activity-card .act-icon {
            font-size: 1.4rem;
            margin-bottom: .5rem;
        }

        .ds-activity-card .act-label {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: var(--ds-muted);
        }

        .ds-activity-card .act-date {
            font-size: .7rem;
            color: var(--ds-muted);
        }

        .ds-activity-card .act-count {
            font-size: 2rem;
            font-weight: 800;
            color: var(--ds-text);
            line-height: 1.1;
        }

        /* ── Notification list ───────────────────────────────────── */
        .ds-notif-item {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: .85rem 0;
            border-bottom: 1px solid var(--ds-border);
        }

        .ds-notif-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .ds-notif-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--ds-accent-soft);
            color: var(--ds-accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .ds-notif-title {
            font-size: .85rem;
            font-weight: 600;
            color: var(--ds-text);
            margin-bottom: .15rem;
        }

        .ds-notif-msg {
            font-size: .78rem;
            color: var(--ds-muted);
            margin-bottom: .15rem;
        }

        .ds-notif-time {
            font-size: .7rem;
            color: #adb5bd;
        }

        /* ── Office timing table ─────────────────────────────────── */
        .ds-timing-table td:first-child {
            font-size: .8rem;
            font-weight: 600;
            color: var(--ds-muted);
            width: 55%;
        }

        .ds-timing-table td:last-child {
            font-size: .82rem;
            font-weight: 700;
            color: var(--ds-text);
        }

        .ds-timing-table tr {
            border-color: var(--ds-border);
        }

        /* ── Website project cards ───────────────────────────────── */
        .ds-wp-card {
            border-radius: var(--ds-radius);
            border: 1px solid var(--ds-border);
            overflow: hidden;
        }

        .ds-wp-card .wp-header {
            background: var(--ds-text);
            color: #fff;
            padding: .75rem 1rem;
            font-size: .85rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ds-wp-card .wp-body {
            padding: .9rem 1rem;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 767px) {
            .ds-task-bars {
                grid-template-columns: repeat(2, 1fr);
            }

            .ds-page {
                padding: 1rem .85rem 2rem;
            }
        }
    </style>

    {{-- ═══════════════════════════════════════════════════════════
         TOP BAR: GREETING + PUNCH IN/OUT
    ═══════════════════════════════════════════════════════════ --}}
    <div class="ds-page">
        <div class="ds-topbar">
            <div class="ds-greeting">
                {{ $role }} Dashboard
                <p>{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
            </div>

            {{-- Punch In/Out ─────────────────────────────────────── --}}
            <div class="ds-punch-card">
                <span class="punch-label">Attendance</span>
                <div class="ds-punch-sep"></div>
                <button class="btn-punch-in" id="punchInBtn" onclick="getLocationAndPunch('in')">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Punch In
                </button>
                <button class="btn-punch-out" id="punchOutBtn" onclick="getLocationAndPunch('out')">
                    <i class="bi bi-box-arrow-right me-1"></i>Punch Out
                </button>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════
             ACCORDION SECTIONS
        ═══════════════════════════════════════════════════════ --}}
        <div class="ds-accordion">

            {{-- ── SECTION 1: Revenue Overview (Admin / SEO Admin) ── --}}
            @if ($role === 'ADMIN' || $role === 'SEO ADMIN')
                <div class="ds-section open" id="sec-revenue">
                    <div class="ds-section-header" onclick="toggleSection('sec-revenue')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#eef2ff;color:#4c6ef5;"><i
                                    class="bi bi-cash-stack"></i></span>
                            Revenue Overview
                        </h6>
                        <div class="ds-section-meta">
                            <span class="ds-section-badge" style="background:#eef2ff;color:#4c6ef5;">SEO · WFF</span>
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body">
                        <div class="ds-stat-grid">
                            <div class="ds-stat ds-stat-accent-purple">
                                <div class="ds-stat-label">Total SEO Revenue</div>
                                <div class="ds-stat-value">
                                    <span class="ds-stat-currency">AED</span>{{ number_format($totalSeoRevenue, 2) }}
                                </div>
                                <div class="ds-stat-sub">All active SEO campaigns · Monthly recurring</div>
                            </div>
                            <div class="ds-stat ds-stat-accent-green">
                                <div class="ds-stat-label">Current SEO Revenue</div>
                                <div class="ds-stat-value">
                                    <span class="ds-stat-currency">AED</span>{{ number_format($currentSeoRevenue, 2) }}
                                </div>
                                <div class="ds-stat-sub">{{ $currentSeoProjectsCount }} active projects</div>
                            </div>
                            <div class="ds-stat ds-stat-accent-teal">
                                <div class="ds-stat-label">Monthly SEO Revenue</div>
                                <div class="ds-stat-value">
                                    <span class="ds-stat-currency">AED</span>{{ number_format($monthlySeoRevenue, 2) }}
                                </div>
                                <div class="ds-stat-sub">{{ $currentSeoProjectsCount }} active projects</div>
                            </div>
                            <div class="ds-stat ds-stat-accent-pink">
                                <div class="ds-stat-label">WFF Revenue</div>
                                <div class="ds-stat-value">
                                    <span class="ds-stat-currency">AED</span>{{ number_format($wffRevenue, 2) }}
                                </div>
                                <div class="ds-stat-sub">{{ $wffProjectsCount }} waiting for funds</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── SECTION 2: SEO Projects Management ── --}}
                <div class="ds-section open" id="sec-seo-projects">
                    <div class="ds-section-header" onclick="toggleSection('sec-seo-projects')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#ebfbee;color:#2f9e44;"><i
                                    class="bi bi-graph-up-arrow"></i></span>
                            SEO Projects
                        </h6>
                        <div class="ds-section-meta">
                            <span class="ds-section-badge" style="background:#ebfbee;color:#2f9e44;">
                                <span id="totalProjects">{{ $seoProjects->count() }}</span> projects
                            </span>
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body" style="padding:0;">

                        {{-- Filter bar --}}
                        <div style="padding:1rem 1.25rem 0;">
                            <div class="ds-filter-bar">
                                <form id="seoProjectFilterForm">
                                    <div class="row g-2 mb-2">
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-search me-1"></i>Search</label>
                                            <input type="text" class="form-control" id="globalSearch" name="search"
                                                placeholder="Project, client, keywords…">
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-person-badge me-1"></i>Account
                                                Manager</label>
                                            <select class="form-select" id="amFilter" name="account_manager_id">
                                                <option value="">All Account Managers</option>
                                                @foreach ($accountManagers as $am)
                                                    <option value="{{ $am->id }}">{{ $am->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-flag me-1"></i>Status</label>
                                            <select class="form-select" id="statusFilter" name="status">
                                                <option value="">All Statuses</option>
                                                <option value="Current">Current</option>
                                                <option value="WFF">WFF</option>
                                                <option value="Debt Collection">Debt Collection</option>
                                                <option value="Completed">Completed</option>
                                                <option value="Cancelled">Cancelled</option>
                                                <option value="Expired">Expired</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-tag me-1"></i>Type</label>
                                            <select class="form-select" id="projectTypeFilter" name="project_type">
                                                <option value="">All Types</option>
                                                <option value="SEO">SEO</option>
                                                <option value="Google Ads">Google Ads</option>
                                                <option value="Social Media Marketing">Social Media Marketing</option>
                                                <option value="Content Marketing">Content Marketing</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Start
                                                From</label>
                                            <input type="date" class="form-control" id="startDateFrom"
                                                name="start_date_from">
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Start
                                                To</label>
                                            <input type="date" class="form-control" id="startDateTo"
                                                name="start_date_to">
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary btn-sm"><i
                                                class="bi bi-funnel-fill me-1"></i>Apply</button>
                                        <button type="button" class="btn btn-secondary btn-sm" id="resetFilters"><i
                                                class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                                        <button type="button" class="btn btn-success btn-sm" id="exportProjects"><i
                                                class="bi bi-file-earmark-excel me-1"></i>Export</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Summary strip --}}
                        <div class="ds-strip">
                            <div class="ds-strip-item">
                                <span class="ds-strip-label">Total Contract Value</span>
                                <span class="ds-strip-value">
                                    AED <span
                                        id="totalRevenue">{{ number_format($seoProjects->sum('contract_value'), 2) }}</span>
                                </span>
                            </div>
                            <div class="ds-strip-item">
                                <span class="ds-strip-label">Monthly Recurring (MRR)</span>
                                <span class="ds-strip-value" style="color:var(--ds-success);">
                                    AED <span
                                        id="monthlyRevenue">{{ number_format($seoProjects->sum(fn($p) => $p->contract_value / ($p->contract_term ?: 1)), 2) }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div style="padding:0 1.25rem 1.25rem;">
                            <div class="ds-table-wrap">
                                <div class="ds-table-scroll">
                                    <table class="table ds-table mb-0" id="seoProjectsTable">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Client</th>
                                                <th>Status</th>
                                                <th>Type</th>
                                                <th>Assignees</th>
                                                <th>Revenue</th>
                                                <th>Timeline</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="projectsTableBody">
                                            @forelse($seoProjects as $index => $project)
                                                @php
                                                    $statusColors = [
                                                        'Current' => 'success',
                                                        'WFF' => 'warning',
                                                        'Debt Collection' => 'danger',
                                                        'Completed' => 'info',
                                                        'Cancelled' => 'secondary',
                                                        'Expired' => 'dark',
                                                    ];
                                                    $color = $statusColors[$project->status] ?? 'secondary';
                                                @endphp
                                                <tr data-project-id="{{ $project->id }}">
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="fw-semibold" style="font-size:.85rem;">
                                                            {{ $project->title }}</div>
                                                        @if ($project->website_url)
                                                            <small class="text-muted">
                                                                <i class="bi bi-link-45deg"></i>
                                                                <a href="{{ $project->website_url }}" target="_blank"
                                                                    class="text-decoration-none">{{ Str::limit($project->website_url, 28) }}</a>
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div style="font-size:.85rem;">
                                                            {{ $project->companies->name ?? 'N/A' }}</div>
                                                        @if ($project->companies->name ?? false)
                                                            <small
                                                                class="text-muted">{{ $project->companies->details->first()->email ?? '' }}</small>
                                                        @endif
                                                    </td>
                                                    <td><span
                                                            class="badge bg-{{ $color }}">{{ $project->status }}</span>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-primary">{{ $project->project_type }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($project->accountManagers->isNotEmpty())
                                                            @foreach ($project->accountManagers as $am)
                                                                <div class="mb-1" style="font-size:.8rem;"><i
                                                                        class="bi bi-person-circle me-1"></i>{{ $am->name }}
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted" style="font-size:.8rem;">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong class="text-success" style="font-size:.85rem;">AED
                                                            {{ number_format($project->contract_value / ($project->contract_term ?: 1), 2) }}</strong><br>
                                                        <small class="text-muted">Total: AED
                                                            {{ number_format($project->contract_value, 2) }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($project->contract_start_date)
                                                            <span
                                                                style="font-size:.82rem;">{{ \Carbon\Carbon::parse($project->contract_start_date)->format('M d, Y') }}</span><br>
                                                            <small class="text-muted">{{ $project->contract_term }}
                                                                months</small>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a href="{{ route('projects.show', $project->id) }}"
                                                                class="btn btn-sm btn-outline-primary"
                                                                title="View"><i class="bi bi-eye"></i></a>
                                                            <a href="{{ route('projects.edit', $project->id) }}"
                                                                class="btn btn-sm btn-outline-warning"
                                                                title="Edit"><i class="bi bi-pencil"></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-5 text-muted">
                                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>No SEO projects
                                                        found
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── SECTION 3: Task Activity ── --}}
            <div class="ds-section open" id="sec-tasks">
                <div class="ds-section-header" onclick="toggleSection('sec-tasks')">
                    <h6 class="ds-section-title">
                        <span class="ds-section-icon" style="background:#fff9db;color:#e67700;"><i
                                class="bi bi-check2-square"></i></span>
                        Task Activity
                    </h6>
                    <div class="ds-section-meta">
                        <span class="ds-section-badge" style="background:#fff9db;color:#e67700;">Overview</span>
                        <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                    </div>
                </div>
                <div class="ds-section-body">
                    <div class="row g-3">
                        {{-- Bar chart --}}
                        <div class="col-lg-6">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span
                                    style="font-size:.8rem;font-weight:700;color:var(--ds-muted);text-transform:uppercase;letter-spacing:.4px;">Task
                                    Breakdown</span>
                                <select id="taskFilter" class="form-select form-select-sm w-auto"
                                    style="font-size:.78rem;border-radius:8px;">
                                    <option value="today" selected>Today</option>
                                    <option value="weekly">Last Week</option>
                                    <option value="monthly">Last Month</option>
                                    <option value="yearly">Last Year</option>
                                </select>
                            </div>
                            <div class="ds-task-bars">
                                <div class="ds-task-bar-col">
                                    <div id="pendingBar" class="ds-task-bar-fill c-warning"
                                        style="min-height:{{ $taskPercentages['pending'] }}%;">
                                        <div class="ds-task-chip"><span
                                                id="pendingCount">{{ $pendingTasksCountGlobal }}</span></div>
                                    </div>
                                    <div class="ds-task-bar-label">Pending</div>
                                </div>
                                <div class="ds-task-bar-col">
                                    <div id="completedBar" class="ds-task-bar-fill c-lilac"
                                        style="min-height:{{ $taskPercentages['completed'] }}%;">
                                        <div class="ds-task-chip"><span
                                                id="completedCount">{{ $completedTasksCountGlobal }}</span></div>
                                    </div>
                                    <div class="ds-task-bar-label">Completed</div>
                                </div>
                                <div class="ds-task-bar-col">
                                    <div id="createdBar" class="ds-task-bar-fill c-primary"
                                        style="min-height:{{ $taskPercentages['created'] }}%;">
                                        <div class="ds-task-chip"><span
                                                id="createdCount">{{ $createdTasksCountGlobal }}</span></div>
                                    </div>
                                    <div class="ds-task-bar-label">Assigned</div>
                                </div>
                                <div class="ds-task-bar-col">
                                    <div id="collaboratorBar" class="ds-task-bar-fill c-success"
                                        style="min-height:{{ $taskPercentages['collaborator'] }}%;">
                                        <div class="ds-task-chip"><span
                                                id="collaboratorCount">{{ $collaboratorTasksCountGlobal }}</span>
                                        </div>
                                    </div>
                                    <div class="ds-task-bar-label">Collaborator</div>
                                </div>
                            </div>
                        </div>

                        {{-- Closed tasks summary --}}
                        <div class="col-lg-6">
                            <span
                                style="font-size:.8rem;font-weight:700;color:var(--ds-muted);text-transform:uppercase;letter-spacing:.4px;">Closed
                                Tasks</span>
                            <div class="ds-activity-grid mt-3">
                                <div class="ds-activity-card" style="background:#f8fffe;">
                                    <div class="act-icon">📅</div>
                                    <div class="act-label">Yesterday</div>
                                    <div class="act-count">{{ $tasksClosedYesterday }}</div>
                                    <div class="act-date">{{ \Carbon\Carbon::yesterday()->format('d M Y') }}</div>
                                </div>
                                <div class="ds-activity-card" style="background:#f8f9ff;">
                                    <div class="act-icon">📆</div>
                                    <div class="act-label">Last 7 Days</div>
                                    <div class="act-count">{{ $tasksClosedLast7Days }}</div>
                                    <div class="act-date">Since
                                        {{ \Carbon\Carbon::now()->subDays(7)->format('d M Y') }}</div>
                                </div>
                                <div class="ds-activity-card" style="background:#f8fff9;">
                                    <div class="act-icon">🗓️</div>
                                    <div class="act-label">Last 30 Days</div>
                                    <div class="act-count">{{ $tasksClosedLast30Days }}</div>
                                    <div class="act-date">Since
                                        {{ \Carbon\Carbon::now()->subDays(30)->format('d M Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tasks trend chart --}}
                    <div class="mt-3" style="border-top:1px solid var(--ds-border);padding-top:1rem;">
                        <span
                            style="font-size:.8rem;font-weight:700;color:var(--ds-muted);text-transform:uppercase;letter-spacing:.4px;">Tasks
                            Trend</span>
                        <div id="tasksChart" class="mt-2" style="min-height:200px;"></div>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 4: Latest Notifications ── --}}
            <div class="ds-section open" id="sec-notifications">
                <div class="ds-section-header" onclick="toggleSection('sec-notifications')">
                    <h6 class="ds-section-title">
                        <span class="ds-section-icon" style="background:#fff3bf;color:#e67700;"><i
                                class="bi bi-bell"></i></span>
                        Latest Notifications
                    </h6>
                    <div class="ds-section-meta">
                        <span class="ds-section-badge"
                            style="background:#fff3bf;color:#e67700;">{{ count($latestNotifications) }} new</span>
                        <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                    </div>
                </div>
                <div class="ds-section-body">
                    @forelse ($latestNotifications as $notification)
                        <a href="{{ $notification['data']['url'] }}" class="d-block text-decoration-none">
                            <div class="ds-notif-item">
                                <div class="ds-notif-avatar">{{ $notification->notifiable_id }}</div>
                                <div class="flex-grow-1">
                                    <div class="ds-notif-title">{{ $notification['data']['title'] }}</div>
                                    <div class="ds-notif-msg">{{ $notification['data']['message'] }}</div>
                                    <div class="ds-notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <i class="bi bi-chevron-right text-muted" style="font-size:.75rem;"></i>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted text-center py-3 mb-0" style="font-size:.85rem;">No notifications yet.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- ── SECTION 5: Office Timings ── --}}
            <div class="ds-section" id="sec-office">
                <div class="ds-section-header" onclick="toggleSection('sec-office')">
                    <h6 class="ds-section-title">
                        <span class="ds-section-icon" style="background:#f3f0ff;color:#7048e8;"><i
                                class="bi bi-clock-history"></i></span>
                        Office Timings & Attendance Rules
                    </h6>
                    <div class="ds-section-meta">
                        <span class="ds-section-badge" style="background:#f3f0ff;color:#7048e8;">Policy</span>
                        <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                    </div>
                </div>
                <div class="ds-section-body hidden">
                    <div class="ds-table-wrap">
                        <table class="table ds-table ds-timing-table mb-0">
                            <tbody>
                                <tr>
                                    <td>Office Open Time</td>
                                    <td>{{ $office_open_time }}</td>
                                </tr>
                                <tr>
                                    <td>Office Close Time</td>
                                    <td>{{ $office_close_time }}</td>
                                </tr>
                                <tr>
                                    <td>Late Mark After</td>
                                    <td>{{ $late_threshold }} minutes</td>
                                </tr>
                                <tr>
                                    <td>Half Day If Arrived After</td>
                                    <td>{{ $half_day_threshold }}</td>
                                </tr>
                                <tr>
                                    <td>Break Ends At</td>
                                    <td>{{ $break_end_time }}</td>
                                </tr>
                                <tr>
                                    <td>Early Out If Left Before</td>
                                    <td>{{ $early_out_threshold }}</td>
                                </tr>
                                <tr>
                                    <td>Late arrivals per deduction</td>
                                    <td>{{ $late_deduction_threshold }}</td>
                                </tr>
                                <tr>
                                    <td>Half-days per deduction</td>
                                    <td>{{ $half_day_deduction_threshold }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── SECTION 6: Role-specific dashboards ── --}}
            @if ($role === 'AM')
                <div class="ds-section open" id="sec-am">
                    <div class="ds-section-header" onclick="toggleSection('sec-am')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#e6fcf5;color:#087f5b;"><i
                                    class="bi bi-person-workspace"></i></span>
                            Account Manager Dashboard
                        </h6>
                        <div class="ds-section-meta">
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body">
                        <x-dashboards.am-dashboard :totalRenewals="$totalRenewals" :monthlyStats="$monthlyStats" :monthlyData="$monthlyData"
                            :newSellProjects="$newSellProjects" :newSell="$newSell" :typeCounts="$typeCounts" :statusCounts="$statusCounts" :currentPortfolio="$currentPortfolio"
                            :projects="$projects" :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
                    </div>
                </div>
            @elseif ($role === 'CONTENT')
                <div class="ds-section open" id="sec-content">
                    <div class="ds-section-header" onclick="toggleSection('sec-content')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#e6fcf5;color:#087f5b;"><i
                                    class="bi bi-file-text"></i></span>
                            Content Dashboard
                        </h6>
                        <div class="ds-section-meta">
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body">
                        <x-dashboards.content-dashboard :pendingTasksCount="$pendingTasksCount" :completedTasksCount="$completedTasksCount" :pendingWords="$pendingWords"
                            :completedWords="$completedWords" :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
                    </div>
                </div>
            @elseif ($role === 'ADMIN')
                {{-- ── Admin: Website Projects ── --}}
                <div class="ds-section open" id="sec-website">
                    <div class="ds-section-header" onclick="toggleSection('sec-website')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#e7f5ff;color:#1971c2;"><i
                                    class="bi bi-globe2"></i></span>
                            Website Projects
                        </h6>
                        <div class="ds-section-meta">
                            <span class="ds-section-badge"
                                style="background:#e7f5ff;color:#1971c2;">{{ count($WebsiteProjects) }}
                                projects</span>
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body">
                        <div class="row g-3">
                            @foreach ($WebsiteProjects as $project)
                                @php $wp = $project; @endphp
                                <div class="col-lg-4 col-md-6">
                                    <div class="ds-wp-card h-100">
                                        <div class="wp-header">
                                            <span><i
                                                    class="bi bi-globe2 me-1"></i>{{ $project->project->title }}</span>
                                            @if (
                                                !$project->websiteProject &&
                                                    (auth()->user()->hasRole('ADMIN') || auth()->user()->id == $project->accountManagers->pluck('id')->first()))
                                                <a href="{{ route('website-projects.create') }}?project_id={{ $project->id }}"
                                                    class="btn btn-sm btn-light text-success py-0 px-2"
                                                    style="font-size:.75rem;">
                                                    <i class="bi bi-plus-circle"></i> Add
                                                </a>
                                            @endif
                                        </div>
                                        <div class="wp-body">
                                            <div class="d-flex flex-wrap gap-1 mb-2">
                                                <span class="badge bg-secondary"
                                                    style="font-size:.7rem;">{{ $wp->platform }}</span>
                                                <span class="badge bg-warning" style="font-size:.7rem;">Design:
                                                    {{ $wp->design_status }}</span>
                                                <span class="badge bg-info" style="font-size:.7rem;">Dev:
                                                    {{ $wp->development_status }}</span>
                                            </div>
                                            <div
                                                class="d-flex justify-content-between align-items-center small text-muted mb-2">
                                                @if ($wp->target_go_live_date)
                                                    <span class="{{ $wp->isOverdue() ? 'text-danger' : 'text-info' }}"
                                                        style="font-size:.75rem;">
                                                        <i
                                                            class="bi bi-calendar-event me-1"></i>{{ $wp->target_go_live_date->format('M d, Y') }}
                                                    </span>
                                                @endif
                                                @if ($wp->developer)
                                                    <span style="font-size:.75rem;"><i
                                                            class="bi bi-person-circle me-1"></i>{{ $wp->developer->name }}</span>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                @if ($wp->go_live_date)
                                                    <span class="badge bg-success"><i
                                                            class="bi bi-rocket-takeoff me-1"></i>LIVE</span>
                                                @elseif($wp->approved_for_go_live)
                                                    <span class="badge bg-warning"><i
                                                            class="bi bi-hourglass-split me-1"></i>Ready</span>
                                                @else
                                                    <span class="badge bg-secondary"><i
                                                            class="bi bi-clock-history me-1"></i>In Progress</span>
                                                @endif
                                                <a href="{{ route('website-projects.show', $wp->id) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    style="font-size:.75rem;padding:.25rem .6rem;">
                                                    <i class="bi bi-eye"></i> Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Admin: Cancellation Requests ── --}}
                <div class="ds-section" id="sec-cancellations">
                    <div class="ds-section-header" onclick="toggleSection('sec-cancellations')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#fff5f5;color:#c92a2a;"><i
                                    class="bi bi-x-circle"></i></span>
                            Cancellation Requests
                        </h6>
                        <div class="ds-section-meta">
                            @if ($pendingCancellations->count())
                                <span class="ds-section-badge"
                                    style="background:#fff5f5;color:#c92a2a;">{{ $pendingCancellations->count() }}
                                    pending</span>
                            @endif
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body hidden">
                        <div class="ds-table-wrap">
                            <table class="table ds-table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Project</th>
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendingCancellations as $index => $cancel)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $cancel->project->title }}</td>
                                            <td>
                                                <button class="btn btn-outline-info btn-sm view-reason-btn"
                                                    data-bs-toggle="modal" data-bs-target="#reasonModal"
                                                    data-project="{{ $cancel->project->title }}"
                                                    data-url="{{ route('projects.show', $cancel->project->id) }}"
                                                    data-reason="{{ $cancel->reason }}" style="font-size:.78rem;">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.approveCancellation', $cancel->id) }}"
                                                    method="POST">
                                                    @csrf @method('PUT')
                                                    <button class="btn btn-success btn-sm" style="font-size:.78rem;">
                                                        <i class="bi bi-check2-circle"></i> Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4"
                                                style="font-size:.85rem;">No pending cancellations.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── Admin: General Dashboard ── --}}
                <div class="ds-section open" id="sec-admin-dash">
                    <div class="ds-section-header" onclick="toggleSection('sec-admin-dash')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#ebfbee;color:#2f9e44;"><i
                                    class="bi bi-speedometer2"></i></span>
                            Admin Overview
                        </h6>
                        <div class="ds-section-meta">
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body">
                        <x-dashboards.admin-dashboard />
                    </div>
                </div>

                {{-- ── Admin: Partner Links ── --}}
                <div class="ds-section" id="sec-partner">
                    <div class="ds-section-header" onclick="toggleSection('sec-partner')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#e6fcf5;color:#087f5b;"><i
                                    class="bi bi-link-45deg"></i></span>
                            Partner Links Data
                        </h6>
                        <div class="ds-section-meta">
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body hidden">
                        <div class="row g-3">
                            @foreach ($partnerLinksByAM as $amData)
                                <div class="col-lg-6">
                                    <div
                                        style="border:1px solid var(--ds-border);border-radius:var(--ds-radius);overflow:hidden;">
                                        <div
                                            style="background:var(--ds-success);color:#fff;padding:.65rem 1rem;font-size:.85rem;font-weight:700;">
                                            <i class="bi bi-person-badge me-1"></i>{{ $amData['am_name'] }}
                                        </div>
                                        <table class="table ds-table mb-0 text-center">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Links</th>
                                                    <th>Amount (Rs.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($amData['data'] as $row)
                                                    <tr>
                                                        <td>{{ $row['month'] }}</td>
                                                        <td>{{ $row['links'] }}</td>
                                                        <td>{{ number_format($row['amount'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ── Admin: Daily Attendance ── --}}
                <div class="ds-section" id="sec-attendance">
                    <div class="ds-section-header" onclick="toggleSection('sec-attendance')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#f3f0ff;color:#7048e8;"><i
                                    class="bi bi-calendar-check"></i></span>
                            Daily Attendance Report
                        </h6>
                        <div class="ds-section-meta">
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body hidden">
                        <input type="date" id="attendanceDate" class="form-control mb-3"
                            style="max-width:220px;border-radius:8px;font-size:.85rem;">
                        <div class="ds-table-wrap">
                            <table class="table ds-table mb-0" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Date</th>
                                        <th>Punch In</th>
                                        <th>Punch Out</th>
                                        <th>Total Hours</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ── Admin: Content Writers ── --}}
                <div class="ds-section" id="sec-writers">
                    <div class="ds-section-header" onclick="toggleSection('sec-writers')">
                        <h6 class="ds-section-title">
                            <span class="ds-section-icon" style="background:#ebfbee;color:#2f9e44;"><i
                                    class="bi bi-pencil-square"></i></span>
                            Content Writers Performance
                        </h6>
                        <div class="ds-section-meta">
                            <a href="{{ route('admin.content-writers.index') }}"
                                class="ds-section-badge text-decoration-none"
                                style="background:#ebfbee;color:#2f9e44;" onclick="event.stopPropagation()">
                                View All <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <div class="ds-chevron"><i class="bi bi-chevron-down"></i></div>
                        </div>
                    </div>
                    <div class="ds-section-body hidden">
                        @php
                            $topWriters = \App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'CONTENT'))
                                ->withCount([
                                    'assignedTasks as completed_this_month' => fn($q) => $q
                                        ->where('status', 'Completed')
                                        ->whereMonth('completed_date', now()->month)
                                        ->whereYear('completed_date', now()->year),
                                ])
                                ->orderBy('completed_this_month', 'desc')
                                ->take(5)
                                ->get();
                        @endphp
                        <div class="ds-table-wrap">
                            <table class="table ds-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Writer</th>
                                        <th>Tasks This Month</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topWriters as $writer)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div
                                                        style="width:34px;height:34px;border-radius:9px;background:var(--ds-accent-soft);color:var(--ds-accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;">
                                                        {{ strtoupper(substr($writer->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div style="font-size:.85rem;font-weight:600;">
                                                            {{ $writer->name }}</div>
                                                        <div style="font-size:.72rem;color:var(--ds-muted);">
                                                            {{ $writer->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-success">{{ $writer->completed_this_month }}
                                                    completed</span></td>
                                            <td>
                                                <a href="{{ route('admin.content-writers.show', $writer->id) }}"
                                                    class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                                                    <i class="bi bi-eye me-1"></i>Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>{{-- end .ds-accordion --}}
    </div>{{-- end .ds-page --}}

    {{-- ── Reason Modal ── --}}
    <div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:var(--ds-radius-lg);border:none;box-shadow:var(--ds-shadow-lg);">
                <div class="modal-header"
                    style="background:var(--ds-info);color:#fff;border-radius:var(--ds-radius-lg) var(--ds-radius-lg) 0 0;">
                    <span class="modal-title fw-bold"><i class="bi bi-exclamation-circle me-1"></i>Cancellation
                        Reason</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <a class="fw-bold text-dark project-title d-block mb-2"></a>
                    <p class="text-secondary reason-text mb-0"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════
         SCRIPTS
    ════════════════════════════════════════════════════════════ --}}
    <script>
        /* ── Accordion toggle ───────────────────────────────────── */
        function toggleSection(id) {
            const sec = document.getElementById(id);
            const body = sec.querySelector('.ds-section-body');
            sec.classList.toggle('open');
            body.classList.toggle('hidden');
        }

        /* ── Punch In/Out ───────────────────────────────────────── */
        function getLocationAndPunch(type) {
            if (type === 'out') {
                if (!confirm("Are you sure you want to punch out? Once you punch out, you cannot punch in again today."))
                    return;
            }
            fetch(type === 'in' ? "{{ route('attendance.punchIn') }}" : "{{ route('attendance.punchOut') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(r => r.json()).then(data => {
                alert(data.success || data.error);
                if (data.success || data.error) document.getElementById('punchInBtn').disabled = true;
            });
        }

        /* ── Task bars AJAX ─────────────────────────────────────── */
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('taskFilter').addEventListener('change', function() {
                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: {
                        filter: this.value
                    },
                    success: function(r) {
                        document.getElementById('pendingCount').textContent = r
                            .pendingTasksCountGlobal;
                        document.getElementById('completedCount').textContent = r
                            .completedTasksCountGlobal;
                        document.getElementById('createdCount').textContent = r
                            .createdTasksCountGlobal;
                        document.getElementById('collaboratorCount').textContent = r
                            .collaboratorTasksCountGlobal;
                        document.getElementById('pendingBar').style.minHeight = r
                            .taskPercentages.pending + '%';
                        document.getElementById('completedBar').style.minHeight = r
                            .taskPercentages.completed + '%';
                        document.getElementById('createdBar').style.minHeight = r
                            .taskPercentages.created + '%';
                        document.getElementById('collaboratorBar').style.minHeight = r
                            .taskPercentages.collaborator + '%';
                    }
                });
            });

            /* ── SEO filter ─────────────────────────────────────── */
            const form = document.getElementById('seoProjectFilterForm');
            const resetBtn = document.getElementById('resetFilters');
            const exportBtn = document.getElementById('exportProjects');

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const params = new URLSearchParams(new FormData(form));
                    fetch(`{{ route('dashboard.seo-projects.filter') }}?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        updateProjectsTable(data.projects);
                        document.getElementById('totalProjects').textContent = data.total;
                        document.getElementById('totalRevenue').textContent = Number(data
                            .totalRevenue).toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });
                        document.getElementById('monthlyRevenue').textContent = Number(data
                            .monthlyRevenue).toLocaleString(undefined, {
                            minimumFractionDigits: 2
                        });
                    }).catch(() => alert('An error occurred while filtering projects'));
                });
                resetBtn.addEventListener('click', function() {
                    form.reset();
                    form.dispatchEvent(new Event('submit'));
                });
                exportBtn.addEventListener('click', function() {
                    window.location.href =
                        `{{ route('dashboard.seo-projects.export') }}?${new URLSearchParams(new FormData(form))}`;
                });
            }

            /* ── Tasks trend chart ──────────────────────────────── */
            if (document.querySelector('#tasksChart')) {
                new ApexCharts(document.querySelector('#tasksChart'), {
                    chart: {
                        type: 'area',
                        height: 180,
                        toolbar: {
                            show: false
                        },
                        sparkline: {
                            enabled: false
                        }
                    },
                    series: [{
                        name: 'Tasks',
                        data: {!! json_encode(array_values($taskData->toArray())) !!}
                    }],
                    xaxis: {
                        categories: {!! json_encode(array_keys($taskData->toArray())) !!},
                        labels: {
                            style: {
                                fontSize: '10px'
                            }
                        }
                    },
                    colors: ['#4c6ef5'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: .35,
                            opacityTo: .02
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        borderColor: '#f0f2f7'
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                }).render();
            }

            /* ── Attendance (admin) ─────────────────────────────── */
            const attDate = document.getElementById('attendanceDate');
            if (attDate) {
                const today = new Date().toISOString().split('T')[0];
                attDate.value = today;

                function fetchAttendance(date) {
                    $.ajax({
                        url: "{{ route('admin.attendance.filter') }}",
                        type: "GET",
                        data: {
                            date
                        },
                        success: function(r) {
                            let rows = r.attendance.length ?
                                r.attendance.map(a =>
                                    `<tr><td>${a.user.name}</td><td>${a.date}</td><td>${a.punch_in||'—'}</td><td>${a.punch_out??'Working'}</td><td>${a.total_hours??'Pending'}</td><td>${a.status||'Pending'}</td></tr>`
                                    ).join('') :
                                '<tr><td colspan="6" class="text-center text-muted py-3" style="font-size:.82rem;">No records found.</td></tr>';
                            $('#attendanceTable tbody').html(rows);
                        },
                        error: () => $('#attendanceTable tbody').html(
                            '<tr><td colspan="6" class="text-center text-muted py-3" style="font-size:.82rem;">Holiday or weekend.</td></tr>'
                            )
                    });
                }
                fetchAttendance(today);
                attDate.addEventListener('change', () => fetchAttendance(attDate.value));
            }

            /* ── Reason modal ───────────────────────────────────── */
            $(document).on('click', '.view-reason-btn', function() {
                const url = $(this).data('url');
                $('.project-title').attr('href', url).text($(this).data('project'));
                $('.reason-text').text($(this).data('reason'));
            });
        });

        function updateProjectsTable(projects) {
            const tbody = document.getElementById('projectsTableBody');
            if (!tbody) return;
            const statusColors = {
                'Current': 'success',
                'WFF': 'warning',
                'Debt Collection': 'danger',
                'Completed': 'info',
                'Cancelled': 'secondary',
                'Expired': 'dark'
            };
            tbody.innerHTML = projects.length === 0 ?
                `<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No SEO projects found</td></tr>` :
                projects.map((p, i) => `
                    <tr>
                        <td>${i+1}</td>
                        <td><div class="fw-semibold" style="font-size:.85rem;">${p.title}</div>${p.website_url?`<small class="text-muted"><i class="bi bi-link-45deg"></i><a href="${p.website_url}" target="_blank" class="text-decoration-none">${p.website_url.substring(0,28)}${p.website_url.length>28?'…':''}</a></small>`:''}</td>
                        <td><div style="font-size:.85rem;">${p.client_name||'N/A'}</div>${p.client_email?`<small class="text-muted">${p.client_email}</small>`:''}</td>
                        <td><span class="badge bg-${statusColors[p.status]||'secondary'}">${p.status}</span></td>
                        <td><span class="badge bg-primary">${p.project_type}</span></td>
                        <td>${p.account_managers.length?p.account_managers.map(a=>`<div style="font-size:.8rem;"><i class="bi bi-person-circle me-1"></i>${a.name}</div>`).join(''):'<span class="text-muted">—</span>'}</td>
                        <td><strong class="text-success" style="font-size:.85rem;">AED ${Number(p.monthly_value).toFixed(2)}</strong><br><small class="text-muted">Total: AED ${Number(p.contract_value).toFixed(2)}</small></td>
                        <td>${p.contract_start_date?`<span style="font-size:.82rem;">${new Date(p.contract_start_date).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}</span><br><small class="text-muted">${p.contract_term} months</small>`:'<span class="text-muted">—</span>'}</td>
                        <td><div class="btn-group"><a href="/projects/${p.id}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a><a href="/projects/${p.id}/edit" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a></div></td>
                    </tr>`).join('');
        }
    </script>
</x-app-layout>
