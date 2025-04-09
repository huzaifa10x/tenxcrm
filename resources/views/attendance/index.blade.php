<x-app-layout>
    <x-slot name="header">
        {{ __('Monthly Attendance Report') }}
    </x-slot>
    <style>
        #scrollable-table {
            overflow-x: auto;
            white-space: nowrap;
            cursor: grab;
            user-select: none;
            max-height: 470px;
            overflow-y: auto;
            padding-bottom: 5px;
        }

        #scrollable-table:active {
            cursor: grabbing;
        }
    </style>
    <!-- Filters -->
    <form method="GET" action="{{ route('attendance.monthly') }}" class="mb-3">
        <div class="row">
            <!-- Month Filter -->
            <div class="col-md-4">
                <label for="month">Select Month</label>
                <input type="month" name="month" id="month" class="form-control"
                    value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            @if (auth()->user()->hasRole('ADMIN'))
                <!-- User Filter -->
                <div class="col-md-4">
                    <label for="user_id">Select User</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">All Users</option>
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    @if (request()->query('user_id'))
        <div class="row gy-3 mb-10">
            <!-- Total Attendance Chart (Donut Chart) -->
            <div class="col-lg-6">
                <div class="card border h-100 p-3">
                    <div id="totalAttendanceChart"></div>
                </div>
            </div>
        </div>
        <script>
            var optionsDonut = {
                series: [
                    {{ $totalPresent }},
                    {{ $totalLate }},
                    {{ $totalAbsent }},
                    {{ $totalHalfDay }},
                    {{ $totalEarlyOut }},
                    {{ $deductions }}
                ],
                chart: {
                    type: 'donut',
                    height: 300
                },
                labels: ['Present', 'Late', 'Absent', 'Half-Day', 'Early Out', 'Deductions'],
                colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#fd7e14', '#ff0000'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: {
                                    show: true
                                },
                                value: {
                                    show: true
                                },

                            }
                        }
                    }
                }
            };
            var chartDonut = new ApexCharts(document.querySelector("#totalAttendanceChart"), optionsDonut);
            chartDonut.render();
        </script>
    @endif
    <!-- Attendance Table -->
    <div class="card basic-data-table">
        <div class="card-body table-responsive p-0" id="scrollable-table">
            <table class="table bordered-table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        @for ($day = 1; $day <= $totalDays; $day++)
                            @php
                                $dateObj = \Carbon\Carbon::createFromFormat(
                                    'Y-m-d',
                                    $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT),
                                );
                                $dayName = $dateObj->format('D'); // Get day name (Mon, Tue, Wed, etc.)
                            @endphp
                            <th>{{ $day }} - {{ $dayName }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="position-sticky start-0">{{ $user->name }}</td>
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
                                        @if ($attendance->status != 'absent')
                                            <span class="badge badge-success text-success">In:
                                                {{ $attendance->punch_in }}</span> <br>
                                            <span class="badge badge-danger text-danger">Out:
                                                {{ $attendance->punch_out ?? 'N/A' }}</span> <br>
                                        @endif
                                        @php
                                            $statusColor = match ($attendance->status) {
                                                'present' => 'success',
                                                'late' => 'warning',
                                                'half_day' => 'info',
                                                'early_out' => 'primary',
                                                'absent' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span
                                            class="badge bg-{{ $statusColor }}">{{ ucfirst($attendance->status) }}</span>
                                    @else
                                        @if ($carbonDate->isPast() && !$carbonDate->isToday())
                                            <span class="badge badge-secondary text-secondary">Absent</span>
                                        @else
                                            <span class="badge badge-warning text-warning">Pending</span>
                                        @endif
                                    @endif
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let tableContainer = document.getElementById("scrollable-table");
            let isDown = false;
            let startX;
            let scrollLeft;

            tableContainer.addEventListener("mousedown", (e) => {
                isDown = true;
                tableContainer.classList.add("active");
                startX = e.pageX - tableContainer.offsetLeft;
                scrollLeft = tableContainer.scrollLeft;
            });

            tableContainer.addEventListener("mouseleave", () => {
                isDown = false;
                tableContainer.classList.remove("active");
            });

            tableContainer.addEventListener("mouseup", () => {
                isDown = false;
                tableContainer.classList.remove("active");
            });

            tableContainer.addEventListener("mousemove", (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - tableContainer.offsetLeft;
                const walk = (x - startX) * 2; // scroll speed
                tableContainer.scrollLeft = scrollLeft - walk;
            });
        });
    </script>
</x-app-layout>
