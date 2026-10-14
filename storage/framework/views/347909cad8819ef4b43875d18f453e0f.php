
<?php
    $__q = empty($periodQuery) ? ['from' => $from->toDateString(), 'to' => $to->toDateString()] : $periodQuery;
    $__pctClass = fn ($p) => $p === null ? '' : ($p >= 90 ? '' : ($p >= 75 ? 'is-warn' : 'is-bad'));
    $__compact = $compact ?? false;
?>
<div class="xa-table-wrap" <?php if($__compact): ?> style="max-height:520px" <?php endif; ?>>
    <table class="xa-table" data-perf-table>
        <thead>
            <tr>
                <th class="xa-sortable">Employee</th>
                <th class="xa-sortable num" title="Working days in the period (weekends and holidays excluded)">Work days</th>
                <th class="xa-sortable num">Present</th>
                <th class="xa-sortable num">Absent</th>
                <th class="xa-sortable num">Late</th>
                <th class="xa-sortable num" title="Total minutes late">Late min</th>
                <th class="xa-sortable num">Half days</th>
                <th class="xa-sortable num" title="Approved leave days">Leave</th>
                <th class="xa-sortable num" title="Average hours on days with a punch-in and punch-out">Avg hours</th>
                <th class="xa-sortable num" title="Missed punch-outs">Missed out</th>
                <th class="xa-sortable" title="(Present − ½ half days) ÷ (elapsed working days − leave)">Attendance</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $s = $r['summary']; $u = $r['user']; ?>
                <tr class="is-clickable" data-search="<?php echo e(strtolower($u->name . ' ' . $u->email . ' ' . $u->departmentNames())); ?>"
                    data-dept="<?php echo e($u->roles->pluck('id')->implode(',')); ?>"
                    onclick="location.href='<?php echo e(route('attendance.index', $__q + ['user_id' => $u->id])); ?>'">
                    <td data-sort="<?php echo e(strtolower($u->name)); ?>">
                        <a class="xa-person" href="<?php echo e(route('attendance.index', $__q + ['user_id' => $u->id])); ?>">
                            <?php echo $__env->make('attendance.partials.avatar', ['user' => $u], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <span style="min-width:0">
                                <span class="xa-person-name d-block"><?php echo e($u->name); ?></span>
                                <span class="xa-person-meta d-block"><?php echo e($u->departmentNames() ?: '—'); ?></span>
                            </span>
                        </a>
                    </td>
                    <td class="num" data-sort="<?php echo e($s['working_days']); ?>"><?php echo e($s['working_days']); ?></td>
                    <td class="num" data-sort="<?php echo e($s['present']); ?>"><?php echo e($s['present']); ?></td>
                    <td class="num" data-sort="<?php echo e($s['absent']); ?>"><?php echo $s['absent'] ? '<span class="xa-badge xa-badge-absent no-dot">' . $s['absent'] . '</span>' : '<span class="xa-dim">0</span>'; ?></td>
                    <td class="num" data-sort="<?php echo e($s['late']); ?>"><?php echo $s['late'] ? '<span class="xa-badge xa-badge-late no-dot">' . $s['late'] . '</span>' : '<span class="xa-dim">0</span>'; ?></td>
                    <td class="num" data-sort="<?php echo e($s['late_minutes']); ?>"><?php echo e($s['late_minutes'] ?: '—'); ?></td>
                    <td class="num" data-sort="<?php echo e($s['half_days']); ?>"><?php echo e($s['half_days'] ?: '—'); ?></td>
                    <td class="num" data-sort="<?php echo e($s['leave_days']); ?>"><?php echo e($s['leave_days'] ?: '—'); ?></td>
                    <td class="num" data-sort="<?php echo e($s['avg_minutes'] ?? -1); ?>"><?php echo e($s['avg_minutes'] !== null ? number_format($s['avg_minutes'] / 60, 1) . 'h' : '—'); ?></td>
                    <td class="num" data-sort="<?php echo e($s['missed_punch_outs']); ?>"><?php echo $s['missed_punch_outs'] ? '<span class="xa-flag xa-flag-missed">' . $s['missed_punch_outs'] . '</span>' : '<span class="xa-dim">0</span>'; ?></td>
                    <td data-sort="<?php echo e($s['attendance_pct'] ?? -1); ?>">
                        <?php if($s['attendance_pct'] !== null): ?>
                            <div class="xa-pct <?php echo e($__pctClass($s['attendance_pct'])); ?>">
                                <div class="xa-pct-bar"><span style="width: <?php echo e(min(100, $s['attendance_pct'])); ?>%"></span></div>
                                <span class="xa-pct-val"><?php echo e($s['attendance_pct']); ?>%</span>
                            </div>
                        <?php else: ?>
                            <span class="xa-dim">No days yet</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr class="xa-no-sort"><td colspan="11"><div class="xa-empty"><iconify-icon icon="solar:users-group-rounded-linear"></iconify-icon>No employees match these filters.</div></td></tr>
            <?php endif; ?>
            <tr class="xa-no-sort" data-perf-empty hidden><td colspan="11"><div class="xa-empty">No employees match your search.</div></td></tr>
        </tbody>
    </table>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/partials/performance-table.blade.php ENDPATH**/ ?>