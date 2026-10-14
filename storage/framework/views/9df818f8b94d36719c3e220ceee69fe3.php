<section>
    <header class="mb-4">
        <h2 class="h5 text-dark">
            <?php echo e(__('Update Password')); ?>

        </h2>
        <p class="text-muted small">
            <?php echo e(__('Ensure your account is using a long, random password to stay secure.')); ?>

        </p>
    </header>

    <form method="post" action="<?php echo e(route('password.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label"><?php echo e(__('Current Password')); ?></label>
            <input type="password" name="current_password" id="update_password_current_password"
                   class="form-control <?php if($errors->updatePassword->has('current_password')): ?> is-invalid <?php endif; ?>"
                   autocomplete="current-password">
            <?php if($errors->updatePassword->has('current_password')): ?>
                <div class="invalid-feedback">
                    <?php echo e($errors->updatePassword->first('current_password')); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label"><?php echo e(__('New Password')); ?></label>
            <input type="password" name="password" id="update_password_password"
                   class="form-control <?php if($errors->updatePassword->has('password')): ?> is-invalid <?php endif; ?>"
                   autocomplete="new-password">
            <?php if($errors->updatePassword->has('password')): ?>
                <div class="invalid-feedback">
                    <?php echo e($errors->updatePassword->first('password')); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
            <input type="password" name="password_confirmation" id="update_password_password_confirmation"
                   class="form-control <?php if($errors->updatePassword->has('password_confirmation')): ?> is-invalid <?php endif; ?>"
                   autocomplete="new-password">
            <?php if($errors->updatePassword->has('password_confirmation')): ?>
                <div class="invalid-feedback">
                    <?php echo e($errors->updatePassword->first('password_confirmation')); ?>

                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>

            <?php if(session('status') === 'password-updated'): ?>
                <p class="text-muted small mb-0" x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)">
                    <?php echo e(__('Saved.')); ?>

                </p>
            <?php endif; ?>
        </div>
    </form>
</section>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/profile/partials/update-password-form.blade.php ENDPATH**/ ?>