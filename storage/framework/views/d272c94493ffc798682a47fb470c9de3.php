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
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('PipeDrive — Edit Organization')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->make('pipedrive._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="crm-page">
        <div class="crm-toolbar"><h4><i class="bi bi-pencil-square me-2"></i>Edit Organization</h4>
            <a href="<?php echo e(route('pipedrive.organizations.index')); ?>" class="btn btn-sm btn-light"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
        <div class="crm-panel"><div class="crm-panel-body">
            <form method="POST" action="<?php echo e(route('pipedrive.organizations.update', $organization)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <?php echo $__env->make('pipedrive.organizations._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <hr><div class="text-end"><button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Update</button></div>
            </form>
        </div></div>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/organizations/edit.blade.php ENDPATH**/ ?>