<?php $__env->startSection('title', 'Deals'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Deals</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('topbar-actions'); ?>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('crm.deals.export', request()->query())); ?>"
           class="btn btn-sm btn-outline-success">
            <i class="bi bi-download me-1"></i>Export
        </a>
        <a href="<?php echo e(route('crm.deals.import.form')); ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-upload me-1"></i>Import
        </a>
        <a href="<?php echo e(route('crm.deals.create')); ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>New Deal
        </a>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <div class="text-muted small mb-1">Total Deals</div>
                <div class="fs-4 fw-bold"><?php echo e(number_format($stats['total'])); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-primary">
            <div class="card-body">
                <div class="text-muted small mb-1">Open</div>
                <div class="fs-4 fw-bold text-primary"><?php echo e(number_format($stats['open'])); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-success">
            <div class="card-body">
                <div class="text-muted small mb-1">Won</div>
                <div class="fs-4 fw-bold text-success"><?php echo e(number_format($stats['won'])); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card shadow-sm border-info">
            <div class="card-body">
                <div class="text-muted small mb-1">Open Pipeline Value</div>
                <div class="fs-5 fw-bold text-info">
                    AED <?php echo e(number_format($stats['total_val'], 0)); ?>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="filter-bar">
    <form method="GET" action="<?php echo e(route('crm.deals.index')); ?>">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search title, org, contact…"
                       value="<?php echo e($filters['search'] ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    <?php $__currentLoopData = \App\Models\Deal::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php if(($filters['status'] ?? '') === $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="stage" class="form-select form-select-sm">
                    <option value="">All Stages</option>
                    <?php $__currentLoopData = \App\Models\Deal::STAGES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php if(($filters['stage'] ?? '') === $st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
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
            <div class="col-md-1 d-flex gap-1">
                <button class="btn btn-sm btn-primary w-100" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <a href="<?php echo e(route('crm.deals.index')); ?>" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </div>

        
        <div class="row g-2 mt-1 align-items-end">
            <div class="col-md-2">
                <input type="number" name="value_min" class="form-control form-control-sm"
                       placeholder="Value min" value="<?php echo e($filters['value_min'] ?? ''); ?>" min="0">
            </div>
            <div class="col-md-2">
                <input type="number" name="value_max" class="form-control form-control-sm"
                       placeholder="Value max" value="<?php echo e($filters['value_max'] ?? ''); ?>" min="0">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="<?php echo e($filters['date_from'] ?? ''); ?>" title="Deal created from">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="<?php echo e($filters['date_to'] ?? ''); ?>" title="Deal created to">
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
                        <th>Organization</th>
                        <th>Value</th>
                        <th>Stage</th>
                        <th>Status</th>
                        <th>Owner</th>
                        <th>Expected Close</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $deals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-muted small"><?php echo e($deal->pipedrive_id ?? $deal->id); ?></td>
                        <td>
                            <a href="<?php echo e(route('crm.deals.show', $deal)); ?>"
                               class="text-decoration-none fw-semibold text-dark">
                                <?php echo e($deal->title); ?>

                            </a>
                            <?php if($deal->notes): ?>
                                <i class="bi bi-chat-left-text text-muted ms-1"
                                   title="<?php echo e(Str::limit($deal->notes, 60)); ?>"></i>
                            <?php endif; ?>
                        </td>
                        <td class="small"><?php echo e($deal->organization ?? '—'); ?></td>
                        <td class="small fw-semibold">
                            <?php echo e($deal->value ? number_format($deal->value, 0) . ' ' . $deal->currency : '—'); ?>

                        </td>
                        <td>
                            <?php if($deal->stage): ?>
                                <span class="badge bg-<?php echo e($deal->stage_badge_class); ?>"><?php echo e($deal->stage); ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($deal->status_badge_class); ?>"><?php echo e($deal->status); ?></span>
                        </td>
                        <td class="small"><?php echo e($deal->owner ?? '—'); ?></td>
                        <td class="small text-muted">
                            <?php echo e($deal->expected_close_date?->format('d M Y') ?? '—'); ?>

                        </td>
                        <td class="small text-muted">
                            <?php echo e($deal->deal_created_at?->format('d M Y') ?? $deal->created_at->format('d M Y')); ?>

                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end">
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                            data-bs-toggle="dropdown" title="Change status">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px">
                                        <?php $__currentLoopData = \App\Models\Deal::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <form method="POST"
                                                      action="<?php echo e(route('crm.deals.status', $deal)); ?>">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                                    <input type="hidden" name="status" value="<?php echo e($st); ?>">
                                                    <button class="dropdown-item small d-flex align-items-center gap-2"
                                                            type="submit">
                                                        <span class="badge bg-<?php echo e($st === 'Won' ? 'success' : ($st === 'Lost' ? 'danger' : 'primary')); ?>"><?php echo e($st); ?></span>
                                                    </button>
                                                </form>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <a href="<?php echo e(route('crm.deals.edit', $deal)); ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="<?php echo e(route('crm.deals.destroy', $deal)); ?>"
                                      onsubmit="return confirm('Delete this deal?')">
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
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
                            No deals found. <a href="<?php echo e(route('crm.deals.create')); ?>">Create one</a>
                            or <a href="<?php echo e(route('crm.deals.import.form')); ?>">import from Pipedrive</a>.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 pt-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
    <div class="text-secondary small order-2 order-md-1">
        Showing 
        <span class="fw-semibold text-dark"><?php echo e($deals->firstItem()); ?></span> 
        to 
        <span class="fw-semibold text-dark"><?php echo e($deals->lastItem()); ?></span> 
        of 
        <span class="fw-semibold text-dark"><?php echo e($deals->total()); ?></span> 
        deals
    </div>

    <div class="order-1 order-md-2">
        <?php echo e($deals->links('pagination::bootstrap-5')); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.crm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/deals/index.blade.php ENDPATH**/ ?>