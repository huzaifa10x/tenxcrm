<x-app-layout>
    <x-slot name="header">
        {{ __('Monthly Attendance Report') }}
    </x-slot>
    @if (auth()->user()->hasRole('ADMIN'))
        <div class="container">
            <!-- Filters -->
            <!-- Filters -->
            <form method="GET" action="{{ route('attendance.monthly') }}" class="mb-3">
                <div class="row">
                    <!-- Month Filter -->
                    <div class="col-md-4">
                        <label for="month">Select Month</label>
                        <input type="month" name="month" id="month" class="form-control"
                            value="{{ request('month', now()->format('Y-m')) }}">
                    </div>

                    <!-- User Filter -->
                    <div class="col-md-4">
                        <label for="user_id">Select User</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">All Users</option>
                            @foreach (\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}"
                                    {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            @if (request()->query('user_id'))
                <div class="row gy-3 mb-10">
                    <div class="col-lg-6">
                        <div class="card border">
                            <div id="attendanceChart"></div>
                        </div>
                    </div>
                </div>
                <script>
                    var options = {
                        series: [{{ $totalPresent }}, {{ $totalLate }}, {{ $totalAbsent }}],
                        chart: {
                            type: 'donut',
                            height: 350
                        },
                        labels: ['Present Days', 'Late Arrivals', 'Absent Days'],
                        colors: ['#28a745', '#ffc107', '#dc3545'],
                        responsive: [{
                            breakpoint: 480,
                            options: {
                                chart: {
                                    width: 300
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }]
                    };

                    var chart = new ApexCharts(document.querySelector("#attendanceChart"), options);
                    chart.render();
                </script>
            @endif

            <!-- Attendance Table -->
            <div class="card basic-data-table">
                <div class="card-body table-responsive p-0">
                    <table class="table bordered-table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                @for ($day = 1; $day <= $totalDays; $day++)
                                    <th>{{ $day }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    @for ($day = 1; $day <= $totalDays; $day++)
                                        @php
                                            $date = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                            $carbonDate = \Carbon\Carbon::parse($date);
                                            $attendance = $user->attendances->firstWhere('date', $date);
                                            $isHoliday = in_array($date, $holidays) || $carbonDate->isWeekend();
                                        @endphp
                                        <td class="text-center">
                                            @if ($isHoliday)
                                                <span class="badge badge-success text-success">Weekend</span>
                                            @elseif ($attendance)
                                                <span class="badge badge-success text-success">In:
                                                    {{ $attendance->punch_in }}</span> <br>
                                                <span class="badge badge-danger text-danger">Out:
                                                    {{ $attendance->punch_out ?? 'N/A' }}</span>
                                            @else
                                                <span class="badge badge-secondary text-secondary">Absent</span>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        @include('vendor.not-allowed')
    @endif
</x-app-layout>
