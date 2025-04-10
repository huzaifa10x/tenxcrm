<div class="row gy-3 mb-10">
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
<div class="d-flex align-items-center flex-wrap justify-content-between">
    <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center active" id="pending-tab" data-bs-toggle="pill"
                data-bs-target="#pending-task" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-to-do-list" aria-selected="true">
                Pending Tasks
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $pendingTasks->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="completed-tab" data-bs-toggle="pill"
                data-bs-target="#completed-task" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                Completed Tasks
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $completedTasks->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link d-flex align-items-center" id="assigned-tab" data-bs-toggle="pill"
                data-bs-target="#assigned-tasks" type="button" role="tab" type="button" role="tab"
                aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                Tasks I've Assigned
                <span
                    class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $assignedTasks->count() }}</span>
            </button>
        </li>
    </ul>
    <a href="{{ route('tasks.index') }}"
        class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
        Tasks
        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
    </a>
</div>
<div class="tab-content p-0 card mb-10" id="task-tabContent">
    <!-- PENDING TASKS TAB -->
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade show active p-0 card-body table-responsive"
        id="pending-task" role="tabpanel">
        @if ($pendingTasks->count())
            <table class="table mb-0 bordered-table">
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
                            <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
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
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-0 card-body table-responsive"
        id="completed-task" role="tabpanel">
        @if ($completedTasks->count())
            <table class="table mb-0 bordered-table">
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
                            <td><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a>
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
    <div style="max-height: 500px; overflow-y: auto" class="tab-pane fade p-0 card-body table-responsive"
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No assigned tasks found.</p>
        @endif
    </div>
</div>
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