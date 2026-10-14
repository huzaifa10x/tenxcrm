<section>
    <header class="mb-4">
        <h2 class="h5 text-dark">
            <?php echo e(__('Profile Information')); ?>

        </h2>
        <p class="text-muted small">
            <?php echo e(__("Update your account's profile information and email address.")); ?>

        </p>
    </header>

    <form id="send-verification" method="post" action="<?php echo e(route('verification.send')); ?>">
        <?php echo csrf_field(); ?>
    </form>

    <form method="post" action="<?php echo e(route('profile.update')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('patch'); ?>

        <div class="mb-3">
            <label for="name" class="form-label"><?php echo e(__('Name')); ?></label>
            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required autofocus autocomplete="name">
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><?php echo e(__('Email')); ?></label>
            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required autocomplete="username">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <?php if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail()): ?>
                <div class="mt-2">
                    <p class="small text-secondary">
                        <?php echo e(__('Your email address is unverified.')); ?>

                        <button type="submit" form="send-verification" class="btn btn-link p-0 align-baseline">
                            <?php echo e(__('Click here to re-send the verification email.')); ?>

                        </button>
                    </p>

                    <?php if(session('status') === 'verification-link-sent'): ?>
                        <p class="text-success small">
                            <?php echo e(__('A new verification link has been sent to your email address.')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>

            <?php if(session('status') === 'profile-updated'): ?>
                <p class="text-muted small mb-0" x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)">
                    <?php echo e(__('Saved.')); ?>

                </p>
            <?php endif; ?>
        </div>
    </form>
</section><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/profile/partials/update-profile-information-form.blade.php ENDPATH**/ ?>