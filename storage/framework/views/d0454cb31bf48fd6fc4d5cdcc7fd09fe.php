<?php $__env->startSection('title', $lead->title); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.leads.index')); ?>">Leads</a></li>
    <li class="breadcrumb-item active"><?php echo e(Str::limit($lead->title, 40)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('crm.leads.edit', $lead)); ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="<?php echo e(route('crm.leads.destroy', $lead)); ?>"
              onsubmit="return confirm('Delete this lead?')">
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
                        <h4 class="fw-bold mb-1"><?php echo e($lead->title); ?></h4>
                        <?php if($lead->label): ?>
                            <span class="badge bg-<?php echo e($lead->label_badge_class); ?> fs-6">
                                <?php echo e($lead->label); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <small class="text-muted">ID #<?php echo e($lead->id); ?></small>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="text-muted small">Source Origin</label>
                        <p class="mb-0"><?php echo e($lead->source_origin ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Owner</label>
                        <p class="mb-0"><?php echo e($lead->owner ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Lead Created</label>
                        <p class="mb-0"><?php echo e($lead->lead_created_at?->format('d M Y H:i') ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6">
                        <label class="text-muted small">Next Activity</label>
                        <p class="mb-0"><?php echo e($lead->next_activity_at?->format('d M Y') ?? '—'); ?></p>
                    </div>
                    <?php if($lead->notes): ?>
                        <div class="col-12">
                            <label class="text-muted small">Notes</label>
                            <p class="mb-0"><?php echo e($lead->notes); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-chat-left-dots me-2"></i>Notes</h6>
            </div>
            <div class="card-body">
                
                <form method="POST" action="<?php echo e(route('crm.leads.notes.store', $lead)); ?>" class="mb-4">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <textarea name="content" class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  rows="3" placeholder="Add a note…" required></textarea>
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
                    </div>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg me-1"></i>Add Note
                    </button>
                </form>

                
                <?php $__empty_1 = true; $__currentLoopData = $lead->crmNotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="note-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="fw-semibold small"><?php echo e($note->author ?? 'System'); ?></span>
                                <span class="text-muted small ms-2">
                                    <?php echo e($note->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                            <form method="POST"
                                  action="<?php echo e(route('crm.leads.notes.destroy', [$lead, $note])); ?>"
                                  onsubmit="return confirm('Delete note?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-xs btn-link text-danger p-0">
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
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Update Label</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('crm.leads.label', $lead)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div class="mb-3">
                        <select name="label" class="form-select">
                            <?php $__currentLoopData = \App\Models\Lead::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lbl); ?>" <?php if($lead->label === $lbl): echo 'selected'; endif; ?>><?php echo e($lbl); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100">Update Label</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mt-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Created:</strong> <?php echo e($lead->created_at->format('d M Y H:i')); ?></div>
                <div><strong>Updated:</strong> <?php echo e($lead->updated_at->format('d M Y H:i')); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leads/show.blade.php ENDPATH**/ ?>