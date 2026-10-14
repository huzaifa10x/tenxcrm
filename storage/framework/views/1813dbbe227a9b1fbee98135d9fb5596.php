<?php $__env->startSection('title', $activity->subject); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('crm.activities.index')); ?>">Activities</a></li>
    <li class="breadcrumb-item active"><?php echo e(Str::limit($activity->subject, 40)); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('crm.activities.edit', $activity)); ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <form method="POST" action="<?php echo e(route('crm.activities.done', $activity)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <button class="btn btn-sm <?php echo e($activity->is_done ? 'btn-success' : 'btn-outline-success'); ?>">
                <i class="bi bi-check-lg me-1"></i><?php echo e($activity->is_done ? 'Done' : 'Mark Done'); ?>

            </button>
        </form>
        <form method="POST" action="<?php echo e(route('crm.activities.destroy', $activity)); ?>"
              onsubmit="return confirm('Delete this activity?')">
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
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">

                
                <div class="d-flex align-items-start gap-3 mb-4">
                    <span class="type-badge bg-<?php echo e($activity->type_color); ?>-subtle text-<?php echo e($activity->type_color); ?>"
                          style="width:44px;height:44px;font-size:1.2rem;border-radius:50%;display:flex;align-items:center;justify-content:center">
                        <i class="bi <?php echo e($activity->type_icon); ?>"></i>
                    </span>
                    <div>
                        <h4 class="fw-bold mb-1"><?php echo e($activity->subject); ?></h4>
                        <div class="d-flex gap-2">
                            <span class="badge bg-<?php echo e($activity->type_color); ?>"><?php echo e($activity->type); ?></span>
                            <span class="badge <?php echo e($activity->is_done ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                <?php echo e($activity->done); ?>

                            </span>
                            <?php if($activity->priority === 'High'): ?>
                                <span class="badge bg-danger">High Priority</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row g-3">
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Due Date</label>
                        <p class="mb-0 fw-semibold <?php echo e($activity->due_date?->isPast() && !$activity->is_done ? 'text-danger' : ''); ?>">
                            <?php echo e($activity->due_date?->format('d M Y') ?? '—'); ?>

                            <?php if($activity->due_time): ?>
                                <small class="text-muted d-block"><?php echo e(substr($activity->due_time, 0, 5)); ?></small>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Duration</label>
                        <p class="mb-0"><?php echo e($activity->duration ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Assigned To</label>
                        <p class="mb-0"><?php echo e($activity->assigned_to ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Organization</label>
                        <p class="mb-0"><?php echo e($activity->organization ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Contact Person</label>
                        <p class="mb-0"><?php echo e($activity->contact_person ?? '—'); ?></p>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label class="text-muted small">Priority</label>
                        <p class="mb-0"><?php echo e($activity->priority); ?></p>
                    </div>

                    <?php if($activity->primary_email): ?>
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <a href="mailto:<?php echo e($activity->primary_email); ?>"><?php echo e($activity->primary_email); ?></a>
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->primary_phone): ?>
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0">
                                <a href="tel:<?php echo e($activity->primary_phone); ?>"><?php echo e($activity->primary_phone); ?></a>
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->location): ?>
                        <div class="col-sm-6 col-md-4">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0"><?php echo e($activity->location); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->video_call_url): ?>
                        <div class="col-12">
                            <label class="text-muted small">Video Call</label>
                            <p class="mb-0"><a href="<?php echo e($activity->video_call_url); ?>" target="_blank"><?php echo e($activity->video_call_url); ?></a></p>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->description): ?>
                        <div class="col-12">
                            <label class="text-muted small">Description</label>
                            <p class="mb-0"><?php echo e($activity->description); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if($activity->note): ?>
                        <div class="col-12">
                            <label class="text-muted small">Note</label>
                            <div class="p-3 rounded" style="background:#fefce8;border:1px solid #fef08a">
                                <?php echo e($activity->note); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-semibold">Linked Records</h6>
            </div>
            <div class="card-body small">
                <div class="mb-2">
                    <span class="text-muted">Deal / Lead:</span><br>
                    <?php if($activity->deal): ?>
                        <a href="<?php echo e(route('crm.deals.show', $activity->deal)); ?>"><?php echo e($activity->deal->title); ?></a>
                    <?php elseif($activity->lead): ?>
                        <a href="<?php echo e(route('crm.leads.show', $activity->lead)); ?>"><?php echo e($activity->lead->title); ?></a>
                    <?php elseif($activity->deal_name): ?>
                        <?php echo e($activity->deal_name); ?>

                    <?php else: ?>
                        <span class="text-muted">—</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body small text-muted">
                <div class="mb-1"><strong>Created:</strong> <?php echo e($activity->created_at->format('d M Y H:i')); ?></div>
                <div><strong>Updated:</strong> <?php echo e($activity->updated_at->format('d M Y H:i')); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/activities/show.blade.php ENDPATH**/ ?>