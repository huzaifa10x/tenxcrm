
<?php $T = $totals; ?>
<div class="xa">
    <div class="xa-sub mb-2"><?php echo e($from->format('d M Y')); ?> – <?php echo e($to->format('d M Y')); ?> · <?php echo e($T['working_days']); ?> working days (weekends &amp; holidays excluded)</div>
    <div class="xa-stats">
        <div class="xa-stat is-good"><div class="xa-stat-label">Attendance</div><div class="xa-stat-value"><?php echo e($T['attendance_pct'] !== null ? $T['attendance_pct'] . '%' : '—'); ?></div><div class="xa-stat-hint"><?php echo e($T['employees']); ?> employees</div></div>
        <div class="xa-stat <?php echo e($T['absent'] ? 'is-bad' : 'is-muted'); ?>"><div class="xa-stat-label">Absences</div><div class="xa-stat-value"><?php echo e($T['absent']); ?></div></div>
        <div class="xa-stat <?php echo e($T['late'] ? 'is-warn' : 'is-muted'); ?>"><div class="xa-stat-label">Late</div><div class="xa-stat-value"><?php echo e($T['late']); ?> <small>· <?php echo e($T['late_minutes']); ?> min</small></div></div>
        <div class="xa-stat <?php echo e($T['half_days'] ? 'is-warn' : 'is-muted'); ?>"><div class="xa-stat-label">Half days</div><div class="xa-stat-value"><?php echo e($T['half_days']); ?></div></div>
        <div class="xa-stat is-info"><div class="xa-stat-label">Leave days</div><div class="xa-stat-value"><?php echo e($T['leave_days']); ?></div></div>
        <div class="xa-stat <?php echo e($T['missed_punch_outs'] ? 'is-bad' : 'is-muted'); ?>"><div class="xa-stat-label">Missed punch-outs</div><div class="xa-stat-value"><?php echo e($T['missed_punch_outs']); ?></div></div>
        <div class="xa-stat is-good"><div class="xa-stat-label">Avg hours</div><div class="xa-stat-value"><?php echo e($T['avg_minutes'] !== null ? number_format($T['avg_minutes'] / 60, 1) . 'h' : '—'); ?></div></div>
        <?php if($pendingLeaves !== null): ?>
            <a class="xa-stat <?php echo e($pendingLeaves ? 'is-warn' : 'is-muted'); ?>" href="<?php echo e(route('leaves.admin')); ?>" style="text-decoration:none"><div class="xa-stat-label">Pending leave</div><div class="xa-stat-value"><?php echo e($pendingLeaves); ?></div><div class="xa-stat-hint">Review →</div></a>
        <?php endif; ?>
    </div>
    <div class="xa-card">
        <?php echo $__env->make('attendance.partials.performance-table', ['rows' => $rows, 'compact' => true, 'periodQuery' => ['from' => $from->toDateString(), 'to' => $to->toDateString()]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/partials/dashboard-performance.blade.php ENDPATH**/ ?>