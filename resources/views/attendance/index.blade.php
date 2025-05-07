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
    <form method="GET" action="{{ route('attendance.monthly') }}" class="mb-3">
        <div class="row">
            <!-- Month Filter -->
            <div class="col-md-4">
                <label for="month">Select Month</label>
                <input type="month" name="month" id="month" class="form-control"
                    value="{{ request('month', now()->format('Y-m')) }}">
            </div>
            @if (request('user_id'))
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
            @endif
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
    <div class="card basic-data-table shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <span class="mb-0">
                <i class="bi bi-calendar3 me-2"></i>Attendance - {{ \Carbon\Carbon::parse($month)->format('F Y') }}
            </span>
        </div>

        <div class="card-body table-responsive p-0" id="scrollable-table">
            <table class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead class="table-light position-sticky top-0 shadow-sm" style="z-index: 1;">
                    <tr>
                        <th class="position-sticky start-0 bg-light fs-6" style="z-index: 2;">User</th>
                        @for ($day = 1; $day <= $totalDays; $day++)
                            @php
                                $dateObj = \Carbon\Carbon::createFromFormat(
                                    'Y-m-d',
                                    $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT),
                                );
                                $dayName = $dateObj->format('D');
                            @endphp
                            <th class="fs-6 @if ($dateObj->isToday()) bg-info text-white @endif">
                                {{ $day }}<br><small>{{ $dayName }}</small></th>
                        @endfor
                    </tr>
                </thead>

                <tbody>
                    @php
                        $icons = [
                            'present' => 'bi-check-circle',
                            'late' => 'bi-clock-history',
                            'half_day' => 'bi-hourglass-split',
                            'early_out' => 'bi-box-arrow-left',
                            'absent' => 'bi-x-circle',
                            'weekend' => 'bi-calendar-week',
                            'pending' => 'bi-question-circle',
                        ];
                    @endphp

                    @foreach ($users as $user)
                        <tr>
                            <td class="position-sticky start-0 fs-6 bg-white text-start fw-semibold text-nowrap"
                                style="z-index: 1;">
                                <i class="bi bi-person-circle me-1 text-primary"></i>{{ $user->name }}
                            </td>

                            @for ($day = 1; $day <= $totalDays; $day++)
                                @php
                                    $date = $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $carbonDate = \Carbon\Carbon::parse($date);
                                    $attendance = $user->attendances->firstWhere('date', $date);
                                    $isHoliday = in_array($date, $holidays) || $carbonDate->isWeekend();
                                    $status =
                                        $attendance->status ??
                                        ($isHoliday
                                            ? 'weekend'
                                            : ($carbonDate->isPast() && !$carbonDate->isToday()
                                                ? 'absent'
                                                : 'pending'));
                                    $statusColor = match ($status) {
                                        'present' => 'success',
                                        'late' => 'warning',
                                        'half_day' => 'info',
                                        'early_out' => 'primary',
                                        'absent' => 'danger',
                                        'weekend' => 'dark',
                                        'pending' => 'secondary',
                                        default => 'secondary',
                                    };
                                @endphp

                                <td class="clickable-attendance fs-6 @if ($carbonDate->isToday()) bg-info-subtle @endif"
                                    data-attendance-id="{{ $attendance->id ?? '' }}">

                                    @if ($attendance && $attendance->status != 'absent')
                                        <div>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-box-arrow-in-right"></i> {{ $attendance->punch_in }}
                                            </span>
                                            <br>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-box-arrow-right"></i>
                                                {{ $attendance->punch_out ?? 'N/A' }}
                                            </span>
                                        </div>
                                    @endif

                                    <span class="badge bg-{{ $statusColor }} mt-1">
                                        <i class="bi {{ $icons[$status] ?? 'bi-question-circle' }}"></i>
                                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                                    </span>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="attendanceNotesDrawer">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Attendance Notes</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div class="input-group add-new-note-wrapper mb-10">
                <input type="text" class="form-control" id="noteInput" placeholder="Add Note...">
                <button class="btn btn-primary" data-attendance-id="{{ $todayAttendanceId }}"
                    id="addNoteButton">Add</button>
            </div>
            <ul class="list-group mb-3" id="notesList">
                <!-- Notes will be loaded here -->
            </ul>
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
    <script>
        let selectedAttendanceId = null;

        $(document).on('click', '.clickable-attendance', function() {
            selectedAttendanceId = $(this).data('attendance-id');

            if (!selectedAttendanceId) return;

            if (selectedAttendanceId != {{ $todayAttendanceId }}) {
                $('.add-new-note-wrapper').hide();
            } else {
                $('.add-new-note-wrapper').show();
            }

            const offcanvas = new bootstrap.Offcanvas(document.getElementById('attendanceNotesDrawer'));
            offcanvas.show();

            $('#notesList').html('<li class="list-group-item text-muted">Loading notes...</li>');
            $('#noteInput').val('');
            $('#addNoteButton').data('attendance-id', selectedAttendanceId);

            $.ajax({
                url: '{{ route('attendance.notes.index', ':attendanceId') }}'.replace(':attendanceId',
                    selectedAttendanceId),
                method: 'GET',
                success: function(res) {
                    let notes = res.notes;
                    if (!notes || notes.length === 0) {
                        $('#notesList').html(
                            '<li class="list-group-item text-muted">No notes yet</li>');
                        return;
                    }
                    let notesHtml = '';
                    notes.forEach(note => {
                        notesHtml += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <strong>${note.user.name}</strong>: ${note.note}<br>
                                <small class="text-muted">${note.created_at}</small>
                            </div>
                        </li>`;
                    });
                    $('#notesList').html(notesHtml);
                },
                error: function() {
                    $('#notesList').html(
                        '<li class="list-group-item text-danger">Failed to load notes</li>');
                }
            });
        });

        $('#addNoteButton').click(function() {
            const note = $('#noteInput').val().trim();
            const attendanceId = $(this).data('attendance-id');
            if (!note || !attendanceId) return;

            $(this).prop('disabled', true).text('Saving...');

            $.ajax({
                url: '{{ route('attendance.notes.store', ':attendanceId') }}'.replace(':attendanceId',
                    attendanceId),
                type: 'POST',
                data: {
                    note: note,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#notesList').append(`
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${response.note.user.name}</strong>: ${response.note.note}<br>
                            <small class="text-muted">${response.note.created_at}</small>
                        </div>
                    </li>
                `);
                    $('#noteInput').val('');
                },
                error: function(err) {
                    alert(err.responseJSON?.message || 'Failed to add note');
                },
                complete: function() {
                    $('#addNoteButton').prop('disabled', false).text('Add Note');
                }
            });
        });
    </script>

</x-app-layout>
