<section id="email-notifications">
    <header class="mb-4">
        <h2 class="h5 text-dark"><?php echo e(__('Email Notifications')); ?></h2>
        <p class="text-muted small">
            <?php echo e(__('Choose which optional emails you receive. Task assignment, completion, overdue and renewal emails are always sent.')); ?>

        </p>
    </header>

    <form method="post" action="<?php echo e(route('profile.notifications.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>

        <?php $__currentLoopData = \App\Models\User::OPTIONAL_EMAILS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="form-check form-switch mb-3">
                <input type="hidden" name="notifications[<?php echo e($key); ?>]" value="0">
                <input class="form-check-input" type="checkbox" role="switch" value="1"
                       id="notify_<?php echo e($key); ?>" name="notifications[<?php echo e($key); ?>]"
                       <?php if($user->wantsEmail($key)): echo 'checked'; endif; ?>>
                <label class="form-check-label" for="notify_<?php echo e($key); ?>"><?php echo e($label); ?></label>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>
            <?php if(session('status') === 'notifications-updated'): ?>
                <span class="text-success small"><?php echo e(__('Saved.')); ?></span>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/profile/partials/email-notifications-form.blade.php ENDPATH**/ ?>