<?php $__env->startSection('title', 'Edit Lead'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.leads.index')); ?>">Leads</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.leads.show', $lead)); ?>"><?php echo e(Str::limit($lead->title, 30)); ?></a></li>
    <li class="breadcrumb-item active">Edit</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('leads.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leads/edit.blade.php ENDPATH**/ ?>