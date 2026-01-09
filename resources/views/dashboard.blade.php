<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    <x-dashboards.punch-in-out />
    <div class="my-5 row gy-4">
        <!-- Yesterday -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-success text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-day fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed Yesterday</h6>
                            <small class="text-light">({{ \Carbon\Carbon::yesterday()->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedYesterday }}</h3>
                </div>
            </div>
        </div>

        <!-- Last 7 Days -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-success text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-week fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed in Last 7 Days</h6>
                            <small class="text-light">(Since
                                {{ \Carbon\Carbon::now()->subDays(7)->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedLast7Days }}</h3>
                </div>
            </div>
        </div>

        <!-- Last 30 Days -->
        <div class="col-md-4">
            <div class="border-0 shadow-sm card bg-gradient bg-info text-light rounded-4 h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-range fs-3 me-3 text-light"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-light">Tasks Closed in Last 30 Days</h6>
                            <small class="text-light">(Since
                                {{ \Carbon\Carbon::now()->subDays(30)->format('d M Y') }})</small>
                        </div>
                    </div>
                    <h3 class="fw-bold text-light">{{ $tasksClosedLast30Days }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-10 row gy-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="px-20 py-20 card-header office-timing-button">
                    <h6 class="mb-0 mb-2 text-lg fw-bold">Office Timings & Attendance Rules</h6>
                </div>
                <div class="p-0 card-body office-timing-body table-responsive" style="display: none">
                    <table class="table mb-0 bordered-table">
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
                                <td>Number of late arrivals that result in one deduction</td>
                                <td>{{ $late_deduction_threshold }}</td>
                            </tr>
                            <tr>
                                <td>Number of half-days that result in one deduction</td>
                                <td>{{ $half_day_deduction_threshold }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if ($role === 'AM')
        <x-dashboards.am-dashboard :totalRenewals="$totalRenewals" :monthlyStats="$monthlyStats" :monthlyData="$monthlyData" :newSellProjects="$newSellProjects"
            :newSell="$newSell" :typeCounts="$typeCounts" :statusCounts="$statusCounts" :currentPortfolio="$currentPortfolio" :projects="$projects" :pendingTasks="$pendingTasks"
            :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role === 'CONTENT')
        <x-dashboards.content-dashboard :pendingTasksCount="$pendingTasksCount" :completedTasksCount="$completedTasksCount" :pendingWords="$pendingWords" :completedWords="$completedWords"
            :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role == 'ADMIN')
        <div class="row">
            @foreach ($WebsiteProjects as $project)
                <div class="mb-10 col-lg-4 col-md-6 col-sm-12">
                    <div class="shadow-sm card h-100">
                        <div
                            class="p-3 text-white card-header bg-dark d-flex align-items-center justify-content-between">
                            <span class="mb-0 fw-bold">
                                <i class="bi bi-globe2 me-1"></i> {{ $project->project->title }}
                            </span>
                            @if (
                                !$project->websiteProject &&
                                    (auth()->user()->hasRole('ADMIN') || auth()->user()->id == $project->accountManagers->pluck('id')->first()))
                                <a href="{{ route('website-projects.create') }}?project_id={{ $project->id }}"
                                    class="btn btn-sm btn-light text-success">
                                    <i class="bi bi-plus-circle"></i> Add
                                </a>
                            @endif
                        </div>
                        <div class="p-3 card-body">
                            @php $wp = $project; @endphp

                            <div class="flex-wrap gap-2 mb-3 d-flex justify-content-between">
                                <span class="badge bg-secondary">
                                    <i
                                        class="bi bi-{{ $wp->platform === 'WordPress' ? 'wordpress' : 'code-square' }}"></i>
                                    {{ $wp->platform }}
                                </span>
                                <span class="badge bg-warning">
                                    <i class="bi bi-palette"></i> Design: {{ $wp->design_status }}
                                </span>
                                <span class="badge bg-info">
                                    <i class="bi bi-code-slash"></i> Dev: {{ $wp->development_status }}
                                </span>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center small text-muted">
                                @if ($wp->target_go_live_date)
                                    <div class="{{ $wp->isOverdue() ? 'text-danger' : 'text-info' }}">
                                        <i class="bi bi-calendar-event me-1"></i> Target:
                                        {{ $wp->target_go_live_date->format('M d, Y') }}
                                    </div>
                                @endif
                                @if ($wp->developer)
                                    <div>
                                        <i class="bi bi-person-circle me-1"></i> Dev: {{ $wp->developer->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                @if ($wp->go_live_date)
                                    <span class="px-3 py-2 badge bg-success fw-bold">
                                        <i class="bi bi-rocket-takeoff me-1"></i> LIVE
                                    </span>
                                @elseif($wp->approved_for_go_live)
                                    <span class="px-3 py-2 badge bg-warning fw-bold">
                                        <i class="bi bi-hourglass-split me-1"></i> Ready to Launch
                                    </span>
                                @else
                                    <span class="px-3 py-2 badge bg-secondary fw-bold">
                                        <i class="bi bi-clock-history me-1"></i> In Progress
                                    </span>
                                @endif

                                <a href="{{ route('website-projects.show', $wp->id) }}"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-eye"></i> Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card">
            <div class="card-header">
                <span class="fw-bold">Project Cancellation Request</span>
            </div>
            <div class="card-body">
                <table class="table align-middle table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"><i class="bi bi-hash"></i> #</th>
                            <th scope="col"><i class="bi bi-folder2-open"></i> Project</th>
                            <th scope="col"><i class="bi bi-chat-left-text"></i> Reason</th>
                            <th scope="col"><i class="bi bi-tools"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingCancellations as $index => $cancel)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cancel->project->title }}</td>
                                <td>
                                    <button class="btn btn-outline-info btn-sm view-reason-btn" data-bs-toggle="modal"
                                        data-bs-target="#reasonModal" data-project="{{ $cancel->project->title }}"
                                        data-url="{{ route('projects.show', $cancel->project->id) }}"
                                        data-reason="{{ $cancel->reason }}">
                                        <i class="bi bi-eye"></i> View Reason
                                    </button>
                                </td>
                                <td>
                                    <form action="{{ route('admin.approveCancellation', $cancel->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-success btn-sm">
                                            <i class="bi bi-check2-circle"></i> Approve
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No pending cancellations.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Reason Modal -->
        <div class="modal fade" id="reasonModal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="shadow modal-content">
                    <div class="text-white modal-header bg-info">
                        <span class="modal-title" id="reasonModalLabel">
                            <i class="bi bi-exclamation-circle me-1"></i> Cancellation Reason
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <a class="fw-bold fs-7 text-dark project-title"></a>
                        <p class="mt-2 text-secondary reason-text"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <x-dashboards.admin-dashboard />
        <button class="mt-24 btn btn-warning show-partner-links-info">Show Partner Links Data</button>
        @foreach ($partnerLinksByAM as $amData)
            <div class="mt-4 card partner-links-info" style="display: none">
                <div class="text-white card-header bg-success">
                    <i class="bi bi-person-badge me-1"></i> {{ $amData['am_name'] }}
                </div>
                <div class="p-0 card-body">
                    <table class="table mb-0 table-bordered table-striped small">
                        <thead class="text-center table-light">
                            <tr>
                                <th>Month</th>
                                <th>Total Links</th>
                                <th>Total Amount (Rs.)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
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

        <div class="mt-6 card">
            <div class="text-white card-header bg-success">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-light">
                        <i class="bi bi-pencil-square me-2"></i>Content Writers Performance
                    </h5>
                    <a href="{{ route('admin.content-writers.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-bar-chart-line me-1"></i>View All Writers
                    </a>
                </div>
            </div>
            <div class="card-body">
                @php
                    $topWriters = \App\Models\User::whereHas('roles', function ($query) {
                        $query->where('name', 'CONTENT');
                    })
                        ->withCount([
                            'assignedTasks as completed_this_month' => function ($query) {
                                $query
                                    ->where('status', 'Completed')
                                    ->whereMonth('completed_date', now()->month)
                                    ->whereYear('completed_date', now()->year);
                            },
                        ])
                        ->orderBy('completed_this_month', 'desc')
                        ->take(5)
                        ->get();
                @endphp

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
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
                                        <div class="d-flex align-items-center">
                                            <div
                                                class="text-white avatar-sm bg-success rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($writer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $writer->name }}</div>
                                                <small class="text-muted">{{ $writer->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $writer->completed_this_month }}
                                            completed</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.content-writers.show', $writer->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <style>
            .avatar-sm {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        </style>
    @endif
    <div class="mt-10 mb-10 row gy-3">
        <x-dashboards.task-counts :taskPercentages="$taskPercentages" :pendingTasksCountGlobal="$pendingTasksCountGlobal" :completedTasksCountGlobal="$completedTasksCountGlobal" :createdTasksCountGlobal="$createdTasksCountGlobal"
            :collaboratorTasksCountGlobal="$collaboratorTasksCountGlobal" />
        <x-dashboards.latest-notifications :latestNotifications="$latestNotifications" />
    </div>
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
        $(document).on('click', '.view-reason-btn', function() {
            let reason = $(this).data('reason');
            let project = $(this).data('project');
            let projectUrl = $(this).data('url');
            $('.project-title').attr('href', projectUrl);
            $('.project-title').text(project);
            $('.reason-text').text(reason);
        });
        $(document).on('click', '.office-timing-button', function() {
            $('.office-timing-body').slideToggle();
        });
        $(document).on('click', '.show-partner-links-info', function() {
            $('.partner-links-info').slideToggle();
        });
    </script>

</x-app-layout>
