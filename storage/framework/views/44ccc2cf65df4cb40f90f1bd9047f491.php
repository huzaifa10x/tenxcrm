<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <?php echo e(__('Monthly Attendance Report')); ?>

     <?php $__env->endSlot(); ?>
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
    <form method="GET" action="<?php echo e(route('attendance.monthly')); ?>" class="mb-3">
        <div class="row">
            <!-- Month Filter -->
            <div class="col-md-4">
                <label for="month">Select Month</label>
                <input type="month" name="month" id="month" class="form-control"
                    value="<?php echo e(request('month', now()->format('Y-m'))); ?>">
            </div>
            <?php if(request('user_id')): ?>
                <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>">
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('ADMIN') || auth()->user()->hasRole('Accountant')): ?>
                <div class="col-md-4">
                    <label for="user_id">Select User</label>
                    <select name="user_id" id="user_id" class="form-control">
                        <option value="">All Users</option>
                        <?php $__currentLoopData = \App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>
    <?php if(request()->query('user_id')): ?>
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
                    <?php echo e($totalPresent); ?>,
                    <?php echo e($totalLate); ?>,
                    <?php echo e($totalAbsent); ?>,
                    <?php echo e($totalHalfDay); ?>,
                    <?php echo e($totalEarlyOut); ?>,
                    <?php echo e($deductions); ?>

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
    <?php endif; ?>
    <div class="card basic-data-table shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <span class="mb-0">
                <i class="bi bi-calendar3 me-2"></i>Attendance - <?php echo e(\Carbon\Carbon::parse($month)->format('F Y')); ?>

            </span>
        </div>

        <div class="card-body table-responsive p-0" id="scrollable-table">
            <table class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead class="table-light position-sticky top-0 shadow-sm" style="z-index: 1;">
                    <tr>
                        <th class="position-sticky start-0 bg-light fs-6" style="z-index: 2;">User</th>
                        <?php for($day = 1; $day <= $totalDays; $day++): ?>
                            <?php
                                $dateObj = \Carbon\Carbon::createFromFormat(
                                    'Y-m-d',
                                    $month . '-' . str_pad($day, 2, '0', STR_PAD_LEFT),
                                );
                                $dayName = $dateObj->format('D');
                            ?>
                            <th class="fs-6 <?php if($dateObj->isToday()): ?> bg-info text-white <?php endif; ?>">
                                <?php echo e($day); ?><br><small><?php echo e($dayName); ?></small></th>
                        <?php endfor; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $icons = [
                            'present' => 'bi-check-circle',
                            'late' => 'bi-clock-history',
                            'half_day' => 'bi-hourglass-split',
                            'early_out' => 'bi-box-arrow-left',
                            'absent' => 'bi-x-circle',
                            'weekend' => 'bi-calendar-week',
                            'pending' => 'bi-question-circle',
                            'leave' => 'bi-airplane',
                            'holiday' => 'bi-stars',
                        ];
                    ?>

                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="position-sticky start-0 fs-6 bg-white text-start fw-semibold text-nowrap"
                                style="z-index: 1;">
                                <i class="bi bi-person-circle me-1 text-primary"></i><?php echo e($user->name); ?>

                            </td>

                            <?php for($day = 1; $day <= $totalDays; $day++): ?>
                                <?php
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
                                        'leave' => 'secondary',
                                        'holiday' => 'light',
                                        'pending' => 'secondary',
                                        default => 'secondary',
                                    };
                                ?>

                                <td class="clickable-attendance fs-6 <?php if($carbonDate->isToday()): ?> bg-info-subtle <?php endif; ?>"
                                    data-attendance-id="<?php echo e($attendance->id ?? ''); ?>">

                                    <?php if($attendance && $attendance->status != 'absent'): ?>
                                        <div>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-box-arrow-in-right"></i> <?php echo e($attendance->punch_in); ?>

                                            </span>
                                            <br>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-box-arrow-right"></i>
                                                <?php echo e($attendance->punch_out ?? 'N/A'); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>

                                    <span class="badge bg-<?php echo e($statusColor); ?> mt-1">
                                        <i class="bi <?php echo e($icons[$status] ?? 'bi-question-circle'); ?>"></i>
                                        <?php echo e(ucfirst(str_replace('_', ' ', $status))); ?>

                                    </span>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <button class="btn btn-primary" data-attendance-id="<?php echo e($todayAttendanceId); ?>"
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

            if (selectedAttendanceId != <?php echo e($todayAttendanceId); ?>) {
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
                url: '<?php echo e(route('attendance.notes.index', ':attendanceId')); ?>'.replace(':attendanceId',
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
                url: '<?php echo e(route('attendance.notes.store', ':attendanceId')); ?>'.replace(':attendanceId',
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/index.blade.php ENDPATH**/ ?>