<div class="col-xxl-6">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">Latest Notifications</h6>
            </div>
            <?php $__currentLoopData = $latestNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($notification['data']['url']); ?>" class="d-flex align-items-start gap-2 mb-20">
                    <span
                        class="w-40-px h-40-px d-flex flex-shrink-0 justify-content-center align-items-center bg-success-600 rounded-circle mb-12">
                        <h6 id="collaboratorCount" class="mb-0 text-base">
                            <?php echo e($notification->notifiable_id); ?>

                        </h6>
                    </span>
                    <div class="flex-grow-1">
                        <h6 class="text-md mb-1 fw-semibold"><?php echo e($notification['data']['title']); ?></h6>
                        <p class="text-sm text-secondary-light fw-medium mb-1">
                            <?php echo e($notification['data']['message']); ?></p>
                        <span class="text-sm text-secondary-light fw-normal"><?php echo e($notification->created_at); ?></span>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/latest-notifications.blade.php ENDPATH**/ ?>