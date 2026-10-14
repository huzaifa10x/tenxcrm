<?php $__env->startSection('title', $deal->title); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.deals.index')); ?>">Deals</a></li>
    <li class="breadcrumb-item active"><?php echo e(Str::limit($deal->title, 40)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('crm.deals.edit', $deal)); ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="<?php echo e(route('crm.deals.destroy', $deal)); ?>"
              onsubmit="return confirm('Delete this deal?')">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm btn-outline-danger">
                <i class="bi bi-trash me-1"></i>Delete
            </button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row g-4">

    
    <div class="col-lg-8">

        
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo e($deal->title); ?></h4>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-<?php echo e($deal->status_badge_class); ?> fs-6"><?php echo e($deal->status); ?></span>
                            <?php if($deal->stage): ?>
                                <span class="badge bg-<?php echo e($deal->stage_badge_class); ?>"><?php echo e($deal->stage); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fs-4 fw-bold text-success">
                            <?php echo e($deal->value ? number_format($deal->value, 2) . ' ' . $deal->currency : '—'); ?>

                        </div>
                        <small class="text-muted">Deal Value</small>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Organization</label>
                        <p class="mb-0 fw-semibold"><?php echo e($deal->organization ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Contact Person</label>
                        <p class="mb-0"><?php echo e($deal->contact_person ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Owner</label>
                        <p class="mb-0"><?php echo e($deal->owner ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Pipeline</label>
                        <p class="mb-0"><?php echo e($deal->pipeline ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Source Origin</label>
                        <p class="mb-0"><?php echo e($deal->source_origin ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Source Channel</label>
                        <p class="mb-0"><?php echo e($deal->source_channel ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Probability</label>
                        <p class="mb-0"><?php echo e($deal->probability !== null ? $deal->probability . '%' : '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Weighted Value</label>
                        <p class="mb-0">
                            <?php echo e($deal->weighted_value ? number_format($deal->weighted_value, 2) . ' ' . $deal->currency : '—'); ?>

                        </p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Creator</label>
                        <p class="mb-0"><?php echo e($deal->creator ?? '—'); ?></p>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Expected Close</label>
                        <p class="mb-0"><?php echo e($deal->expected_close_date?->format('d M Y') ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Last Activity</label>
                        <p class="mb-0"><?php echo e($deal->last_activity_date?->format('d M Y') ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Deal Created</label>
                        <p class="mb-0"><?php echo e($deal->deal_created_at?->format('d M Y H:i') ?? '—'); ?></p>
                    </div>

                    <?php if($deal->lost_reason): ?>
                        <div class="col-12">
                            <label class="text-muted small">Lost Reason</label>
                            <p class="mb-0 text-danger"><?php echo e($deal->lost_reason); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if($deal->notes): ?>
                        <div class="col-12">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0"><?php echo e($deal->notes); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-activity me-2"></i>Activity Summary</h6>
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="fs-4 fw-bold"><?php echo e($deal->total_activities); ?></div>
                        <div class="text-muted small">Total</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-success"><?php echo e($deal->done_activities); ?></div>
                        <div class="text-muted small">Done</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-4 fw-bold text-warning"><?php echo e($deal->activities_to_do); ?></div>
                        <div class="text-muted small">To Do</div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-chat-left-dots me-2"></i>Notes
                    <span class="badge bg-secondary ms-1"><?php echo e($deal->crmNotes->count()); ?></span>
                </h6>
            </div>
            <div class="card-body">
                
                <form method="POST" action="<?php echo e(route('crm.deals.notes.store', $deal)); ?>" class="mb-4">
                    <?php echo csrf_field(); ?>
                    <textarea name="content" rows="3"
                              class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> mb-2"
                              placeholder="Add a note…" required></textarea>
                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add Note
                    </button>
                </form>

                <?php $__empty_1 = true; $__currentLoopData = $deal->crmNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="note-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold small"><?php echo e($note->author ?? 'System'); ?></span>
                                <span class="text-muted small ms-2">
                                    <?php echo e($note->created_at->diffForHumans()); ?>

                                    · <?php echo e($note->created_at->format('d M Y H:i')); ?>

                                </span>
                            </div>
                            <form method="POST"
                                  action="<?php echo e(route('crm.deals.notes.destroy', [$deal, $note])); ?>"
                                  onsubmit="return confirm('Delete note?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-link text-danger p-0 btn-sm">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        </div>
                        <p class="mb-0 mt-1"><?php echo e($note->content); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small">No notes yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">

        
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Update Status</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('crm.deals.status', $deal)); ?>" id="statusForm">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <select name="status" class="form-select" id="statusSelect"
                                onchange="toggleLostReason(this.value)">
                            <?php $__currentLoopData = \App\Models\Deal::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php if($deal->status === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div id="lostReasonWrap" class="mb-3"
                         style="<?php echo e($deal->status === 'Lost' ? '' : 'display:none'); ?>">
                        <label class="form-label small fw-semibold">Lost Reason</label>
                        <textarea name="lost_reason" class="form-control form-control-sm" rows="2"
                                  placeholder="Why was this deal lost?"><?php echo e($deal->lost_reason); ?></textarea>
                    </div>
                    <button class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Move Stage</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('crm.deals.stage', $deal)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <select name="stage" class="form-select">
                            <?php $__currentLoopData = \App\Models\Deal::STAGES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($st); ?>" <?php if($deal->stage === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button class="btn btn-outline-primary w-100">Move Stage</button>
                </form>
            </div>
        </div>

        
        <?php if($deal->product_name): ?>
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold"><i class="bi bi-box me-2"></i>Product</h6>
                </div>
                <div class="card-body small">
                    <div class="mb-1"><strong>Name:</strong> <?php echo e($deal->product_name); ?></div>
                    <div class="mb-1"><strong>Qty:</strong> <?php echo e($deal->product_quantity ?? '—'); ?></div>
                    <div><strong>Amount:</strong>
                        <?php echo e($deal->product_amount ? number_format($deal->product_amount, 2) . ' ' . $deal->currency : '—'); ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Pipedrive ID:</strong> <?php echo e($deal->pipedrive_id ?? '—'); ?></div>
                <div class="mb-1"><strong>Archive:</strong> <?php echo e($deal->archive_status ?? '—'); ?></div>
                <div class="mb-1"><strong>Visible to:</strong> <?php echo e($deal->visible_to ?? '—'); ?></div>
                <div class="mb-1"><strong>Won:</strong> <?php echo e($deal->won_time?->format('d M Y') ?? '—'); ?></div>
                <div class="mb-1"><strong>Lost:</strong> <?php echo e($deal->lost_time?->format('d M Y') ?? '—'); ?></div>
                <div class="mb-1"><strong>Record created:</strong> <?php echo e($deal->created_at->format('d M Y H:i')); ?></div>
                <div><strong>Last updated:</strong> <?php echo e($deal->updated_at->format('d M Y H:i')); ?></div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleLostReason(val) {
    document.getElementById('lostReasonWrap').style.display = val === 'Lost' ? '' : 'none';
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/deals/show.blade.php ENDPATH**/ ?>