<style>
    .fc-toolbar-title {
        font-size: 16px !important;
    }

    .fc-h-event .fc-event-main {
        color: #000000 !important;
        font-size: 10px !important;
    }

    .show-am-projects-info,
    .show-am-new-sell-projects {
        cursor: pointer;
    }
</style>
<div class="row gy-3 mb-10">
    <div class="col-xxl-3 col-sm-6 show-am-projects-info">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon1.svg') }}" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">Current Portfolio / MO</span>
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
    <div class="col-12 am-projects-info" style="display: none">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0 table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Service</th>
                            <th>Contract Value</th>
                            <th>Contract Start Date</th>
                            <th>Contract Term</th>
                            <th>Monthly Revenue (w/o VAT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects->whereIn('status', ['Current']) as $project)
                            <tr>
                                <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                                </td>
                                <td>{{ $project->project_type }}</td>
                                <td>{{ $project->contract_value }}</td>
                                <td>{{ $project->contract_start_date ?? '' }}</td>
                                <td>{{ $project->contract_term }} /Mo</td>
                                <td>{{ $project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                            <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon2.svg') }}" alt="">
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
                            <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon3.svg') }}" alt="">
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
    <div class="col-xxl-3 col-sm-6 show-am-new-sell-projects">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-4 border border-white">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                    <div class="d-flex align-items-center gap-10">
                        <span
                            class="mb-0 w-48-px h-48-px bg-success-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center rounded-circle h6 mb-0">
                            <img src="{{ asset('/images/home-eleven/icons/home-eleven-icon4.svg') }}" alt="">
                        </span>
                        <div>
                            <span class="fw-medium text-secondary-light text-md">New / Renew / UpSell</span>
                            <h6 class="fw-semibold mt-2">{{ number_format($newSell, 2) }}</h6>
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
    <div class="col-12 am-new-sell-projects" style="display: none">
        <div class="card p-3 shadow-2 radius-8 h-100 gradient-deep-two-1 border border-white">
            <div class="card-body p-0 table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Project</th>
                            <th>Service</th>
                            <th>Contract Value</th>
                            <th>Contract Start Date</th>
                            <th>Contract Term</th>
                            <th>Monthly Revenue (w/o VAT)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($newSellProjects as $project)
                            <tr>
                                <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                                </td>
                                <td>{{ $project->project_type }}</td>
                                <td>{{ $project->contract_value }}</td>
                                <td>{{ $project->contract_start_date ?? '' }}</td>
                                <td>{{ $project->contract_term }} /Mo</td>
                                <td>{{ $project->contract_term > 0 ? round($project->contract_value / $project->contract_term, 2) : 0 ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<x-dashboards.am-calendar />
@if ($statusCounts->count() && $typeCounts->count())
    <div class="border border-secondary card mb-10 overflow-hidden position-relative radius-12">
        <div
            class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
            <span class="fw-bold">Projects Status</span>
        </div>
        <div class="card-body">
            <div class="row row-cols-lg-5 mt-2 gy-4">
                @foreach ($statusCounts as $status => $count)
                    <div class="col mt-1">
                        <div class="card shadow-none border bg-gradient-start-1 h-100">
                            <div class="card-body p-20">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div>
                                        <p class="fw-medium text-primary-light mb-1">{{ $status }}</p>
                                        <h6 class="mb-0">{{ $count }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="border border-secondary card mb-10 overflow-hidden position-relative radius-12">
        <div
            class="card-header flex-wrap gap-1 pt-16 px-24 bg-base border border-end-0 border-start-0 border-top-0 d-flex align-items-center flex-wrap justify-content-between">
            <span class="fw-bold">Projects Types</span>
        </div>
        <div class="card-body">
            <div class="row row-cols-lg-5 mt-2 gy-4">
                @foreach ($typeCounts as $type => $count)
                    <div class="col mt-1">
                        <div class="card shadow-none border bg-gradient-start-2 h-100">
                            <div class="card-body p-20">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div>
                                        <p class="fw-medium text-primary-light mb-1">{{ $type }}</p>
                                        <h6 class="mb-0">{{ $count }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
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
<ul class="nav d-flex align-items-center justify-content-between border-gradient-tab nav-pills mb-0 w-100"
    id="pills-tab" role="tablist">
    <button class="nav-link d-flex align-items-center justify-content-center fs-4 fw-semibold active" id="pending-tab"
        data-bs-toggle="pill" data-bs-target="#pending-task" type="button" role="tab" type="button"
        role="tab" aria-controls="pills-to-do-list" aria-selected="true">
        All Pending Tasks
        <span
            class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $pendingTasks->count() }}</span>
    </button>
    <button class="nav-link d-flex align-items-center justify-content-center fs-4 fw-semibold" id="completed-tab"
        data-bs-toggle="pill" data-bs-target="#completed-task" type="button" role="tab" type="button"
        role="tab" aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
        All Completed Tasks
        <span
            class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $completedTasks->count() }}</span>
    </button>
    <button class="nav-link d-flex align-items-center justify-content-center fs-4 fw-semibold" id="assigned-tab"
        data-bs-toggle="pill" data-bs-target="#assigned-tasks" type="button" role="tab" type="button"
        role="tab" aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
        All Assigned Tasks
        <span
            class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $assignedTasks->count() }}</span>
    </button>
</ul>
<div class="tab-content card p-0 mb-10" id="task-tabContent">
    <!-- PENDING TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane card-body table-responsive fade show active p-0"
        id="pending-task" role="tabpanel">
        @if ($pendingTasks->count())
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
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
                            <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                            </td>
                            <td><x-badge :text="$task->status" type="status" /></td>
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
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane card-body table-responsive fade p-0"
        id="completed-task" role="tabpanel">
        @if ($completedTasks->count())
            <table class="table mb-0 bordered-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Start Date</th>
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
                            <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
                            </td>
                            <td><x-badge :text="$task->status" type="status" /></td>
                            <td>{{ $task->start_date }}</td>
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
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane card-body table-responsive fade p-0"
        id="assigned-tasks" role="tabpanel">
        @if ($assignedTasks->count())
            <table class="table mb-0 bordered-table">
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
                            <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
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
<div class="card basic-data-table">
    <div class="card-header">
        <span class="fw-bold">Projects</span>
    </div>
    <div class="card-body table-responsive">
        <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Contract End Date</th>
                    <th>Monthly Revenue (w/o VAT)</th>
                    <th>Tech</th>
                    <th>Created By</th>
                    <th>Sequence Pending Tasks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a>
                        </td>
                        <td>{{ $project->project_type }}</td>
                        <td>
                            @if ($userPermissions->contains('can update project status'))
                                @if (auth()->user()->hasRole('ADMIN') || ($project->createdBy && $project->createdBy->id == auth()->user()->id))
                                    <select class="form-control project-status" data-project-id="{{ $project->id }}"
                                        required>
                                        <option selected disabled value="">Select status</option>
                                        <option value="Current" {{ $project->status == 'Current' ? 'selected' : '' }}>
                                            Current
                                        </option>
                                        <option value="Paused" {{ $project->status == 'Paused' ? 'selected' : '' }}>
                                            Paused
                                        </option>
                                        <option value="WFF" {{ $project->status == 'WFF' ? 'selected' : '' }}>
                                            WFF
                                        </option>
                                        <option value="Expired" {{ $project->status == 'Expired' ? 'selected' : '' }}>
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
                                    <iconify-icon icon="majesticons:eye-line" class="icon text-xl"></iconify-icon>
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
    $(document).on('click', '.show-am-projects-info', function() {
        $('.am-projects-info').slideToggle();
    })
    $(document).on('click', '.show-am-new-sell-projects', function() {
        $('.am-new-sell-projects').slideToggle();
    })
</script>
