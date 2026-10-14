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
        <?php echo e(__('Users')); ?>

     <?php $__env->endSlot(); ?>
    <div class="card h-100 p-0 radius-12">
        <?php if($userPermissions->contains('can manage users')): ?>
            <div class="card-header">
                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-outline-primary">
                    Add New User
                </a>
            </div>
        <?php endif; ?>
        <div class="card-body p-24">
            <div class="row gy-4">
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xxl-3 col-md-6 user-grid-card">
                        <div class="position-relative border radius-16 overflow-hidden">
                            <?php if($user->img): ?>
                                <img src="<?php echo e(asset('/public/storage/' . $user->img)); ?>" alt=""
                                    class="w-100 object-fit-cover">
                            <?php endif; ?>

                            <?php if($userPermissions->contains('can manage users')): ?>
                                <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                                    <button type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        class="bg-white-gradient-light w-32-px h-32-px radius-8 border border-light-white d-flex justify-content-center align-items-center text-white">
                                        <iconify-icon icon="entypo:dots-three-vertical" class="icon "></iconify-icon>
                                    </button>
                                    <ul class="dropdown-menu p-12 border bg-base shadow">
                                        <li>
                                            <a class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
                                                href="<?php echo e(route('users.edit', $user->id)); ?>">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST"
                                                style="display: inline-block;"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="delete-btn dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-danger-100 text-hover-danger-600 d-flex align-items-center gap-10">
                                                    Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="ps-16 pb-16 pe-16 text-center mt--50">
                                <img src="https://cdn-icons-png.flaticon.com/512/9131/9131529.png" alt=""
                                    class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover">
                                <h6 class="text-lg mb-0 mt-4"><?php echo e($user->name); ?></h6>
                                <span class="text-secondary-light mb-16"><?php echo e($user->email); ?></span>

                                <div
                                    class="center-border position-relative bg-danger-gradient-light radius-8 p-12 d-flex align-items-center gap-4">
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0"><?php echo e($user->roles->pluck('name')->implode(', ')); ?></h6>
                                        <span class="text-secondary-light text-sm mb-0">Department</span>
                                    </div>
                                    <div class="text-center w-50">
                                        <h6 class="text-md mb-0">Phone</h6>
                                        <span class="text-secondary-light text-sm mb-0"><?php echo e($user->phone); ?></span>
                                    </div>
                                </div>
                                <a href="<?php echo e(route('users.show', $user->id)); ?>"
                                    class="bg-primary-50 text-primary-600 bg-hover-primary-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100">
                                    View Profile
                                    <iconify-icon icon="solar:alt-arrow-right-linear"
                                        class="icon text-xl line-height-1"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php echo e($users->links('vendor.pagination.custom')); ?>


        </div>
    </div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/users/users-grid-view.blade.php ENDPATH**/ ?>