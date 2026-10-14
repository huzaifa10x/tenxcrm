<?php if($errors->any()): ?>
    <div class="alert alert-danger py-2" style="font-size:.82rem;"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required value="<?php echo e(old('name', $organization->name ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $organization->email ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $organization->phone ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Website</label>
        <input type="text" name="website" class="form-control" value="<?php echo e(old('website', $organization->website ?? '')); ?>"></div>
    <div class="col-12"><label class="form-label fw-semibold">Address</label>
        <textarea name="address" class="form-control" rows="2"><?php echo e(old('address', $organization->address ?? '')); ?></textarea></div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/organizations/_form.blade.php ENDPATH**/ ?>