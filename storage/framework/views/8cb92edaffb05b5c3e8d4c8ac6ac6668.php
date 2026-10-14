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
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('PipeDrive — Contacts')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->make('pipedrive._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="crm-page">
        <?php if(session('success')): ?><div class="alert alert-success py-2" style="font-size:.85rem;"><?php echo e(session('success')); ?></div><?php endif; ?>
        <div class="crm-toolbar">
            <h4><i class="bi bi-people me-2"></i>Contacts</h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search…" value="<?php echo e(request('search')); ?>" style="width:220px;">
                    <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <a href="<?php echo e(route('pipedrive.persons.create')); ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add</a>
            </div>
        </div>
        <div class="crm-panel">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead style="background:#f8f9fc;"><tr><th>Name</th><th>Phone</th><th>Email</th><th>Organization</th><th>Deals</th><th></th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($p->name); ?></td>
                                <td><?php echo e($p->phone ?: '—'); ?></td>
                                <td><?php echo e($p->email ?: '—'); ?></td>
                                <td><?php echo e($p->organization?->name ?: '—'); ?></td>
                                <td><span class="badge bg-primary"><?php echo e($p->deals_count); ?></span></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('pipedrive.persons.edit', $p)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="<?php echo e(route('pipedrive.persons.destroy', $p)); ?>" class="d-inline" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="crm-empty"><i class="bi bi-people"></i>No contacts yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3"><?php echo e($persons->links()); ?></div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/persons/index.blade.php ENDPATH**/ ?>