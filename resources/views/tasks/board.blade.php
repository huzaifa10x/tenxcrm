<x-app-layout>
    <x-slot name="header">{{ __('Tasks Board') }}</x-slot>

    <style>
        /* ── Tokens ──────────────────────────────────────────────── */
        :root {
            --tk-bg:        #f4f6fb;
            --tk-surface:   #ffffff;
            --tk-border:    #e4e9f2;
            --tk-text:      #1a1f36;
            --tk-muted:     #6b7a99;
            --tk-accent:    #3b6ef5;
            --tk-accent-s:  #eef2ff;
            --tk-radius:    10px;
            --tk-shadow:    0 1px 3px rgba(0,0,0,.06), 0 4px 14px rgba(0,0,0,.05);
            --tk-ease:      .18s cubic-bezier(.4,0,.2,1);
        }

        /* ── Page wrapper ────────────────────────────────────────── */
        .tk-page { background: var(--tk-bg); padding: 1.5rem; min-height: 100vh; }

        /* ── Top header bar ──────────────────────────────────────── */
        .tk-topbar {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem;
        }
        .tk-topbar-left { display: flex; align-items: center; gap: .75rem; flex-wrap: wrap; }
        .tk-page-title {
            font-size: 1.25rem; font-weight: 800; color: var(--tk-text);
            letter-spacing: -.4px; margin: 0;
        }

        /* ── Stat pill ───────────────────────────────────────────── */
        .tk-stat-pill {
            display: flex; align-items: center; gap: .5rem;
            background: var(--tk-surface); border: 1px solid var(--tk-border);
            border-radius: 30px; padding: .35rem .85rem;
            font-size: .78rem; font-weight: 600; color: var(--tk-muted);
            box-shadow: var(--tk-shadow);
        }
        .tk-stat-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .tk-stat-pill strong { color: var(--tk-text); }

        /* ── Toolbar ─────────────────────────────────────────────── */
        .tk-toolbar {
            background: var(--tk-surface); border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius); box-shadow: var(--tk-shadow);
            padding: .85rem 1rem; margin-bottom: 1rem;
        }
        .tk-toolbar-row {
            display: flex; align-items: center; flex-wrap: wrap; gap: .6rem;
        }
        .tk-toolbar-row + .tk-toolbar-row { margin-top: .6rem; padding-top: .6rem; border-top: 1px solid var(--tk-border); }

        /* shared input / select styling */
        .tk-input, .tk-select {
            border: 1px solid var(--tk-border); border-radius: 8px;
            padding: .42rem .75rem; font-size: .8rem; color: var(--tk-text);
            background: var(--tk-bg); transition: border-color var(--tk-ease), box-shadow var(--tk-ease);
            outline: none;
        }
        .tk-input:focus, .tk-select:focus {
            border-color: var(--tk-accent);
            box-shadow: 0 0 0 3px rgba(59,110,245,.1);
        }
        .tk-input { min-width: 160px; }
        .tk-select { min-width: 170px; cursor: pointer; }

        /* search with icon */
        .tk-search-wrap { position: relative; flex: 1; min-width: 200px; }
        .tk-search-wrap .tk-search-icon {
            position: absolute; left: .7rem; top: 50%; transform: translateY(-50%);
            color: var(--tk-muted); font-size: .9rem; pointer-events: none;
        }
        .tk-search-wrap .tk-input { width: 100%; padding-left: 2.1rem; }

        /* ── Buttons ─────────────────────────────────────────────── */
        .tk-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .42rem .9rem; border-radius: 8px; font-size: .8rem;
            font-weight: 600; border: 1px solid transparent; cursor: pointer;
            transition: all var(--tk-ease); white-space: nowrap;
        }
        .tk-btn-primary   { background: var(--tk-accent); color: #fff; border-color: var(--tk-accent); }
        .tk-btn-primary:hover { background: #2d5de0; }
        .tk-btn-outline   { background: var(--tk-surface); color: var(--tk-text); border-color: var(--tk-border); }
        .tk-btn-outline:hover { background: var(--tk-bg); border-color: #c5cde0; }
        .tk-btn-ghost     { background: transparent; color: var(--tk-muted); border-color: transparent; }
        .tk-btn-ghost:hover { background: var(--tk-bg); color: var(--tk-text); }
        .tk-btn-danger-soft { background: #fff1f0; color: #e03232; border-color: #ffc9c9; }
        .tk-btn-danger-soft:hover { background: #ffe4e4; }

        /* segment: List / Board */
        .tk-seg { display: flex; background: var(--tk-bg); border: 1px solid var(--tk-border); border-radius: 8px; overflow: hidden; }
        .tk-seg-btn {
            padding: .38rem .8rem; font-size: .78rem; font-weight: 600;
            background: transparent; border: none; color: var(--tk-muted); cursor: pointer;
            transition: all var(--tk-ease);
        }
        .tk-seg-btn.active { background: var(--tk-surface); color: var(--tk-accent); box-shadow: 0 1px 4px rgba(0,0,0,.08); }

        /* ── Autocomplete dropdown ───────────────────────────────── */
        #input2 {
            position: absolute; top: calc(100% + 4px); left: 0; width: 100%;
            background: var(--tk-surface); border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius); box-shadow: 0 8px 24px rgba(0,0,0,.1);
            z-index: 1000; max-height: 280px; overflow-y: auto;
        }
        #input2 .list-group-item {
            font-size: .8rem; padding: .5rem .85rem; border: none; border-bottom: 1px solid var(--tk-border);
            color: var(--tk-text);
        }
        #input2 .list-group-item:last-child { border-bottom: none; }
        #input2 .list-group-item:hover { background: var(--tk-accent-s); color: var(--tk-accent); }

        /* ── Tab nav ─────────────────────────────────────────────── */
        .tk-tabs {
            display: flex; gap: .15rem; flex-wrap: wrap;
            background: var(--tk-bg); padding: .3rem;
            border-radius: var(--tk-radius); border: 1px solid var(--tk-border);
            margin-bottom: 1rem;
        }
        .tk-tab {
            display: flex; align-items: center; gap: .45rem;
            padding: .42rem .9rem; border-radius: 7px; font-size: .8rem; font-weight: 600;
            background: transparent; border: none; color: var(--tk-muted); cursor: pointer;
            transition: all var(--tk-ease);
        }
        .tk-tab:hover { background: var(--tk-surface); color: var(--tk-text); }
        .tk-tab.active { background: var(--tk-surface); color: var(--tk-text); box-shadow: var(--tk-shadow); }
        .tk-tab-badge {
            font-size: .65rem; font-weight: 700; padding: .15rem .45rem;
            border-radius: 20px; line-height: 1.4;
        }

        /* status colors */
        .s-todo     { color: #1d4ed8; background: #dbeafe; }
        .s-inprog   { color: #6d28d9; background: #ede9fe; }
        .s-review   { color: #065f46; background: #d1fae5; }
        .s-done     { color: #854d0e; background: #fef9c3; }
        .s-blocked  { color: #9f1239; background: #ffe4e6; }

        /* ── Table card ──────────────────────────────────────────── */
        .tk-card {
            background: var(--tk-surface); border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius); box-shadow: var(--tk-shadow); overflow: hidden;
        }
        .tk-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
        .tk-table thead th {
            background: #f8f9fd; font-size: .68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .5px; color: var(--tk-muted);
            padding: .65rem .9rem; border-bottom: 1px solid var(--tk-border);
            white-space: nowrap;
        }
        .tk-table tbody td { padding: .65rem .9rem; border-bottom: 1px solid #f0f3f9; vertical-align: middle; }
        .tk-table tbody tr { cursor: pointer; transition: background var(--tk-ease); }
        .tk-table tbody tr:hover { background: #f8f9fd; }
        .tk-table tbody tr:last-child td { border-bottom: none; }
        .tk-table-scroll { max-height: 540px; overflow-y: auto; }

        /* ── Priority dot ────────────────────────────────────────── */
        .tk-priority {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .75rem; font-weight: 600;
        }
        .tk-p-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
        .p-low    .tk-p-dot { background: #22c55e; }
        .p-medium .tk-p-dot { background: #3b82f6; }
        .p-high   .tk-p-dot { background: #f59e0b; }
        .p-urgent .tk-p-dot { background: #ef4444; }
        .p-low    { color: #15803d; }
        .p-medium { color: #1d4ed8; }
        .p-high   { color: #b45309; }
        .p-urgent { color: #dc2626; }

        /* ── Badge ───────────────────────────────────────────────── */
        .tk-badge {
            display: inline-block; font-size: .68rem; font-weight: 700;
            padding: .2rem .55rem; border-radius: 20px; line-height: 1.4;
            max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .tk-badge-blue   { background: #dbeafe; color: #1e40af; }
        .tk-badge-auto   { background: #fee2e2; color: #991b1b; }
        .tk-badge-project { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }

        /* ── Task ID chip ────────────────────────────────────────── */
        .tk-id { font-size: .7rem; font-weight: 700; color: var(--tk-muted); }

        /* ── Title link ──────────────────────────────────────────── */
        .tk-title-link {
            color: var(--tk-text); text-decoration: none; font-weight: 600;
            transition: color var(--tk-ease);
        }
        .tk-title-link:hover { color: var(--tk-accent); }
        .tk-title-link.highlighted { color: #16a34a; }

        /* ── Action button ───────────────────────────────────────── */
        .tk-action {
            width: 30px; height: 30px; border-radius: 7px;
            background: var(--tk-accent-s); color: var(--tk-accent);
            display: inline-flex; align-items: center; justify-content: center;
            font-size: .9rem; border: none; transition: all var(--tk-ease);
            text-decoration: none;
        }
        .tk-action:hover { background: var(--tk-accent); color: #fff; }

        /* ── Date text ───────────────────────────────────────────── */
        .tk-date { font-size: .74rem; color: var(--tk-muted); }

        /* ── Kanban board ────────────────────────────────────────── */
        .tk-board-wrap { overflow-x: auto; padding-bottom: 1rem; }
        .tk-board { display: flex; gap: .85rem; min-width: max-content; align-items: flex-start; }
        .tk-col {
            width: 272px; background: var(--tk-surface);
            border: 1px solid var(--tk-border); border-radius: var(--tk-radius);
            box-shadow: var(--tk-shadow); overflow: hidden; flex-shrink: 0;
        }
        .tk-col-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: .75rem 1rem; border-bottom: 1px solid var(--tk-border);
            background: #fafbfe;
        }
        .tk-col-title { font-size: .82rem; font-weight: 800; color: var(--tk-text); }
        .tk-col-count {
            font-size: .68rem; font-weight: 700; padding: .2rem .55rem;
            border-radius: 20px;
        }
        .tk-col-body { padding: .6rem; max-height: 620px; overflow-y: auto; }

        /* Kanban card */
        .tk-kcard {
            background: var(--tk-surface); border: 1px solid var(--tk-border);
            border-radius: 8px; padding: .75rem; margin-bottom: .5rem;
            cursor: pointer; transition: box-shadow var(--tk-ease), transform var(--tk-ease);
            position: relative;
        }
        .tk-kcard:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); transform: translateY(-1px); }
        .tk-kcard:last-child { margin-bottom: 0; }
        .tk-kcard-id { font-size: .65rem; font-weight: 700; color: var(--tk-muted); margin-bottom: .35rem; }
        .tk-kcard-proj {
            font-size: .68rem; font-weight: 600; padding: .15rem .45rem;
            border-radius: 4px; background: #f0fdf4; color: #166534;
            display: inline-block; margin-bottom: .4rem;
            max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .tk-kcard-title {
            font-size: .82rem; font-weight: 700; color: var(--tk-text);
            line-height: 1.35; margin-bottom: .45rem;
        }
        .tk-kcard-footer { display: flex; align-items: center; justify-content: space-between; }
        .tk-kcard-assignee { font-size: .7rem; color: var(--tk-muted); }
        .tk-kcard-date { font-size: .68rem; color: #9ca3af; }

        /* ui sortable placeholder */
        .ui-state-highlight {
            border: 2px dashed var(--tk-accent); background: var(--tk-accent-s);
            border-radius: 8px; height: 60px; margin-bottom: .5rem;
        }

        /* ── Modal & offcanvas tweaks ────────────────────────────── */
        .tk-modal .modal-content {
            border: none; border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15);
        }
        .tk-modal .modal-header {
            border-bottom: 1px solid var(--tk-border); padding: 1.1rem 1.25rem;
        }
        .tk-modal .modal-title { font-weight: 800; font-size: .95rem; color: var(--tk-text); }
        .tk-modal .modal-footer { border-top: 1px solid var(--tk-border); padding: .85rem 1.25rem; }

        /* ── Divider label ───────────────────────────────────────── */
        .tk-divider {
            font-size: .65rem; font-weight: 800; text-transform: uppercase;
            letter-spacing: .8px; color: var(--tk-muted);
            display: flex; align-items: center; gap: .5rem; margin: .5rem 0 .25rem;
        }
        .tk-divider::after { content: ''; flex: 1; height: 1px; background: var(--tk-border); }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 border-0 mb-3" style="font-size:.82rem;">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    @php
        $pendingStatuses   = ['Todo', 'In Progress', 'Review'];
        $completedStatuses = ['Completed'];
        $pendingCount      = $tasks->whereIn('status', $pendingStatuses)->where('repeat_task','0')->count();
        $completedCount    = $tasks->whereIn('status', $completedStatuses)->where('repeat_task','0')->count();
        $statusMeta = [
            'Todo'        => ['cls' => 's-todo',   'badge' => 'tk-col-count s-todo'],
            'In Progress' => ['cls' => 's-inprog',  'badge' => 'tk-col-count s-inprog'],
            'Review'      => ['cls' => 's-review',  'badge' => 'tk-col-count s-review'],
            'Completed'   => ['cls' => 's-done',    'badge' => 'tk-col-count s-done'],
            'Blocked'     => ['cls' => 's-blocked', 'badge' => 'tk-col-count s-blocked'],
        ];
    @endphp

    <div class="tk-page">

        {{-- ── Top bar ── --}}
        <div class="tk-topbar">
            <div class="tk-topbar-left">
                <h5 class="tk-page-title">Tasks</h1>
                <div class="tk-stat-pill">
                    <span class="tk-stat-dot" style="background:#3b82f6;"></span>
                    Pending <strong>{{ $pendingCount }}</strong>
                </div>
                <div class="tk-stat-pill">
                    <span class="tk-stat-dot" style="background:#f59e0b;"></span>
                    Completed <strong>{{ $completedCount }}</strong>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                {{-- View toggle --}}
                <div class="tk-seg">
                    <button class="tk-seg-btn {{ request()->query('view_type') !== 'board' ? 'active' : '' }}" id="seg-list">
                        <i class="bi bi-list-ul me-1"></i>List
                    </button>
                    <button disabled class="tk-seg-btn {{ request()->query('view_type') === 'board' ? 'active' : '' }}" id="seg-board">
                        <i class="bi bi-kanban me-1"></i>Board
                    </button>
                </div>
                {{-- hidden select (keep JS working) --}}
                <select id="view_type" style="display:none;">
                    <option value="list" {{ request()->query('view_type') !== 'board' ? 'selected' : '' }}>List View</option>
                    <option value="board" {{ request()->query('view_type') === 'board' ? 'selected' : '' }}>Board View</option>
                </select>
                <a href="{{ request()->fullUrlWithQuery(['ui' => 'new']) }}" class="tk-btn tk-btn-outline" title="Switch to the new tasks screen">
                    <i class="bi bi-stars"></i> New UI
                </a>
                <button data-bs-toggle="modal" data-bs-target="#taskCreateModal" class="tk-btn tk-btn-primary">
                    <i class="bi bi-plus-lg"></i> Create Task
                </button>
            </div>
        </div>

        {{-- ── Toolbar: filters ── --}}
        <div class="tk-toolbar">
            {{-- Row 1: date + search --}}
            <div class="tk-toolbar-row">
                <input type="date" id="start_date_search" class="tk-input" placeholder="Start from">
                <input type="date" id="end_date_search"   class="tk-input" placeholder="Start to">
                <input type="date" id="completed_date_start" class="tk-input" placeholder="Completed from">
                <input type="date" id="completed_date_end"   class="tk-input" placeholder="Completed to">
                <button id="search_tasks"  class="tk-btn tk-btn-primary"><i class="bi bi-search"></i> Search</button>
                <button id="reset_filters" class="tk-btn tk-btn-outline"><i class="bi bi-arrow-counterclockwise"></i> Reset</button>
            </div>

            {{-- Row 2: dropdowns + text search --}}
            <div class="tk-toolbar-row">
                @if (auth()->user()->hasRole('ADMIN') || route('tasks.index') == url()->current() || $userPermissions->contains('can view all tasks'))
                <select id="taskType" class="tk-select">
                    <option value="" disabled selected>Type</option>
                    <option value="ALL">All Types</option>
                    @foreach(['CONTENT','ONSITE','TECH MANAGER','OFFSITE','DESIGN','DEVELOPMENT','AM','SEO ADMIN','ADMIN','SALES MANAGER','SALES','ADHOC','SOCIAL MEDIA MANAGER'] as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
                <select id="byUser" class="tk-select">
                    <option value="" selected disabled>Assigned to</option>
                    <option value="ALL">All Users</option>
                    @foreach ($users->sortBy('name') as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @endif
                <select id="byAssignedFrom" class="tk-select">
                    <option value="" selected disabled>Assigned from</option>
                    <option value="ALL">All</option>
                    @foreach ($users->sortBy('name') as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <select id="byProject" class="tk-select">
                    <option value="" selected disabled>Project</option>
                    <option value="ALL">All Projects</option>
                    @foreach ($projects->sortBy('title') as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
                <button id="showAllAutomated" class="tk-btn tk-btn-outline">
                    <i class="bi bi-robot"></i> Automated
                </button>

                {{-- Search --}}
                <div class="tk-search-wrap" style="flex:1;min-width:200px;position:relative;">
                    <i class="bi bi-search tk-search-icon"></i>
                    <input type="text" id="taskSearch" class="tk-input" placeholder="Search task by title…" style="width:100%;padding-left:2.1rem;">
                    <div id="input2" class="list-group" style="display:none;"></div>
                </div>
            </div>
        </div>

        {{-- ════════════════════ LIST VIEW ════════════════════ --}}
        @if (request()->query('view_type') !== 'board')
        <div class="tk-tabs" id="tk-tabs-nav" role="tablist">
            @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $i => $status)
                @php $meta = $statusMeta[$status]; $cnt = $tasks->where('status',$status)->where('repeat_task','0')->count(); @endphp
                <button class="tk-tab {{ $i === 0 ? 'active' : '' }}"
                        data-target="{{ strtolower(str_replace(' ','-',$status)) }}-panel"
                        role="tab">
                    {{ $status }}
                    <span class="tk-tab-badge {{ $meta['cls'] }}">{{ $cnt }}</span>
                </button>
            @endforeach
        </div>

        @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $i => $status)
        @php $cnt = $tasks->where('status',$status)->where('repeat_task','0')->count(); @endphp
        <div class="tk-card mb-3 {{ $i === 0 ? '' : 'd-none' }}" id="{{ strtolower(str_replace(' ','-',$status)) }}-panel" role="tabpanel">
            <div class="tk-table-scroll">
                <table class="tk-table" id="{{ strtolower(str_replace(' ','-',$status)) }}-task">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Project</th>
                            <th>Title</th>
                            @if (request('type') == 'task-created-by-me' || auth()->user()->hasRole('ADMIN'))
                                <th>Assigned To</th>
                            @endif
                            <th>Priority</th>
                            <th>Created By</th>
                            <th>Start Date</th>
                            <th>Completed</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tasks->where('status', $status)->where('repeat_task','0') as $task)
                            @php
                                $prio = strtolower($task->priority ?? 'medium');
                                $pMap = ['low'=>'p-low','medium'=>'p-medium','high'=>'p-high','urgent'=>'p-urgent'];
                                $pCls = $pMap[$prio] ?? 'p-medium';
                            @endphp
                            <tr onclick="loadTaskDetails({{ $task->id }})">
                                <td><span class="tk-id">#{{ $task->id }}</span></td>
                                <td>
                                    <a target="_blank" href="{{ route('projects.show', $task->project->id) }}"
                                       class="tk-badge tk-badge-project" onclick="event.stopPropagation()">
                                        {{ $task->project->title ?? 'N/A' }}
                                    </a>
                                    @if ($task->is_automated)
                                        <span class="tk-badge tk-badge-auto ms-1">auto</span>
                                    @endif
                                </td>
                                <td>
                                    <a data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                       onclick="loadTaskDetails({{ $task->id }})"
                                       href="#" class="tk-title-link task-title-class-for-highlight">
                                        {{ $task->title }}
                                    </a>
                                </td>
                                @if (request('type') == 'task-created-by-me' || auth()->user()->hasRole('ADMIN'))
                                    <td style="font-size:.78rem;color:var(--tk-muted);">{{ $task->assignedTo->name ?? '—' }}</td>
                                @endif
                                <td>
                                    <span class="tk-priority {{ $pCls }}">
                                        <span class="tk-p-dot"></span>{{ $task->priority }}
                                    </span>
                                </td>
                                <td class="tk-date">{{ $task->createdBy->name ?? 'System' }}</td>
                                <td class="tk-date">
                                    {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->setTimezone('Asia/Dubai')->format('d M Y, h:i A') : '—' }}
                                </td>
                                <td class="tk-date">
                                    {{ $task->completed_date ? \Carbon\Carbon::parse($task->completed_date)->setTimezone('Asia/Dubai')->format('d M Y, h:i A') : '—' }}
                                </td>
                                <td onclick="event.stopPropagation()">
                                    <a target="_blank" href="{{ route('tasks.show', $task->id) }}" class="tk-action" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;padding:2.5rem;color:var(--tk-muted);font-size:.82rem;">
                                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>No tasks in this status
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach
        @endif

        {{-- ════════════════════ BOARD VIEW ════════════════════ --}}
        @if (request()->query('view_type') === 'board')
        <div class="tk-board-wrap">
            <div class="tk-board" id="sortable-wrapper">
                @foreach (['Todo', 'In Progress', 'Review', 'Completed', 'Blocked'] as $status)
                    @php $meta = $statusMeta[$status]; $cnt = $tasks->where('status',$status)->where('repeat_task','0')->count(); @endphp
                    <div class="tk-col">
                        <div class="tk-col-header">
                            <span class="tk-col-title">{{ $status }}</span>
                            <span class="{{ $meta['badge'] }}">{{ $cnt }}</span>
                        </div>
                        <div class="tk-col-body connectedSortable kanban-items"
                             id="{{ strtolower(str_replace(' ','-',$status)) }}"
                             data-status="{{ $status }}">
                            @foreach ($tasks->where('status', $status)->where('repeat_task','0') as $task)
                                <div class="tk-kcard kanban-item"
                                     data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                     onclick="loadTaskDetails({{ $task->id }})"
                                     data-task-id="{{ $task->id }}"
                                     data-status="{{ $status }}">
                                    <div class="tk-kcard-id">#{{ $task->id }}</div>
                                    <div class="tk-kcard-proj">{{ $task->project->title ?? '' }}</div>
                                    <div class="tk-kcard-title">{{ $task->title }}</div>
                                    <div class="tk-kcard-footer">
                                        @if ($userPermissions->contains('can create tasks'))
                                            <span class="tk-kcard-assignee">
                                                <i class="bi bi-person me-1"></i>{{ $task->assignedTo->name ?? 'Unassigned' }}
                                            </span>
                                        @endif
                                        <span class="tk-kcard-date">
                                            {{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('d M') : '—' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- /tk-page --}}

    {{-- ── Offcanvas task detail ── --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel"
         style="width:min(1020px,100vw);">
        <div class="offcanvas-header" style="border-bottom:1px solid var(--tk-border);">
            <h6 class="mb-0 fw-bold" style="font-size:.9rem;">Task Detail</h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="taskDetails" style="background:#fafbfe;">…</div>
    </div>

    {{-- ── Create Task Modal ── --}}
    <form action="{{ route('tasks.store') }}" id="task-create-form" method="POST" enctype="multipart/form-data">
        <div class="modal fade tk-modal" id="taskCreateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create New Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.25rem;">
                        @csrf
                        @include('tasks.create', ['projects' => $projects])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="tk-btn tk-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="tk-btn tk-btn-primary task-submit">
                            <i class="bi bi-check2"></i> Save Task
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- ── Scripts ── --}}
    <script>
        /* ── Tab switching (list view) ───────────────────────────── */
        document.querySelectorAll('.tk-tab').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tk-tab').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                var target = this.getAttribute('data-target');
                document.querySelectorAll('[role="tabpanel"]').forEach(p => p.classList.add('d-none'));
                var panel = document.getElementById(target);
                if (panel) panel.classList.remove('d-none');
            });
        });

        /* ── View toggle (segment control) ──────────────────────── */
        document.getElementById('seg-list').addEventListener('click', function() {
            document.getElementById('view_type').value = 'list';
            document.getElementById('view_type').dispatchEvent(new Event('change'));
        });
        document.getElementById('seg-board').addEventListener('click', function() {
            document.getElementById('view_type').value = 'board';
            document.getElementById('view_type').dispatchEvent(new Event('change'));
        });
    </script>

    <script type="text/javascript">
        var uploadedDocumentMap = {};
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#taskAttachmentsDropzone", {
            url: "{{ route('tasks.store') }}",
            autoProcessQueue: false, uploadMultiple: true,
            paramName: "attachments", maxFiles: 10, maxFilesize: 5,
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt",
            addRemoveLinks: true,
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            init: function() {
                var dz = this;
                dz.on("sending", function(file, xhr, formData) {
                    var fd = new FormData(document.querySelector("#task-create-form"));
                    fd.forEach((v, k) => formData.append(k, v));
                    formData.append("attachments[]", file);
                });
                dz.on("success",     function(f,r) { uploadedDocumentMap[f.name] = r.name; });
                dz.on("removedfile", function(f)   { delete uploadedDocumentMap[f.name]; });
            }
        });

        $('.task-submit').click(function(e) {
            e.preventDefault();
            if (!$('#department_id').val()) { toastr.error('Please select Department!'); $('#department_id').focus(); return; }
            if (!$('#sub_type').val())       { toastr.error('Please select Task Type!');  $('#sub_type').focus(); return; }
            $(this).attr('disabled', true).text('Saving…');
            setTimeout(() => $(this).attr('disabled', false).html('<i class="bi bi-check2"></i> Save Task'), 3000);
            var fd = new FormData($("#task-create-form")[0]);
            myDropzone.files.forEach(f => fd.append("attachments[]", f));
            $.ajax({
                url: "{{ route('tasks.store') }}", type: "POST", data: fd,
                processData: false, contentType: false,
                success: function() {
                    toastr.success('Task created successfully');
                    setTimeout(() => window.location.href = "{{ route('tasks.index') }}", 2000);
                },
                error: function(xhr) { console.log(xhr.responseText); toastr.error('Error creating task'); }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            /* ── Kanban sortable ── */
            $(".kanban-items").sortable({
                connectWith: ".kanban-items", placeholder: "ui-state-highlight",
                stop: function(event, ui) {
                    let taskId = ui.item.attr("data-task-id");
                    let newStatus = ui.item.parent().attr("data-status");
                    if (newStatus === "Completed") {
                        toastr.error("Complete the task properly via its detail view.");
                        return false;
                    }
                    $.ajax({
                        url: "{{ route('tasks.updateStatus') }}", type: "POST",
                        data: { _token: "{{ csrf_token() }}", task_id: taskId, status: newStatus },
                        success: () => toastr.success("Status updated!"),
                        error:   () => toastr.error("Error updating status")
                    });
                }
            }).disableSelection();

            /* ── Load task on URL (e.g. /tasks/31) ── */
            let segments = window.location.pathname.split('/');
            if (segments[2]) loadTaskDetails(segments[2]);

            /* ── Filters ── */
            let urlParams = new URLSearchParams(window.location.search);

            // task type
            let type = urlParams.get('category');
            if (type) $('#taskType').val(type);
            $('#taskType').on('change', function() {
                let v = $(this).val();
                v && v !== 'ALL' ? urlParams.set('category', v) : urlParams.delete('category');
                window.location.search = urlParams.toString();
            });

            // by user
            if (urlParams.get('user_id')) $('#byUser').val(urlParams.get('user_id'));
            $('#byUser').on('change', function() {
                let v = $(this).val(), base = "{{ request()->url() }}";
                v === 'ALL' ? window.location.href = base : (urlParams.set('user_id', v), window.location.href = base + '?' + urlParams);
            });

            // by project
            if (urlParams.get('project_id')) $('#byProject').val(urlParams.get('project_id'));
            $('#byProject').on('change', function() {
                let v = $(this).val(), base = "{{ request()->url() }}";
                v === 'ALL' ? window.location.href = base : (urlParams.set('project_id', v), window.location.href = base + '?' + urlParams);
            });

            // by assigned from
            if (urlParams.get('assigned_from')) $('#byAssignedFrom').val(urlParams.get('assigned_from'));
            $('#byAssignedFrom').on('change', function() {
                let v = $(this).val(), base = "{{ request()->url() }}";
                v === 'ALL' ? window.location.href = base : (urlParams.set('assigned_from', v), window.location.href = base + '?' + urlParams);
            });

            // view type
            if (urlParams.get('view_type')) $('#view_type').val(urlParams.get('view_type'));
            $('#view_type').on('change', function() {
                urlParams.set('view_type', $(this).val());
                window.location.href = "{{ request()->url() }}" + '?' + urlParams;
            });

            // automated
            $('#showAllAutomated').on('click', function() {
                urlParams.set('all-automated', 1);
                window.location.href = "{{ request()->url() }}" + '?' + urlParams;
            });

            /* ── Date pickers ── */
            let dateFields = {
                '#start_date_search':    urlParams.get('start_date'),
                '#end_date_search':      urlParams.get('end_date'),
                '#completed_date_start': urlParams.get('completed_date_start'),
                '#completed_date_end':   urlParams.get('completed_date_end'),
            };
            Object.entries(dateFields).forEach(([id, val]) => {
                flatpickr(id, { enableTime:false, dateFormat:'Y-m-d', altInput:true, altFormat:'F j, Y', allowInput:true, defaultDate: val||null });
            });

            $('#search_tasks').on('click', function() {
                let map = {
                    start_date:           '#start_date_search',
                    end_date:             '#end_date_search',
                    completed_date_start: '#completed_date_start',
                    completed_date_end:   '#completed_date_end',
                };
                Object.entries(map).forEach(([k,id]) => {
                    let v = $(id).val();
                    v ? urlParams.set(k,v) : urlParams.delete(k);
                });
                window.location.search = urlParams.toString();
            });

            $('#reset_filters').on('click', function() {
                ['#start_date_search','#end_date_search','#completed_date_start','#completed_date_end'].forEach(id => $(id).flatpickr().clear());
                window.location.search = '';
            });

            /* ── Title highlight ── */
            $('.task-title-class-for-highlight').on('click', function() {
                $('.task-title-class-for-highlight').removeClass('highlighted');
                $(this).addClass('highlighted');
            });

            /* ── Task search autocomplete ── */
            $('#taskSearch').on('input', function() {
                let q = $(this).val();
                if (q.length < 2) { $('#input2').hide(); return; }
                $.ajax({
                    url: "{{ route('tasks.search.index') }}", method: 'GET', data: { query: q },
                    success: function(r) {
                        if (r.length > 0) {
                            let html = r.map(t =>
                                `<a target="_blank" href="{{ url('tasks') }}/${t.id}" class="list-group-item list-group-item-action">
                                    <span style="font-size:.68rem;color:var(--tk-muted);">#${t.id}</span>
                                    <strong style="margin:0 .35rem;">${t.title}</strong>
                                    <span style="font-size:.7rem;color:#16a34a;">${t.project_title}</span>
                                    <span style="float:right;font-size:.68rem;color:var(--tk-muted);">${t.status}</span>
                                </a>`
                            ).join('');
                            $('#input2').html(html).show();
                        } else { $('#input2').hide(); }
                    }
                });
            });
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#taskSearch, #input2').length) $('#input2').hide();
            });
        });

        function loadTaskDetails(taskId) {
            $("input[name='task_id']").val(taskId);
            $('#taskDetails').html('<div class="d-flex justify-content-center align-items-center" style="height:200px;"><div class="spinner-border text-primary" role="status"></div></div>');
            $.ajax({
                url: '{{ route('tasks.show', ':id') }}'.replace(':id', taskId),
                method: 'GET', data: { is_ajax: true },
                success: function(r) {
                    $('#taskDetails').html(r);
                    if (typeof loadTaskAttachments === 'function') loadTaskAttachments();
                    if (typeof loadComments === 'function') loadComments();
                    if ($('.editor').length) $('.editor').richText();
                },
                error: function() {
                    $('#taskDetails').html('<p class="text-danger p-3" style="font-size:.82rem;">Task not found or has been deleted.</p>');
                }
            });
        }
    </script>
</x-app-layout>