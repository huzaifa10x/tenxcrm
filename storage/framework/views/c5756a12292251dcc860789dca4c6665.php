
<form method="GET" action="<?php echo e($action); ?>" class="mb-4 card">
    <div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3 col-6">
                <label class="form-label fw-semibold">Period</label>
                <select name="range" class="form-select js-range">
                    <?php $__currentLoopData = $ranges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e($range === $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2 col-6 js-custom-date" style="<?php echo e($range === 'custom' ? '' : 'display:none'); ?>">
                <label class="form-label fw-semibold">From</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo e($from->toDateString()); ?>">
            </div>
            <div class="col-md-2 col-6 js-custom-date" style="<?php echo e($range === 'custom' ? '' : 'display:none'); ?>">
                <label class="form-label fw-semibold">To</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo e($to->toDateString()); ?>">
            </div>
            <?php echo e($slot ?? ''); ?>

            <div class="col-md-auto col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel-fill me-1"></i>Apply</button>
                <a href="<?php echo e($action); ?>" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
        <small class="mt-2 text-muted d-block">Showing <?php echo e($from->format('d M Y')); ?> – <?php echo e($to->format('d M Y')); ?></small>
    </div>
</form>
<script>
    document.querySelectorAll('.js-range').forEach(function(select) {
        select.addEventListener('change', function() {
            document.querySelectorAll('.js-custom-date').forEach(function(el) {
                el.style.display = select.value === 'custom' ? '' : 'none';
            });
        });
    });
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/admin/offsite-seo/_filters.blade.php ENDPATH**/ ?>