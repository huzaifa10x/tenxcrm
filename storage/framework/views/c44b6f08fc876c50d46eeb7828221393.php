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
        <?php echo e(__('Create Role')); ?>

     <?php $__env->endSlot(); ?>
    <?php if($userPermissions->contains('can manage roles')): ?>
        <form method="POST" action="<?php echo e(route('roles.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="card">
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-12">
                            <label class="form-label">Role Name</label>
                            <input type="text" placeholder="Enter Role Name" required name="name"
                                class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Role Permissions</label>
                            <div class="d-block d-lg-flex flex-wrap">
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-lg-4 form-check d-flex align-items-center g-2">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="<?php echo e($permission->id); ?>" id="permission-<?php echo e($permission->id); ?>">
                                        <label class="form-check-label" for="permission-<?php echo e($permission->id); ?>">
                                            <?php echo e($permission->name); ?>

                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Create Role</button>
                            <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/roles/create.blade.php ENDPATH**/ ?>