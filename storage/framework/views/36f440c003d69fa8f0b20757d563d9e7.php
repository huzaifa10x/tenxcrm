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
     <?php $__env->slot('header', null, []); ?> 
        <?php echo e(__('Create User')); ?>

     <?php $__env->endSlot(); ?>
    <?php if($userPermissions->contains('can manage users')): ?>
        <div class="card">
            <div class="card-body">
                <form action="<?php echo e(route('users.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <div class="icon-field has-validation">
                                <span class="icon">
                                    <iconify-icon icon="f7:person"></iconify-icon>
                                </span>
                                <input type="text" name="name" class="form-control" placeholder="Enter First Name"
                                    required="">
                                <div class="invalid-feedback">
                                    Please provide name
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <div class="icon-field has-validation">
                                <span class="icon">
                                    <iconify-icon icon="mage:email"></iconify-icon>
                                </span>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email"
                                    required="">
                                <div class="invalid-feedback">
                                    Please provide email address
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <div class="icon-field has-validation">
                                <span class="icon">
                                    <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                                </span>
                                <input type="text" name="phone" class="form-control"
                                    placeholder="+1 (555) 000-0000" required="">
                                <div class="invalid-feedback">
                                    Please provide phone number
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <div class="icon-field has-validation">
                                <span class="icon">
                                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                </span>
                                <input type="password" name="password" class="form-control" placeholder="*******"
                                    required="">
                                <div class="invalid-feedback">
                                    Please provide password
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <div class="icon-field has-validation">
                                <span class="icon">
                                    <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                                </span>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="*******" required="">
                                <div class="invalid-feedback">
                                    Please confirm password
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-3">
                            <label class="form-label">Roles</label>
                            <div class="form-group">
                                <div class="row">
                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-lg-3">
                                            <div class="form-check d-flex align-items-center gap-2">
                                                <input class="form-check-input" type="checkbox" name="roles[]"
                                                    value="<?php echo e($role->id); ?>" id="role_<?php echo e($role->id); ?>"
                                                    <?php if(in_array($role->id, old('roles', []))): ?> checked <?php endif; ?>>
                                                <label class="form-check-label" for="role_<?php echo e($role->id); ?>">
                                                    <?php echo e($role->name); ?>

                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <button class="btn btn-primary-600" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/users/create.blade.php ENDPATH**/ ?>