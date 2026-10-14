<?php
    $pinErrors = $errors->getBag('updatePin');
    $removeErrors = $errors->getBag('removePin');
?>

<section id="login-pin">
    <header class="mb-4">
        <h2 class="h5 text-dark d-flex align-items-center gap-2">
            <?php echo e(__('Login PIN')); ?>

            <?php if($user->hasPin()): ?>
                <span class="badge bg-success" style="font-size: .7rem;">Active</span>
            <?php endif; ?>
        </h2>
        <p class="text-muted small mb-0">
            Sign in with a 6-digit PIN instead of your password. Your PIN is stored encrypted, and PIN sign-in
            locks for 15 minutes after 5 wrong attempts. Your password always keeps working.
        </p>
        <?php if($user->hasPin() && $user->pin_set_at): ?>
            <p class="text-muted small mt-2 mb-0">PIN last changed <?php echo e($user->pin_set_at->timezone('Asia/Dubai')->format('d M Y, h:i A')); ?>.</p>
        <?php endif; ?>
    </header>

    <?php if(session('status') === 'pin-updated'): ?>
        <div class="alert alert-success py-2 small">Your PIN is saved. Choose <strong>PIN</strong> on the sign-in page next time.</div>
    <?php elseif(session('status') === 'pin-removed'): ?>
        <div class="alert alert-secondary py-2 small">Your PIN was removed. Sign in with your password.</div>
    <?php endif; ?>

    <form method="post" action="<?php echo e(route('profile.pin.update')); ?>" autocomplete="off" style="max-width: 420px;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>

        <div class="mb-3">
            <label for="pin_current_password" class="form-label"><?php echo e(__('Current password')); ?></label>
            <input type="password" name="current_password" id="pin_current_password" autocomplete="current-password"
                   class="form-control <?php if($pinErrors->has('current_password')): ?> is-invalid <?php endif; ?>" required>
            <?php if($pinErrors->has('current_password')): ?>
                <div class="invalid-feedback"><?php echo e($pinErrors->first('current_password')); ?></div>
            <?php endif; ?>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-sm-6">
                <label for="pin_new" class="form-label"><?php echo e($user->hasPin() ? __('New PIN') : __('PIN')); ?></label>
                <input type="password" name="pin" id="pin_new" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                       autocomplete="new-password" placeholder="6 digits"
                       class="form-control text-center fw-semibold <?php if($pinErrors->has('pin')): ?> is-invalid <?php endif; ?>"
                       style="letter-spacing: .5em;" required>
            </div>
            <div class="col-sm-6">
                <label for="pin_confirm" class="form-label"><?php echo e(__('Confirm PIN')); ?></label>
                <input type="password" name="pin_confirmation" id="pin_confirm" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                       autocomplete="new-password" placeholder="6 digits"
                       class="form-control text-center fw-semibold <?php if($pinErrors->has('pin')): ?> is-invalid <?php endif; ?>"
                       style="letter-spacing: .5em;" required>
            </div>
            <?php if($pinErrors->has('pin')): ?>
                <div class="col-12"><div class="text-danger small"><?php echo e($pinErrors->first('pin')); ?></div></div>
            <?php endif; ?>
            <div class="col-12">
                <div class="form-text">Avoid easy PINs like 123456, 111111 or your birth year. Don't reuse your bank PIN.</div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary"><?php echo e($user->hasPin() ? __('Change PIN') : __('Set PIN')); ?></button>
    </form>

    <?php if($user->hasPin()): ?>
        <details class="mt-4" <?php if($removeErrors->any()): ?> open <?php endif; ?>>
            <summary class="small text-muted" style="cursor: pointer;">Remove my PIN</summary>
            <form method="post" action="<?php echo e(route('profile.pin.destroy')); ?>" class="mt-3" style="max-width: 420px;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('delete'); ?>
                <label for="pin_remove_password" class="form-label"><?php echo e(__('Current password')); ?></label>
                <div class="d-flex gap-2">
                    <input type="password" name="current_password" id="pin_remove_password" autocomplete="current-password"
                           class="form-control <?php if($removeErrors->has('current_password')): ?> is-invalid <?php endif; ?>" required>
                    <button type="submit" class="btn btn-outline-danger text-nowrap">Remove PIN</button>
                </div>
                <?php if($removeErrors->has('current_password')): ?>
                    <div class="text-danger small mt-1"><?php echo e($removeErrors->first('current_password')); ?></div>
                <?php endif; ?>
            </form>
        </details>
    <?php endif; ?>
</section>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/profile/partials/login-pin-form.blade.php ENDPATH**/ ?>