<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    <x-dashboards.punch-in-out />
    <div class="row gy-4 my-5">
        <!-- Yesterday -->
        <div class="col-md-4">
            <div class="card bg-gradient bg-success text-light border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
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
            <div class="card bg-gradient bg-primary text-light border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
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
            <div class="card bg-gradient bg-info text-light border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
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
    <div class="row gy-3 mb-10">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header office-timing-button px-20 py-20">
                    <h6 class="mb-2 fw-bold text-lg mb-0">Office Timings & Attendance Rules</h6>
                </div>
                <div class="card-body office-timing-body table-responsive p-0" style="display: none">
                    <table class="table bordered-table mb-0">
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
        <x-dashboards.am-dashboard :monthlyStats="$monthlyStats" :monthlyData="$monthlyData" :newSellProjects="$newSellProjects" :newSell="$newSell"
            :typeCounts="$typeCounts" :statusCounts="$statusCounts" :currentPortfolio="$currentPortfolio" :projects="$projects" :pendingTasks="$pendingTasks" :completedTasks="$completedTasks"
            :assignedTasks="$assignedTasks" />
    @elseif ($role === 'CONTENT')
        <x-dashboards.content-dashboard :pendingTasksCount="$pendingTasksCount" :completedTasksCount="$completedTasksCount" :pendingWords="$pendingWords" :completedWords="$completedWords"
            :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role == 'ADMIN')
        <x-dashboards.admin-dashboard />
        <button class="btn btn-warning show-partner-links-info">Show Partner Links Data</button>
        @foreach ($partnerLinksByAM as $amData)
            <div class="card mt-4 partner-links-info" style="display: none">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person-badge me-1"></i> {{ $amData['am_name'] }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0 small">
                        <thead class="table-light text-center">
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
    @endif
    <div class="row gy-3 mb-10 mt-10">
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
        $(document).on('click', '.office-timing-button', function() {
            $('.office-timing-body').slideToggle();
        });
        $(document).on('click', '.show-partner-links-info', function() {
            $('.partner-links-info').slideToggle();
        });
    </script>

</x-app-layout>
