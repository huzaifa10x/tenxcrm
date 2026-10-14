div<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
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
        <?php echo e(__('USER - ' . $user->name)); ?>

     <?php $__env->endSlot(); ?>
    <?php if($userPermissions->contains('can manage users')): ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <?php if($user->img): ?>
                        <div class="col-lg-6">
                            <img src="<?php echo e(asset('/public/storage/' . $user->img)); ?>" class="img-fluid" alt="Logo"
                                width="50">
                        </div>
                    <?php endif; ?>
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="f7:person"></iconify-icon>
                            </span>
                            <input readonly type="text" name="name" class="form-control"
                                value="<?php echo e($user->name); ?>" placeholder="Enter First Name" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="mage:email"></iconify-icon>
                            </span>
                            <input readonly type="email" name="email" class="form-control" placeholder="Enter Email"
                                value="<?php echo e($user->email); ?>" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <div class="icon-field has-validation">
                            <span class="icon">
                                <iconify-icon icon="solar:phone-calling-linear"></iconify-icon>
                            </span>
                            <input readonly type="text" name="phone" class="form-control"
                                value="<?php echo e($user->phone); ?>" placeholder="+1 (555) 000-0000" required="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <input readonly type="text" name="phone" class="form-control"
                            value="<?php echo e($user->roles->pluck('name')->implode(', ')); ?>">

                    </div>
                </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/users/show.blade.php ENDPATH**/ ?>