<?php if($errors->any()): ?>
    <div class="alert alert-danger py-2" style="font-size:.82rem;"><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
<?php endif; ?>
<div class="row g-3">
    <div class="col-md-6"><label class="form-label fw-semibold">First name</label>
        <input type="text" name="first_name" class="form-control" value="<?php echo e(old('first_name', $person->first_name ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Last name</label>
        <input type="text" name="last_name" class="form-control" value="<?php echo e(old('last_name', $person->last_name ?? '')); ?>"></div>
    <div class="col-12"><label class="form-label fw-semibold">Display name</label>
        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $person->name ?? '')); ?>" placeholder="Leave blank to auto-build from first + last"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $person->email ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Phone</label>
        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $person->phone ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Label</label>
        <input type="text" name="label" class="form-control" value="<?php echo e(old('label', $person->label ?? '')); ?>"></div>
    <div class="col-md-6"><label class="form-label fw-semibold">Organization</label>
        <select name="organization_id" class="form-select">
            <option value="">— None —</option>
            <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $org): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($org->id); ?>" <?php if(old('organization_id', $person->organization_id ?? '') == $org->id): echo 'selected'; endif; ?>><?php echo e($org->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select></div>
</div>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/persons/_form.blade.php ENDPATH**/ ?>