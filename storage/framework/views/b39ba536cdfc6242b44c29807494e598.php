<?php $__env->startSection('title', 'Edit Deal'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.deals.index')); ?>">Deals</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.deals.show', $deal)); ?>"><?php echo e(Str::limit($deal->title, 30)); ?></a></li>
    <li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('deals.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/deals/edit.blade.php ENDPATH**/ ?>