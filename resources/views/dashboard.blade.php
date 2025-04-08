<x-app-layout>
    <x-slot name="header">
        {{ __($role . ' DASHBOARD') }}
    </x-slot>
    <x-dashboards.punch-in-out />
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
        <x-dashboards.am-dashboard :newSell="$newSell" :typeCounts="$typeCounts" :statusCounts="$statusCounts" :currentPortfolio="$currentPortfolio" :projects="$projects"
            :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role === 'CONTENT')
        <x-dashboards.content-dashboard :pendingTasksCount="$pendingTasksCount" :completedTasksCount="$completedTasksCount" :pendingWords="$pendingWords" :completedWords="$completedWords"
            :pendingTasks="$pendingTasks" :completedTasks="$completedTasks" :assignedTasks="$assignedTasks" />
    @elseif ($role == 'ADMIN')
        <x-dashboards.admin-dashboard />
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
    </script>

</x-app-layout>
