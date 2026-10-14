<?php $__env->startSection('title', 'Leads'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Leads</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('crm.leads.export', request()->query())); ?>"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export
        </a>
        <a href="<?php echo e(route('crm.leads.import.form')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <a href="<?php echo e(route('crm.leads.create')); ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Lead
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0 fw-bold">Leads
        <span class="badge bg-secondary ms-2 fw-normal"><?php echo e($leads->total()); ?></span>
    </h4>
</div>


<div class="filter-bar">
    <form method="GET" action="<?php echo e(route('crm.leads.index')); ?>" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search title, owner…" value="<?php echo e($filters['search'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <select name="label" class="form-select form-select-sm">
                    <option value="">All Labels</option>
                    <?php $__currentLoopData = \App\Models\Lead::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($l); ?>" <?php if(($filters['label'] ?? '') === $l): echo 'selected'; endif; ?>><?php echo e($l); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="owner" class="form-select form-select-sm">
                    <option value="">All Owners</option>
                    <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($o); ?>" <?php if(($filters['owner'] ?? '') === $o): echo 'selected'; endif; ?>><?php echo e($o); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="source_origin" class="form-select form-select-sm">
                    <option value="">All Sources</option>
                    <?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php if(($filters['source_origin'] ?? '') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="<?php echo e($filters['date_from'] ?? ''); ?>" title="From date">
            </div>
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="<?php echo e($filters['date_to'] ?? ''); ?>" title="To date">
            </div>
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?php echo e(route('crm.leads.index')); ?>" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>
    </form>
</div>


<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-crm table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Label</th>
                        <th>Source</th>
                        <th>Owner</th>
                        <th>Next Activity</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted small"><?php echo e($lead->id); ?></td>
                        <td>
                            <a href="<?php echo e(route('crm.leads.show', $lead)); ?>"
                               class="text-decoration-none fw-semibold text-dark">
                                <?php echo e($lead->title); ?>

                            </a>
                            <?php if($lead->notes): ?>
                                <i class="bi bi-chat-left-text text-muted ms-1" title="<?php echo e(Str::limit($lead->notes, 60)); ?>"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($lead->label): ?>
                                <span class="badge bg-<?php echo e($lead->label_badge_class); ?>"><?php echo e($lead->label); ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small"><?php echo e($lead->source_origin ?? '—'); ?></td>
                        <td class="small"><?php echo e($lead->owner ?? '—'); ?></td>
                        <td class="small text-muted">
                            <?php echo e($lead->next_activity_at?->format('d M Y') ?? '—'); ?>

                        </td>
                        <td class="small text-muted">
                            <?php echo e($lead->lead_created_at?->format('d M Y') ?? $lead->created_at->format('d M Y')); ?>

                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                
                                <div class="dropdown">
                                    <button class="btn btn-xs btn-outline-secondary dropdown-toggle btn-sm"
                                            data-bs-toggle="dropdown" title="Change label">
                                        <i class="bi bi-tag"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php $__currentLoopData = \App\Models\Lead::LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lbl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <form method="POST"
                                                      action="<?php echo e(route('crm.leads.label', $lead)); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                    <input type="hidden" name="label" value="<?php echo e($lbl); ?>">
                                                    <button class="dropdown-item small" type="submit">
                                                        <?php echo e($lbl); ?>

                                                    </button>
                                                </form>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <a href="<?php echo e(route('crm.leads.edit', $lead)); ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('crm.leads.destroy', $lead)); ?>"
                                      onsubmit="return confirm('Delete this lead?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            No leads found. <a href="<?php echo e(route('crm.leads.create')); ?>">Create one</a>
                            or <a href="<?php echo e(route('crm.leads.import.form')); ?>">import from Pipedrive</a>.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 pt-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
    <div class="text-muted small order-2 order-sm-1">
        Showing 
        <span class="fw-bold text-dark"><?php echo e($leads->firstItem()); ?></span> 
        to 
        <span class="fw-bold text-dark"><?php echo e($leads->lastItem()); ?></span> 
        of 
        <span class="fw-bold text-dark"><?php echo e($leads->total()); ?></span> 
        leads
    </div>

    <div class="pagination-sm order-1 order-sm-2">
        <?php echo e($leads->links('pagination::bootstrap-5')); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leads/index.blade.php ENDPATH**/ ?>