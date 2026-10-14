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
     <?php $__env->slot('header', null, []); ?> Team attendance <?php $__env->endSlot(); ?>
    <?php echo $__env->make('attendance.partials.assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $T = $totals;
        $isMonth = empty($filters['from']);
        $monthC = \Carbon\Carbon::parse($month . '-01');
        $periodQuery = $isMonth ? ['month' => $month] : ['from' => $from->toDateString(), 'to' => $to->toDateString()];
        $keep = array_filter(['department' => $filters['department'] ?? null, 'q' => $filters['q'] ?? null, 'status' => $filters['status'] ?? null]);
        $boardCols = [
            'in' => ['On time', 'present'],
            'late' => ['Late / half day', 'late'],
            'out' => ['Punched out', 'early_out'],
            'leave' => ['On leave', 'leave'],
            'missing' => ['Not in yet', 'absent'],
        ];
    ?>

    <div class="xa">
        <?php echo $__env->make('attendance.partials.tabs', ['active' => 'team'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Team attendance</h1>
                <div class="xa-sub">
                    <?php echo e($isMonth ? $monthC->format('F Y') : $from->format('d M Y') . ' – ' . $to->format('d M Y')); ?>

                    · <?php echo e($T['working_days']); ?> working days (weekends &amp; holidays excluded)
                </div>
            </div>
            <div class="xa-actions">
                <a class="xa-btn xa-btn-sm" href="<?php echo e(route('attendance.index', $keep + ['month' => $monthC->copy()->subMonth()->format('Y-m')])); ?>" aria-label="Previous month">&lsaquo;</a>
                <input type="month" class="xa-input" style="width:auto" value="<?php echo e($month); ?>" aria-label="Month"
                    onchange="if(this.value){const u=new URL('<?php echo e(route('attendance.index', $keep)); ?>');u.searchParams.set('month',this.value);location.href=u}">
                <a class="xa-btn xa-btn-sm" href="<?php echo e(route('attendance.index', $keep + ['month' => $monthC->copy()->addMonth()->format('Y-m')])); ?>" aria-label="Next month">&rsaquo;</a>
                <a class="xa-btn xa-btn-sm" href="<?php echo e(route('attendance.export', $periodQuery + $keep)); ?>"><iconify-icon icon="solar:download-linear"></iconify-icon> CSV</a>
                <a class="xa-btn xa-btn-sm" href="<?php echo e(route('attendance.monthly', ['view' => 'classic', 'month' => $month])); ?>" title="The previous month grid">Classic grid</a>
                <button type="button" class="xa-btn xa-btn-dark" data-add-record><iconify-icon icon="solar:add-circle-linear"></iconify-icon> Add record</button>
            </div>
        </div>

        <?php if($pendingLeaves): ?>
            <div class="xa-callout is-warn mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span><strong><?php echo e($pendingLeaves); ?></strong> leave <?php echo e(Str::plural('request', $pendingLeaves)); ?> waiting for review.</span>
                <a href="<?php echo e(route('leaves.admin')); ?>" class="xa-btn xa-btn-sm">Review now</a>
            </div>
        <?php endif; ?>

        <div class="xa-stats">
            <div class="xa-stat is-good"><div class="xa-stat-label">Team attendance</div><div class="xa-stat-value"><?php echo e($T['attendance_pct'] !== null ? $T['attendance_pct'] . '%' : '—'); ?></div><div class="xa-stat-hint">Average across <?php echo e($T['employees']); ?> people</div></div>
            <div class="xa-stat is-good"><div class="xa-stat-label">Present days</div><div class="xa-stat-value"><?php echo e($T['present']); ?></div><div class="xa-stat-hint">Incl. <?php echo e($T['half_days']); ?> half days</div></div>
            <div class="xa-stat <?php echo e($T['absent'] ? 'is-bad' : 'is-muted'); ?>"><div class="xa-stat-label">Absences</div><div class="xa-stat-value"><?php echo e($T['absent']); ?></div><div class="xa-stat-hint">Without approved leave</div></div>
            <div class="xa-stat <?php echo e($T['late'] ? 'is-warn' : 'is-muted'); ?>"><div class="xa-stat-label">Late arrivals</div><div class="xa-stat-value"><?php echo e($T['late']); ?></div><div class="xa-stat-hint"><?php echo e(\App\Services\Attendance\AttendancePolicy::formatMinutes($T['late_minutes'])); ?> in total</div></div>
            <div class="xa-stat is-info"><div class="xa-stat-label">Leave days</div><div class="xa-stat-value"><?php echo e($T['leave_days']); ?></div><div class="xa-stat-hint">Approved</div></div>
            <div class="xa-stat <?php echo e($T['missed_punch_outs'] ? 'is-bad' : 'is-muted'); ?>"><div class="xa-stat-label">Missed punch-outs</div><div class="xa-stat-value"><?php echo e($T['missed_punch_outs']); ?></div><div class="xa-stat-hint">Need a correction?</div></div>
            <div class="xa-stat is-good"><div class="xa-stat-label">Avg hours / day</div><div class="xa-stat-value"><?php echo e($T['avg_minutes'] !== null ? number_format($T['avg_minutes'] / 60, 1) . 'h' : '—'); ?></div><div class="xa-stat-hint">Scheduled <?php echo e(number_format($policy->scheduledMinutes() / 60, 1)); ?>h</div></div>
        </div>

        <div class="xa-grid xa-grid-main mb-3">
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">Today · <?php echo e(now()->format('l d M')); ?></h2>
                    <?php if (! ($todayBoard['working'])): ?><span class="xa-badge xa-badge-weekend">Not a working day</span><?php endif; ?>
                </div>
                <div class="xa-card-body">
                    <div class="xa-board">
                        <?php $__currentLoopData = $boardCols; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $badge]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="xa-board-col">
                                <div class="xa-board-head"><span><?php echo e($label); ?></span><span class="xa-badge xa-badge-<?php echo e($badge); ?> no-dot"><?php echo e(count($todayBoard['groups'][$key])); ?></span></div>
                                <div class="xa-board-list">
                                    <?php $__empty_1 = true; $__currentLoopData = $todayBoard['groups'][$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <a class="xa-chip" href="<?php echo e(route('attendance.index', ['user_id' => $item['user']->id])); ?>">
                                            <span><?php echo e($item['user']->name); ?></span><span><?php echo e($item['note']); ?></span>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <div class="xa-dim" style="font-size:.76rem">—</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="xa-card">
                <div class="xa-card-head"><h2 class="xa-card-title">Daily trend</h2></div>
                <div class="xa-card-body"><div id="xaTeamTrend" class="xa-chart"></div></div>
            </div>
        </div>

        <div class="xa-card">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Employee performance</h2>
                <span class="xa-card-note">Click a person for their day-by-day history</span>
            </div>
            <form class="xa-filters" method="GET" action="<?php echo e(route('attendance.index')); ?>">
                <div class="xa-field" style="flex:1;min-width:180px">
                    <label for="tQ">Search</label>
                    <div class="xa-search">
                        <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                        <input type="search" id="tQ" name="q" class="xa-input" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Name, email or department" autocomplete="off">
                    </div>
                </div>
                <div class="xa-field">
                    <label for="tDept">Department</label>
                    <select id="tDept" name="department" class="xa-select">
                        <option value="">All departments</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>" <?php if(($filters['department'] ?? '') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="xa-field">
                    <label for="tEmp">Employee</label>
                    <select id="tEmp" name="user_id" class="xa-select">
                        <option value="">Everyone</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($e->id); ?>"><?php echo e($e->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="xa-field">
                    <label for="tFrom">From</label>
                    <input type="date" id="tFrom" name="from" class="xa-input" value="<?php echo e($from->toDateString()); ?>">
                </div>
                <div class="xa-field">
                    <label for="tTo">To</label>
                    <input type="date" id="tTo" name="to" class="xa-input" value="<?php echo e($to->toDateString()); ?>">
                </div>
                <div class="xa-field">
                    <label for="tStatus">Show</label>
                    <select id="tStatus" name="status" class="xa-select">
                        <option value="">Everyone</option>
                        <option value="issues" <?php if(($filters['status'] ?? '') === 'issues'): echo 'selected'; endif; ?>>Only with issues</option>
                    </select>
                </div>
                <button class="xa-btn xa-btn-dark" type="submit">Apply</button>
                <?php if(array_filter($filters)): ?>
                    <a class="xa-btn" href="<?php echo e(route('attendance.index')); ?>">Reset</a>
                <?php endif; ?>
            </form>
            <?php echo $__env->make('attendance.partials.performance-table', ['rows' => $rows, 'periodQuery' => $periodQuery], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php echo $__env->make('attendance.partials.record-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.querySelector('[data-perf-table]');
        XA.sortable(table);
        XA.liveFilter(document.getElementById('tQ'), () => table.querySelectorAll('tbody tr[data-search]'), table.querySelector('[data-perf-empty]'));

        const trend = <?php echo json_encode($trend, 15, 512) ?>;
        if (window.ApexCharts && trend.length) {
            new ApexCharts(document.getElementById('xaTeamTrend'), {
                chart: { type: 'bar', height: 250, stacked: true, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [
                    { name: 'On time', data: trend.map(d => d.present) },
                    { name: 'Late', data: trend.map(d => d.late) },
                    { name: 'Leave', data: trend.map(d => d.leave) },
                    { name: 'Absent', data: trend.map(d => d.absent) },
                ],
                xaxis: { categories: trend.map(d => d.label), labels: { rotate: -45, style: { fontSize: '10px' } } },
                colors: ['#a9e324', '#f0a400', '#3b82f6', '#e5484d'],
                plotOptions: { bar: { borderRadius: 3, columnWidth: '60%' } },
                dataLabels: { enabled: false },
                legend: { position: 'top', horizontalAlign: 'left' },
                grid: { borderColor: '#eef1ed' },
                yaxis: { labels: { formatter: v => Math.round(v) } },
            }).render();
        } else if (document.getElementById('xaTeamTrend')) {
            document.getElementById('xaTeamTrend').innerHTML = '<div class="xa-empty">No working days yet in this period.</div>';
        }
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/team.blade.php ENDPATH**/ ?>