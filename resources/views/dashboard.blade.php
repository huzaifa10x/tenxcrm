<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    <div class="d-flex align-items-center justify-content-between mb-10">
        <button class="btn btn-sm btn-success" id="punchInBtn">Punch In</button>
        <button class="btn btn-sm btn-danger" id="punchOutBtn">Punch Out</button>
    </div>

    <div class="row gy-3 mb-10">
        <div class="col-xxl-6">
            <div class="card h-100">
                <div
                    class="card-header border-bottom-0 pb-0 d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <h6 id="card-title" class="mb-2 fw-bold text-lg mb-0">Today Tasks</h6>
                    <select id="taskFilter"
                        class="form-select form-select-sm w-auto bg-base border text-secondary-light radius-8">
                        <option value="today" selected>Today</option>
                        <option value="weekly">Last Week</option>
                        <option value="monthly">Last Month</option>
                        <option value="yearly">Last Year</option>
                    </select>

                </div>
                <div class="card-body">
                    <div class="position-relative h-100 min-h-320-px">
                        <div class="row g-3 h-100">
                            <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                                <div id="pendingBar"
                                    class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-warning"
                                    style="min-height: {{ $taskPercentages['pending'] }}%;">
                                    <span
                                        class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-warning-600 rounded-circle mb-12">
                                        <h6 id="pendingCount" class="mb-0 text-base">{{ $pendingTasksCountGlobal }}</h6>
                                    </span>
                                    <span class="text-secondary-light">Pending</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                                <div id="completedBar"
                                    class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-lilac"
                                    style="min-height: {{ $taskPercentages['completed'] }}%;">
                                    <span
                                        class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-lilac-600 rounded-circle mb-12">
                                        <h6 id="completedCount" class="mb-0 text-base">{{ $completedTasksCountGlobal }}
                                        </h6>
                                    </span>
                                    <span class="text-secondary-light">Completed</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                                <div id="createdBar"
                                    class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-primary"
                                    style="min-height: {{ $taskPercentages['created'] }}%;">
                                    <span
                                        class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-primary-600 rounded-circle mb-12">
                                        <h6 id="createdCount" class="mb-0 text-base">{{ $createdTasksCountGlobal }}
                                        </h6>
                                    </span>
                                    <span class="text-secondary-light">Assigned</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 d-flex flex-column justify-content-end">
                                <div id="collaboratorBar"
                                    class="d-flex flex-column align-items-center p-24 pt-16 rounded-top-4 bg-tb-success"
                                    style="min-height: {{ $taskPercentages['collaborator'] }}%;">
                                    <span
                                        class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-success-600 rounded-circle mb-12">
                                        <h6 id="collaboratorCount" class="mb-0 text-base">
                                            {{ $collaboratorTasksCountGlobal }}</h6>
                                    </span>
                                    <span class="text-secondary-light">Collaborator</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Latest Notifications</h6>
                    </div>
                    @foreach ($latestNotifications as $notification)
                        <a href="{{ $notification['data']['url'] }}" class="d-flex align-items-start gap-2 mb-20">
                            <span
                                class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-success-600 rounded-circle mb-12">
                                <h6 id="collaboratorCount" class="mb-0 text-base">
                                    {{ $notification->notifiable_id }}
                                </h6>
                            </span>
                            <div class="flex-grow-1">
                                <h6 class="text-md mb-1 fw-semibold">{{ $notification['data']['title'] }}</h6>
                                <p class="text-sm text-secondary-light fw-medium mb-1">
                                    {{ $notification['data']['message'] }}</p>
                                <span
                                    class="text-sm text-secondary-light fw-normal">{{ $notification->created_at }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @if ($role === 'AM')
        <style>
            .fc-toolbar-title {
                font-size: 16px !important;
            }

            .fc-h-event .fc-event-main {
                color: #000000 !important;
                font-size: 10px !important;
            }
        </style>
        <div class="row gy-3 mb-10">
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon1.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">Current Portfolio</span>
                                    <h6 class="fw-semibold mt-2">{{ number_format($currentPortfolio, 2) }}</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i> 00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-2 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon2.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">Current Revenue</span>
                                    <h6 class="fw-semibold mt-2">00</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i> 00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-3 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-lilac-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon3.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">4 Months Avg</span>
                                    <h6 class="fw-semibold mt-2">00</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-danger-focus px-6 py-2 rounded-2 fw-medium text-danger-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-down-line"></i> 00
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-sm-6">
                <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-4 border border-white">
                    <div class="card-body p-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                            <div class="d-flex align-items-center gap-10">
                                <span
                                    class="mb-0 w-48-px h-48-px bg-success-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                                    <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon4.svg') }}"
                                        alt="">
                                </span>
                                <div>
                                    <span class="fw-medium text-secondary-light text-md">New / Renew / UpSell</span>
                                    <h6 class="fw-semibold mt-2">00</h6>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm mb-0 d-flex align-items-center flex-wrap gap-12 mt-12 text-secondary-light">
                            <span
                                class="bg-success-focus px-6 py-2 rounded-2 fw-medium text-success-main text-sm d-flex align-items-center gap-1">
                                <i class="ri-arrow-right-up-line"></i>00
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-3 mb-10">
            <div class="col-lg-12">
                <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                    <div
                        class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                        <span class="fw-bold">Last 15 Days Tasks</span>
                    </div>
                    <div class="card-body p-0">
                        <div id="tasksChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-3 mb-10">
            <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js"></script>
            <div class="col-lg-6">
                <div class="card h-100 p-3 radius-8 border-0">
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100 radius-8 border-0">
                    <div class="card-header">
                        <input type="hidden" id="todoDate" class="form-control mb-3">
                        <div id="todoForm" class="d-none">
                            <div class="input-group">
                                <input type="text" id="todoTask" class="form-control" placeholder="Enter Todo">
                                <button class="input-group-text bg-base" id="saveTodo"> <iconify-icon
                                        icon="lucide:plus"></iconify-icon>Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto">
                        <ul id="todoList" class="list-group mt-2"></ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card h-100 radius-8 border-0">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg">Campaigns</h6>
                            {{-- <div class="">
                                <select
                                    class="form-select form-select-sm w-auto bg-base border text-secondary-light radius-8">
                                    <option>Yearly</option>
                                    <option>Monthly</option>
                                    <option>Weekly</option>
                                    <option>Today</option>
                                </select>
                            </div> --}}
                        </div>
                        <div class="mt-3">
                            @foreach (['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled', 'Completed'] as $status)
                                <div class="d-flex align-items-center justify-content-between gap-3 mb-12">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="text-xxl line-height-1 d-flex align-content-center flex-shrink-0 text-{{ strtolower(str_replace(' ', '-', $status)) }}">
                                            <iconify-icon icon="ri:discount-percent-fill"
                                                class="icon"></iconify-icon>
                                        </span>
                                        <span
                                            class="text-primary-light fw-medium text-sm ps-12">{{ $status }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 w-100">
                                        <div class="w-100 max-w-66 ms-auto">
                                            <div class="progress progress-sm rounded-pill" role="progressbar"
                                                aria-valuenow="{{ $campaignCounts[$status] ?? 0 }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                                <div class="progress-bar bg-{{ strtolower(str_replace(' ', '-', $status)) }} rounded-pill"
                                                    style="width: {{ ($campaignCounts[$status] ?? 0) * 10 }}%;">
                                                </div>
                                            </div>
                                        </div>
                                        <span
                                            class="text-secondary-light font-xs fw-semibold">{{ $campaignCounts[$status] ?? 0 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-3 mb-10">
            <div class="col-lg-12">
                <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                    <div
                        class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center active" id="pending-tab"
                                    data-bs-toggle="pill" data-bs-target="#pending-task" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-to-do-list"
                                    aria-selected="true">
                                    Pending Tasks
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $pendingTasks->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="completed-tab"
                                    data-bs-toggle="pill" data-bs-target="#completed-task" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-recent-leads"
                                    aria-selected="false" tabindex="-1">
                                    Completed Tasks
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $completedTasks->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="assigned-tab"
                                    data-bs-toggle="pill" data-bs-target="#assigned-tasks" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-recent-leads"
                                    aria-selected="false" tabindex="-1">
                                    Tasks I've Assigned
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $assignedTasks->count() }}</span>
                                </button>
                            </li>
                        </ul>
                        <a href="{{ route('tasks.board.index') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Tasks
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="task-tabContent">
                            <!-- PENDING TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade show active p-3"
                                id="pending-task" role="tabpanel">
                                @if ($pendingTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Assign To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                    <td>{{ $task->assignedTo->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No pending tasks found.</p>
                                @endif
                            </div>
                            <!-- COMPLETED TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-3"
                                id="completed-task" role="tabpanel">
                                @if ($completedTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Completion Date</th>
                                                <th>Assign To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($completedTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                    <td>{{ $task->completions->sortByDesc('id')->first()->created_at ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $task->assignedTo->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No completed tasks found.</p>
                                @endif
                            </div>
                            <!-- ASSIGNED TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-3"
                                id="assigned-tasks" role="tabpanel">
                                @if ($assignedTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Completion Date</th>
                                                <th>Assign To</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assignedTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                    <td>{{ $task->completions->sortByDesc('id')->first()->created_at ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $task->assignedTo->name ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No assigned tasks found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card basic-data-table">
            @if ($userPermissions->contains('can add projects'))
                <div class="card-header">
                    <span class="fw-bold">Projects</span>
                </div>
            @endif
            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Project</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Account Manager</th>
                            <th>Contract End Date</th>
                            <th>Monthly Revenue (w/o VAT)</th>
                            <th>Tech</th>
                            <th>Created By</th>
                            <th>Pending Tasks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                                </td>
                                <td>{{ $project->project_type }}</td>
                                <td>
                                    @if ($userPermissions->contains('can update project status'))
                                        @if (auth()->user()->hasRole('ADMIN') || ($project->createdBy && $project->createdBy->id == auth()->user()->id))
                                            <select class="form-control project-status"
                                                data-project-id="{{ $project->id }}" required>
                                                <option selected disabled value="">Select status</option>
                                                <option value="Current"
                                                    {{ $project->status == 'Current' ? 'selected' : '' }}>
                                                    Current
                                                </option>
                                                <option value="Paused"
                                                    {{ $project->status == 'Paused' ? 'selected' : '' }}>
                                                    Paused
                                                </option>
                                                <option value="WFF"
                                                    {{ $project->status == 'WFF' ? 'selected' : '' }}>
                                                    WFF
                                                </option>
                                                <option value="Expired"
                                                    {{ $project->status == 'Expired' ? 'selected' : '' }}>
                                                    Expired
                                                </option>
                                                <option value="Debt Collection"
                                                    {{ $project->status == 'Debt Collection' ? 'selected' : '' }}>Debt
                                                    Collection
                                                </option>
                                                <option value="Cancelled"
                                                    {{ $project->status == 'Cancelled' ? 'selected' : '' }}>
                                                    Cancelled
                                                </option>
                                                <option value="Completed"
                                                    {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                                    Completed
                                                </option>
                                            </select>
                                        @else
                                            {{ $project->status }}
                                        @endif
                                    @else
                                        {{ $project->status }}
                                    @endif
                                </td>
                                <td>{{ $project->accountManagers->pluck('name')->first() ?? '' }}</td>
                                <td>{{ Carbon\Carbon::parse($project->contract_start_date)->addMonths($project->contract_term)->format('Y-m-d') ?? '' }}
                                </td>
                                <td>{{ $project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? '' }}
                                </td>
                                <td>{{ $project->techManagers->pluck('name')->first() ?? '' }}</td>
                                <td>
                                    {{ $project->createdBy->name ?? 'N/A' }}
                                </td>
                                <td>
                                    <span
                                        class="badge text-sm fw-semibold w-32-px h-32-px d-flex justify-content-center align-items-center rounded-circle border border-danger-600 text-danger-600 radius-4 text-white">
                                        {{ $project->tasks->where('status', 'Todo')->count() ?? 0 }}
                                    </span>
                                </td>
                                <td class="">
                                    <div class="d-flex align-items-center gap-10">
                                        <a href="{{ route('projects.show', $project->id) }}"
                                            class="bg-info-focus bg-hover-info-200 text-info-600 fw-medium w-40-px h-40-px d-flex justify-content-center align-items-center rounded-circle">
                                            <iconify-icon icon="majesticons:eye-line"
                                                class="icon text-xl"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let today = new Date().toISOString().split('T')[0];
                document.getElementById('todoDate').value = today;
                fetchTodos(today);

                // Fetch and display todos for selected date
                function fetchTodos(selectedDate) {
                    document.getElementById('todoForm').classList.remove('d-none');
                    fetch(`{{ route('todo.getByDate') }}?date=${selectedDate}`)
                        .then(response => response.json())
                        .then(data => {
                            let todoList = document.getElementById('todoList');
                            todoList.innerHTML = "";
                            if (data.length > 0) {
                                data.forEach(todo => {
                                    let statusIcon = todo.status === 'completed' ? '✅' : '⏳';
                                    let actionButton = todo.status === 'pending' ?
                                        `<button class="btn btn-sm btn-success mark-done" data-id="${todo.id}">✔</button>` :
                                        '';

                                    let listItem = document.createElement('li');
                                    listItem.classList.add('list-group-item', 'd-flex',
                                        'justify-content-between', 'align-items-center');
                                    listItem.innerHTML =
                                        `<span>${statusIcon} ${todo.task}</span> ${actionButton}`;
                                    todoList.appendChild(listItem);
                                });
                            } else {
                                todoList.innerHTML =
                                    `<li class="list-group-item text-muted">No tasks found for this date.</li>`;
                            }
                        });
                }

                // Handle date change
                document.getElementById('todoDate').addEventListener('change', function() {
                    fetchTodos(this.value);
                });

                // Save new todo
                document.getElementById('saveTodo').addEventListener('click', function() {
                    let date = document.getElementById('todoDate').value;
                    let task = document.getElementById('todoTask').value.trim();

                    if (!task) {
                        alert("Please enter a task!");
                        return;
                    }

                    fetch("{{ route('todo.store') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            date: date,
                            task: task
                        })
                    }).then(response => response.json()).then(() => {
                        document.getElementById('todoTask').value = '';
                        fetchTodos(date);
                        calendar.refetchEvents();
                    });
                });

                // Mark task as done (Event Delegation)
                document.getElementById('todoList').addEventListener('click', function(e) {
                    if (e.target.classList.contains('mark-done')) {
                        let todoId = e.target.dataset.id;
                        fetch("{{ route('todo.markDone') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                id: todoId
                            })
                        }).then(response => response.json()).then(() => {
                            fetchTodos(document.getElementById('todoDate').value); // Refresh list
                        });
                    }
                });

                // Initialize FullCalendar
                let calendarEl = document.getElementById('calendar');
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    selectable: true,
                    dateClick: function(info) {
                        document.getElementById('todoDate').value = info.dateStr;
                        fetchTodos(info.dateStr);
                    },
                    events: function(fetchInfo, successCallback) {
                        fetch("{{ route('todo.getDates') }}")
                            .then(response => response.json())
                            .then(dates => {
                                let events = dates.map(date => ({
                                    title: date?.task,
                                    start: date?.todo_date,
                                    allDay: true,
                                    color: "#ffcc00"
                                }));
                                successCallback(events);
                            });
                    }
                });

                calendar.render();
            });
        </script>
    @elseif ($role === 'CONTENT')
        <div class="row gy-3 mb-10">
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100 border shadow-none radius-8 overflow-hidden">
                    <div class="card-body p-24">

                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100 border shadow-none radius-8 overflow-hidden">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg">Tasks</h6>
                        </div>
                        <div id="compAndPendingTasks"></div>
                        <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                                <span class="text-secondary-light text-sm fw-normal">Pending:
                                    <span class="text-primary-light fw-semibold">{{ $pendingTasksCount }}</span>
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                                <span class="text-secondary-light text-sm fw-normal">Completed:
                                    <span class="text-primary-light fw-semibold">{{ $completedTasksCount }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-6">
                <div class="card h-100 border shadow-none radius-8 overflow-hidden">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                            <h6 class="mb-2 fw-bold text-lg">Words Count</h6>
                        </div>
                        <div id="compAndPendingWords"></div>
                        <ul class="d-flex flex-wrap align-items-center justify-content-between mt-3 gap-3">
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                                <span class="text-secondary-light text-sm fw-normal">Pending:
                                    <span class="text-primary-light fw-semibold">{{ $pendingWords }}</span>
                                </span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                                <span class="text-secondary-light text-sm fw-normal">Delivered:
                                    <span class="text-primary-light fw-semibold">{{ $completedWords }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-3 mb-10">
            <div class="col-lg-12">
                <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                    <div
                        class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                        <span class="fw-bold">Last 15 Days Tasks</span>
                    </div>
                    <div class="card-body p-0">
                        <div id="tasksChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gy-3 mb-10">
            <div class="col-lg-12">
                <div class="border border-secondary card p-0 overflow-hidden position-relative radius-12 h-100">
                    <div
                        class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
                        <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center active" id="pending-tab"
                                    data-bs-toggle="pill" data-bs-target="#pending-task" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-to-do-list"
                                    aria-selected="true">
                                    Pending Tasks
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $pendingTasks->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="completed-tab"
                                    data-bs-toggle="pill" data-bs-target="#completed-task" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-recent-leads"
                                    aria-selected="false" tabindex="-1">
                                    Completed Tasks
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $completedTasks->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="assigned-tab"
                                    data-bs-toggle="pill" data-bs-target="#assigned-tasks" type="button"
                                    role="tab" type="button" role="tab" aria-controls="pills-recent-leads"
                                    aria-selected="false" tabindex="-1">
                                    Tasks I've Assigned
                                    <span
                                        class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $assignedTasks->count() }}</span>
                                </button>
                            </li>
                        </ul>
                        <a href="{{ route('tasks.board.index') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            Tasks
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="task-tabContent">
                            <!-- PENDING TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade show active p-3"
                                id="pending-task" role="tabpanel">
                                @if ($pendingTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Content Type</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->contentTask->sub_type ?? 'N/A' }}</td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No pending tasks found.</p>
                                @endif
                            </div>
                            <!-- COMPLETED TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-3"
                                id="completed-task" role="tabpanel">
                                @if ($completedTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Word Count</th>
                                                <th>Number Of Pages</th>
                                                <th>Content Type</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Completion Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($completedTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->completions->where('field_name', 'number_of_words')->sortByDesc('id')->first()->field_value ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $task->contentTask->number_of_pages ?? 'N/A' }}</td>
                                                    <td>{{ $task->contentTask->sub_type ?? 'N/A' }}</td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                    <td>{{ $task->completions->sortByDesc('id')->first()->created_at ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No completed tasks found.</p>
                                @endif
                            </div>
                            <!-- ASSIGNED TASKS TAB -->
                            <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-3"
                                id="assigned-tasks" role="tabpanel">
                                @if ($assignedTasks->count())
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Start Date</th>
                                                <th>Due Date</th>
                                                <th>Completion Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assignedTasks as $task)
                                                <tr>
                                                    <td>{{ $task->id }}</td>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                                                    </td>
                                                    <td><x-badge :text="$task->status" type="status" /></td>
                                                    <td>{{ $task->start_date }}</td>
                                                    <td>{{ $task->due_date }}</td>
                                                    <td>{{ $task->completions->sortByDesc('id')->first()->created_at ?? 'N/A' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>No assigned tasks found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($role == 'ADMIN')
    @endif
    @if (auth()->user()->hasRole('ADMIN'))
        <div class="row gy-3 mb-10">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-body p-24">
                        <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                            <h6 class="mb-2 fw-bold text-lg mb-0">Daily Attendance Report</h6>
                        </div>
                        <input placeholder="select date" type="date" id="attendanceDate"
                            class="form-control mb-3">
                        <table class="table table-bordered" id="attendanceTable">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Punch In</th>
                                    <th>Punch Out</th>
                                    <th>Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        var options = {
            series: [{{ $pendingWords ?? 0 }}, {{ $completedWords ?? 0 }}],
            colors: ['#F4941E', '#22C55E'],
            labels: ['Pending', 'Completed'],
            legend: {
                show: false
            },
            chart: {
                type: 'donut',
                height: 270,
                sparkline: {
                    enabled: true
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 150,
                        height: 150,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
        };
        var chart = new ApexCharts(document.querySelector("#compAndPendingWords"), options);
        chart.render();
        var options1 = {
            series: [{{ $pendingTasksCount ?? 0 }}, {{ $completedTasksCount ?? 0 }}],
            colors: ['#F4941E', '#22C55E'],
            labels: ['Pending', 'Completed'],
            legend: {
                show: false
            },
            chart: {
                type: 'donut',
                height: 270,
                sparkline: {
                    enabled: true
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 150,
                        height: 150,
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
        };
        var chart1 = new ApexCharts(document.querySelector("#compAndPendingTasks"), options1);
        chart1.render();
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                chart: {
                    type: 'line',
                    height: 350
                },
                series: [{
                    name: 'Tasks',
                    data: {!! json_encode(array_values($taskData->toArray())) !!}
                }],
                xaxis: {
                    categories: {!! json_encode(array_keys($taskData->toArray())) !!}
                },
                colors: ['#008FFB'],
                stroke: {
                    curve: 'smooth'
                }
            };

            var chart = new ApexCharts(document.querySelector("#tasksChart"), options);
            chart.render();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#taskFilter').change(function() {
                let filter = $(this).val();
                let selectedText = $('#taskFilter option:selected').text();
                $('#card-title').text(selectedText + " Tasks");

                $.ajax({
                    url: "{{ route('dashboard') }}",
                    type: "GET",
                    data: {
                        filter: filter
                    },
                    success: function(response) {
                        // Update task counts
                        $('#pendingCount').text(response.pendingTasksCountGlobal);
                        $('#completedCount').text(response.completedTasksCountGlobal);
                        $('#createdCount').text(response.createdTasksCountGlobal);
                        $('#collaboratorCount').text(response.collaboratorTasksCountGlobal);

                        // Update bar heights based on new percentages
                        $('#pendingBar').css('min-height', response.taskPercentages.pending +
                            '%');
                        $('#completedBar').css('min-height', response.taskPercentages
                            .completed + '%');
                        $('#createdBar').css('min-height', response.taskPercentages.created +
                            '%');
                        $('#collaboratorBar').css('min-height', response.taskPercentages
                            .collaborator + '%');
                    }
                });
            });
        });
    </script>
    <script>
        function getLocationAndPunch(type) {
            // if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                let data = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                if (type === 'out') {
                    if (!confirm(
                            "Are you sure you want to punch out? Once you punch out, you cannot punch in again today."
                        )) {
                        return;
                    }
                }
                fetch(type === 'in' ? "{{ route('attendance.punchIn') }}" :
                        "{{ route('attendance.punchOut') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(data)
                        }).then(response => response.json())
                    .then(data => alert(data.success || data.error));
            });
            // } else {
            //     alert("Location access is required!");
            // }
        }

        document.getElementById('punchInBtn').addEventListener('click', () => getLocationAndPunch('in'));
        document.getElementById('punchOutBtn').addEventListener('click', () => getLocationAndPunch('out'));
    </script>
    @if (auth()->user()->hasRole('ADMIN'))
        <script>
            $(document).ready(function() {
                let today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
                $('#attendanceDate').val(today); // Set default value in date picker

                function fetchAttendance(date) {
                    $.ajax({
                        url: "{{ route('admin.attendance.filter') }}",
                        type: "GET",
                        data: {
                            date: date
                        },
                        success: function(data) {
                            let tbody = '';
                            if (data.length > 0) {
                                data.forEach(attendance => {
                                    tbody += `
                        <tr>
                            <td>${attendance.user.name}</td>
                            <td>${attendance.date}</td>
                            <td>${attendance.punch_in}</td>
                            <td>${attendance.punch_out ?? 'Not Punched Out'}</td>
                            <td>${attendance.total_hours ?? 'Pending'}</td>
                        </tr>
                    `;
                                });
                            } else {
                                tbody =
                                    `<tr><td colspan="5" class="text-center">No attendance records found.</td></tr>`;
                            }
                            $('#attendanceTable tbody').html(tbody);
                        },
                        error: function(response) {
                            $('#attendanceTable tbody').html(
                                `<tr><td colspan="5" class="text-center">This is a holyday.</td></tr>`
                            );
                        }
                    });
                }

                fetchAttendance(today);
                $('#attendanceDate').on('change', function() {
                    let selectedDate = $(this).val();
                    fetchAttendance(selectedDate);
                });
            });
        </script>
    @endif

</x-app-layout>
