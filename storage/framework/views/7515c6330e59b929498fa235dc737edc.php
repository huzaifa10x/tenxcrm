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
    <?php if(auth()->user()->hasRole('ADMIN')): ?>
         <?php $__env->slot('header', null, []); ?> 
            <?php echo e(__('System Setting')); ?>

         <?php $__env->endSlot(); ?>
        <form id="settingsForm">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label>Office Open Time:</label>
                <input type="time" name="office_open_time" value="<?php echo e($settings->office_open_time ?? ''); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Office Close Time:</label>
                <input type="time" name="office_close_time" value="<?php echo e($settings->office_close_time ?? ''); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Late Threshold (Minutes After Office Open Time e.g 15 min):</label>
                <input type="number" name="late_threshold" value="<?php echo e($settings->late_threshold ?? '15'); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Late Deduction Threshold (Number of late arrivals that result in one deduction):</label>
                <input type="number" name="late_deduction_threshold"
                    value="<?php echo e($settings->late_deduction_threshold ?? '3'); ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Half-Day Deduction Threshold (Number of half-days that result in one deduction):</label>
                <input type="number" name="half_day_deduction_threshold"
                    value="<?php echo e($settings->half_day_deduction_threshold ?? '3'); ?>" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Half-Day Threshold (Late after this = Half Day):</label>
                <input type="time" name="half_day_threshold" value="<?php echo e($settings->half_day_threshold ?? '12:00'); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Break End Time:</label>
                <input type="time" name="break_end_time" value="<?php echo e($settings->break_end_time ?? '14:00'); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Early Out Threshold (Punch out before this = Early Out):</label>
                <input type="time" name="early_out_threshold"
                    value="<?php echo e($settings->early_out_threshold ?? ($settings->office_close_time ?? '')); ?>"
                    class="form-control" required>
            </div>

            <div class="form-group">
                <label>Holidays (Comma Separated Dates) e.g ["2025-03-23", "2025-04-01"]</label>
                <input type="text" name="holidays"
                    value="<?php echo e(isset($settings->holidays) ? implode(',', $settings->holidays) : ''); ?>"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary btn-sm my-10">Save Settings</button>
        </form>

        <div id="responseMessage"></div>
        <script>
            document.getElementById('settingsForm').addEventListener('submit', function(event) {
                event.preventDefault();

                let formData = new FormData(this);

                fetch("<?php echo e(route('settings.update')); ?>", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('responseMessage').innerHTML =
                            `<div class="alert alert-success">${data.success}</div>`;
                    })
                    .catch(error => {
                        document.getElementById('responseMessage').innerHTML =
                            `<div class="alert alert-success">${error}</div>`;
                    });
            });
        </script>
    <?php else: ?>
        <?php echo $__env->make('vendor.not-allowed', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/settings/index.blade.php ENDPATH**/ ?>