<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    @if ($role === 'AM')
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
            <div class="col-lg-6">
                <div class="card h-100 radius-8 border-0">
                    <span class="mb-3 text-primary">📅 Select a Date</span>
                    <input type="date" id="todoDate" class="form-control mb-3">
                    <div id="todoForm" class="d-none">
                        <div class="input-group">
                            <input type="text" id="todoTask" class="form-control" placeholder="Enter Todo">
                            <button class="btn btn-primary" id="saveTodo">➕ Add</button>
                        </div>
                    </div>
                    <ul id="todoList" class="list-group mt-2"></ul>
                </div>
            </div>
            <div class="col-lg-6">
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
                                            <iconify-icon icon="ri:discount-percent-fill" class="icon"></iconify-icon>
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
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Project Name</th>
                                                <th>Task</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pendingTasks as $task)
                                                <tr>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a href="{{ route('tasks.show', $task->id) }}"
                                                            target="_blank">{{ $task->title }}</a></td>
                                                    <td>{{ $task->status }}</td>
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
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Project Name</th>
                                                <th>Task</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($completedTasks as $task)
                                                <tr>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a href="{{ route('tasks.show', $task->id) }}"
                                                            target="_blank">{{ $task->title }}</a></td>
                                                    <td>{{ $task->status }}</td>
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
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Project Name</th>
                                                <th>Task</th>
                                                <th>Assignee</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($assignedTasks as $task)
                                                <tr>
                                                    <td><a target="_blank"
                                                            href="{{ route('projects.show', $task->project->id) }}">{{ $task->project->title }}</a>
                                                    </td>
                                                    <td><a href="{{ route('tasks.show', $task->id) }}"
                                                            target="_blank">{{ $task->title }}</a></td>
                                                    <td>{{ $task->assignedTo->name }}</td>
                                                    <td>{{ $task->status }}</td>
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
                    <span class="fw-bold">Campaigns</span>
                </div>
            @endif
            <div class="card-body table-responsive">
                <table class="table bordered-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Service Type</th>
                            <th>Status</th>
                            <th>Seo Tech</th>
                            <th>Contract End</th>
                            <th>Monthly Revenue</th>
                            <th>Campaign Tasks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accountProjects as $accountProject)
                            <tr>
                                <td>{{ $accountProject->id }}</td>
                                <td>{{ $accountProject->title }}</td>
                                <td>{{ $accountProject->companies->name }}</td>
                                <td>{{ $accountProject->project_type }}</td>
                                <td>{{ $accountProject->status }}</td>
                                <td>{{ $accountProject->techManagers[0]->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($accountProject->contract_start_date)->addMonths($accountProject->contract_term) }}
                                </td>
                                <td>{{ $accountProject->contract_term > 0 ? round($accountProject->contract_value / $accountProject->contract_term, 2) : 0 }}
                                </td>
                                <td>{{ $accountProject->tasks->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <script>
        $(document).ready(function() {

            function fetchTodos(selectedDate) {
                $('#todoForm').removeClass('d-none');
                $.get("{{ route('todo.getByDate') }}", {
                    date: selectedDate
                }, function(data) {
                    let todoList = $('#todoList');
                    todoList.empty();
                    if (data.length > 0) {
                        $.each(data, function(index, todo) {
                            let statusIcon = todo.status === 'completed' ? '✅' : '⏳';
                            let actionButton = todo.status === 'pending' ?
                                `<button class="btn btn-sm btn-success mark-done" data-id="${todo.id}">✔</button>` :
                                '';

                            todoList.append(`
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>${statusIcon} ${todo.task}</span>
                                ${actionButton}
                            </li>
                        `);
                        });
                    } else {
                        todoList.append(
                            '<li class="list-group-item text-muted">No tasks found for this date.</li>'
                        );
                    }
                });
            }

            let today = new Date().toISOString().split('T')[0];
            fetchTodos(today);
            $('#todoDate').val(today)

            $('#todoDate').on('change', function() {
                let selectedDate = $(this).val();
                fetchTodos(selectedDate)
            });

            $('#saveTodo').on('click', function() {
                let date = $('#todoDate').val();
                let task = $('#todoTask').val();

                if (!task.trim()) {
                    alert("Please enter a task!");
                    return;
                }

                $.post("{{ route('todo.store') }}", {
                    _token: "{{ csrf_token() }}",
                    date: date,
                    task: task
                }, function() {
                    $('#todoTask').val('');
                    $('#todoDate').trigger('change'); // Refresh list
                });
            });

            $(document).on('click', '.mark-done', function() {
                let todoId = $(this).data('id');

                $.post("{{ route('todo.markDone') }}", {
                    _token: "{{ csrf_token() }}",
                    id: todoId
                }, function() {
                    $('#todoDate').trigger('change'); // Refresh list
                });
            });
        });
    </script>
</x-app-layout>
