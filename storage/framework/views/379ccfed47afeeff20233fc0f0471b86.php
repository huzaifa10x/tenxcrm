<?php $__env->startSection('title', 'Import Activities'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.activities.index')); ?>">Activities</a></li>
    <li class="breadcrumb-item active">Import</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-lg-7">

        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-upload me-2"></i>Import Activities from Pipedrive</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('crm.activities.import')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">CSV / Excel File <span class="text-danger">*</span></label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls"
                               class="form-control <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">Accepted: .csv, .xlsx, .xls — Max 20 MB</div>
                    </div>

                    <div class="alert alert-info small d-flex gap-2">
                        <i class="bi bi-info-circle-fill flex-shrink-0 mt-1"></i>
                        <div>
                            Each row is inserted as a new activity. The activity <strong>type</strong> is
                            inferred from the subject text (keywords: meeting, email, lunch, task, deadline → Call as default).
                            The Pipedrive <strong>"Activity - Deal"</strong> blob field is parsed to extract the contact/deal name.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Start Import
                    </button>
                    <a href="<?php echo e(route('crm.activities.index')); ?>" class="btn btn-outline-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-table me-2"></i>Pipedrive Column Mapping</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0 small">
                    <thead class="table-light">
                        <tr><th>Pipedrive CSV Column</th><th>Maps To</th><th>Notes</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><code>Activity - Done</code></td><td>done</td><td>To do / Done</td></tr>
                        <tr class="table-warning"><td><code>Activity - Subject</code></td><td>subject</td><td><span class="badge bg-danger">Required</span></td></tr>
                        <tr><td><code>Activity - Deal</code></td><td>deal_name</td><td>Name parsed from blob field</td></tr>
                        <tr><td><code>Activity - Contact person</code></td><td>contact_person</td><td></td></tr>
                        <tr><td><code>Person - Email - Work</code></td><td>email_work</td><td></td></tr>
                        <tr><td><code>Person - Email - Home</code></td><td>email_home</td><td></td></tr>
                        <tr><td><code>Person - Email - Other</code></td><td>email_other</td><td></td></tr>
                        <tr><td><code>Person - Phone - Work</code></td><td>phone_work</td><td></td></tr>
                        <tr><td><code>Person - Phone - Home</code></td><td>phone_home</td><td></td></tr>
                        <tr><td><code>Person - Phone - Mobile</code></td><td>phone_mobile</td><td></td></tr>
                        <tr><td><code>Person - Phone - Other</code></td><td>phone_other</td><td></td></tr>
                        <tr><td><code>Activity - Organization</code></td><td>organization</td><td></td></tr>
                        <tr><td><code>Activity - Due date</code></td><td>due_date</td><td>Parsed as date</td></tr>
                        <tr><td><code>Activity - Duration</code></td><td>duration</td><td>e.g. 01:00</td></tr>
                        <tr><td><code>Activity - Assigned to user</code></td><td>assigned_to</td><td></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/activities/import.blade.php ENDPATH**/ ?>