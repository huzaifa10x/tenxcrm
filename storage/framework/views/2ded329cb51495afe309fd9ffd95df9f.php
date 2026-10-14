<?php $__env->startSection('title', isset($deal) ? 'Edit Deal' : 'New Deal'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.deals.index')); ?>">Deals</a></li>
    <?php if(isset($deal)): ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('crm.deals.show', $deal)); ?>"><?php echo e(Str::limit($deal->title, 30)); ?></a></li>
        <li class="breadcrumb-item active">Edit</li>
    <?php else: ?>
        <li class="breadcrumb-item active">New Deal</li>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-xl-10">
        <form method="POST"
              action="<?php echo e(isset($deal) ? route('crm.deals.update', $deal) : route('crm.deals.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(isset($deal)): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

            <div class="row g-4">

                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Core Details</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="title"
                                           class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('title', $deal->title ?? '')); ?>" required>
                                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" class="form-select">
                                        <?php $__currentLoopData = \App\Models\Deal::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($s); ?>"
                                                <?php if(old('status', $deal->status ?? 'Open') === $s): echo 'selected'; endif; ?>>
                                                <?php echo e($s); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Value</label>
                                    <input type="number" name="value" step="0.01" min="0"
                                           class="form-control <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('value', $deal->value ?? '')); ?>">
                                    <?php $__errorArgs = ['value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Currency</label>
                                    <input type="text" name="currency" maxlength="10"
                                           class="form-control"
                                           value="<?php echo e(old('currency', $deal->currency ?? 'AED')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Probability (%)</label>
                                    <input type="number" name="probability" min="0" max="100" step="1"
                                           class="form-control <?php $__errorArgs = ['probability'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('probability', $deal->probability ?? '')); ?>">
                                    <?php $__errorArgs = ['probability'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Stage</label>
                                    <select name="stage" class="form-select">
                                        <option value="">— Select Stage —</option>
                                        <?php $__currentLoopData = \App\Models\Deal::STAGES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($st); ?>"
                                                <?php if(old('stage', $deal->stage ?? '') === $st): echo 'selected'; endif; ?>>
                                                <?php echo e($st); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Contact & Organization</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Organization</label>
                                    <input type="text" name="organization" class="form-control"
                                           value="<?php echo e(old('organization', $deal->organization ?? '')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Contact Person</label>
                                    <input type="text" name="contact_person" class="form-control"
                                           value="<?php echo e(old('contact_person', $deal->contact_person ?? '')); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Owner</label>
                                    <input type="text" name="owner" class="form-control"
                                           value="<?php echo e(old('owner', $deal->owner ?? '')); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Pipeline</label>
                                    <input type="text" name="pipeline" class="form-control"
                                           value="<?php echo e(old('pipeline', $deal->pipeline ?? '')); ?>"
                                           placeholder="e.g. Pipeline">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Label</label>
                                    <input type="text" name="label" class="form-control"
                                           value="<?php echo e(old('label', $deal->label ?? '')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Source & Dates</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Source Origin</label>
                                    <input type="text" name="source_origin" class="form-control"
                                           value="<?php echo e(old('source_origin', $deal->source_origin ?? '')); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Source Channel</label>
                                    <input type="text" name="source_channel" class="form-control"
                                           value="<?php echo e(old('source_channel', $deal->source_channel ?? '')); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Expected Close Date</label>
                                    <input type="date" name="expected_close_date" class="form-control"
                                           value="<?php echo e(old('expected_close_date', isset($deal->expected_close_date) ? $deal->expected_close_date->format('Y-m-d') : '')); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Next Activity Date</label>
                                    <input type="date" name="next_activity_date" class="form-control"
                                           value="<?php echo e(old('next_activity_date', isset($deal->next_activity_date) ? $deal->next_activity_date->format('Y-m-d') : '')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Product (optional)</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Product Name</label>
                                    <input type="text" name="product_name" class="form-control"
                                           value="<?php echo e(old('product_name', $deal->product_name ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Quantity</label>
                                    <input type="number" name="product_quantity" min="0" class="form-control"
                                           value="<?php echo e(old('product_quantity', $deal->product_quantity ?? '')); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Amount</label>
                                    <input type="number" name="product_amount" step="0.01" min="0"
                                           class="form-control"
                                           value="<?php echo e(old('product_amount', $deal->product_amount ?? '')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Additional Info</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Lost Reason</label>
                                    <input type="text" name="lost_reason" class="form-control"
                                           value="<?php echo e(old('lost_reason', $deal->lost_reason ?? '')); ?>"
                                           placeholder="Required when status is Lost">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Notes</label>
                                    <textarea name="notes" rows="4" class="form-control"
                                              placeholder="Internal notes about this deal…"><?php echo e(old('notes', $deal->notes ?? '')); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="col-12 d-flex gap-2 justify-content-end pb-4">
                    <a href="<?php echo e(isset($deal) ? route('crm.deals.show', $deal) : route('crm.deals.index')); ?>"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i>
                        <?php echo e(isset($deal) ? 'Save Changes' : 'Create Deal'); ?>

                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/deals/create.blade.php ENDPATH**/ ?>