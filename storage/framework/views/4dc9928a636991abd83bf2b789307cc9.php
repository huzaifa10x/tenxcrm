<?php
    $__me = auth()->user();
    $__pending = \App\Support\Access::approveLeaves($__me) ? \App\Models\LeaveRequest::where('status', 'pending')->count() : 0;
    $__tabs = [['My attendance', route('attendance.index', \App\Support\Access::manageAttendance($__me) ? ['user_id' => $__me->id] : []), 'my-attendance', null]];
    if (\App\Support\Access::manageAttendance($__me)) {
        $__tabs[] = ['Team attendance', route('attendance.index'), 'team', null];
    }
    $__tabs[] = ['My leave', route('leaves.index'), 'my-leave', null];
    if (\App\Support\Access::approveLeaves($__me)) {
        $__tabs[] = ['Leave requests', route('leaves.admin'), 'leave-admin', $__pending];
    }
    if (\App\Support\Access::manageLeavePolicies($__me)) {
        $__tabs[] = ['Leave policies', route('leaves.policies'), 'policies', null];
    }
    if ($__me->hasRole('ADMIN')) {
        $__tabs[] = ['Attendance settings', route('settings.index'), 'settings', null];
    }
?>
<nav class="xa-tabs" aria-label="Attendance and leave">
    <?php $__currentLoopData = $__tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $url, $key, $count]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($url); ?>" class="xa-tab <?php echo e(($active ?? '') === $key ? 'active' : ''); ?>" <?php if(($active ?? '') === $key): ?> aria-current="page" <?php endif; ?>>
            <?php echo e($label); ?><?php if($count): ?><span class="xa-count is-hot"><?php echo e($count); ?></span><?php endif; ?>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</nav>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/attendance/partials/tabs.blade.php ENDPATH**/ ?>