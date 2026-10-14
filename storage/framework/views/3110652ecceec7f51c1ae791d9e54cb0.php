<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('CRM — Deal')); ?> <?php $__env->endSlot(); ?>

    <?php echo $__env->make('pipedrive._styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="crm-page">
        <?php if(session('success')): ?>
            <div class="alert alert-success py-2" style="font-size:.85rem;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="crm-toolbar">
            <div>
                <a href="<?php echo e(route('pipedrive.deals.index')); ?>" class="text-decoration-none text-muted" style="font-size:.78rem;">
                    <i class="bi bi-arrow-left me-1"></i><?php echo e($deal->pipeline->name); ?>

                </a>
                <p class="mt-1"><?php echo e($deal->title); ?></p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if($deal->status === 'open'): ?>
                    <form method="POST" action="<?php echo e(route('pipedrive.deals.won', $deal)); ?>" class="d-inline"><?php echo csrf_field(); ?>
                        <button class="btn btn-success btn-sm"><i class="bi bi-trophy me-1"></i>Won</button>
                    </form>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#lostModal"><i class="bi bi-x-circle me-1"></i>Lost</button>
                <?php else: ?>
                    <span class="badge bg-<?php echo e($deal->status === 'won' ? 'success' : 'danger'); ?> px-3 py-2">
                        <?php echo e(strtoupper($deal->status)); ?>

                    </span>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $deal)): ?>
                    <a href="<?php echo e(route('pipedrive.deals.edit', $deal)); ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $deal)): ?>
                    <form method="POST" action="<?php echo e(route('pipedrive.deals.destroy', $deal)); ?>" onsubmit="return confirm('Delete this deal?')" class="d-inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        
        <?php
            $currentPos = $deal->stage->position;
        ?>
        <div class="crm-stage-bar">
            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $cls = $stage->position < $currentPos ? 'passed'
                         : ($stage->id === $deal->stage_id ? 'current' : 'future');
                    $days = $stageDurations[$stage->id] ?? 0;
                ?>
                <div class="crm-stage-seg <?php echo e($cls); ?>">
                    <?php echo e($stage->name); ?>

                    <small><?php echo e($cls === 'future' ? '—' : $days . 'd'); ?></small>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="row g-3">
            
            <div class="col-lg-4">
                
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-card-text me-1"></i>Summary</div>
                    <div class="crm-panel-body">
                        <div class="crm-meta-row"><span>Value</span><span><?php echo e($deal->currency); ?> <?php echo e(number_format((float) $deal->value, 2)); ?></span></div>
                        <div class="crm-meta-row"><span>Stage</span><span><span class="badge" style="background:<?php echo e($deal->stage->color); ?>;color:#fff;"><?php echo e($deal->stage->name); ?></span></span></div>
                        <div class="crm-meta-row"><span>Status</span><span><?php echo e(ucfirst($deal->status)); ?></span></div>
                        <div class="crm-meta-row"><span>Label</span><span><?php echo e($deal->label ?: '—'); ?></span></div>
                        <div class="crm-meta-row"><span>Owner</span><span><?php echo e($deal->owner?->name ?? '—'); ?></span></div>
                        <div class="crm-meta-row"><span>Expected close</span><span><?php echo e(optional($deal->expected_close_date)->format('M d, Y') ?? '—'); ?></span></div>
                    </div>
                </div>

                
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-person me-1"></i>Person</div>
                    <div class="crm-panel-body">
                        <?php if($deal->person): ?>
                            <a href="<?php echo e(route('pipedrive.persons.edit', $deal->person->id)); ?>" class="fw-semibold mb-1"><?php echo e($deal->person->name); ?></a>
                            <?php if($deal->person->phone): ?><div style="font-size:.82rem;"><i class="bi bi-telephone me-1 text-muted"></i><a href="tel:<?php echo e($deal->person->phone); ?>"><?php echo e($deal->person->phone); ?></a></div><?php endif; ?>
                            <?php if($deal->person->email): ?><div style="font-size:.82rem;"><i class="bi bi-envelope me-1 text-muted"></i><a href="mailto:<?php echo e($deal->person->email); ?>"><?php echo e($deal->person->email); ?></a></div><?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.85rem;">No contact linked.</span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="crm-panel mb-3">
                    <div class="crm-panel-head"><i class="bi bi-building me-1"></i>Organization</div>
                    <div class="crm-panel-body">
                        <?php if($deal->organization): ?>
                            <a href="<?php echo e(route('pipedrive.organizations.edit', $deal->organization->id)); ?>" class="fw-semibold mb-1"><?php echo e($deal->organization->name); ?></a>
                            <?php if($deal->organization->website): ?><div style="font-size:.82rem;"><i class="bi bi-link-45deg me-1 text-muted"></i><a href="<?php echo e($deal->organization->website); ?>" target="_blank"><?php echo e($deal->organization->website); ?></a></div><?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.85rem;">No organization linked.</span>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="crm-panel">
                    <div class="crm-panel-head"><i class="bi bi-speedometer2 me-1"></i>Overview</div>
                    <div class="crm-panel-body">
                        <div class="crm-meta-row"><span>Deal age</span><span><?php echo e($deal->deal_age); ?> days</span></div>
                        <div class="crm-meta-row"><span>Inactive</span><span><?php echo e($deal->inactive_days); ?> days</span></div>
                        <div class="crm-meta-row"><span>Source</span><span><?php echo e($deal->source_origin ?? '—'); ?></span></div>
                        <div class="crm-meta-row"><span>Created</span><span><?php echo e($deal->created_at->format('M d, Y')); ?></span></div>
                    </div>
                </div>
            </div>

            
            <div class="col-lg-8">
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-activity"><i class="bi bi-calendar2-check me-1"></i>Activity</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-notes"><i class="bi bi-sticky me-1"></i>Notes</button></li>
                </ul>

                <div class="tab-content">
                    
                    <div class="tab-pane fade show active" id="tab-activity">
                        <div class="crm-panel mb-3">
                            <div class="crm-panel-body">
                                <form method="POST" action="<?php echo e(route('pipedrive.activities.store', $deal)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select name="type" class="form-select form-select-sm">
                                                <?php $__currentLoopData = \App\Models\Crm\Activity::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($t); ?>"><?php echo e(ucfirst($t)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" name="subject" class="form-control form-control-sm" placeholder="Subject (e.g. Follow-up call)" required>
                                        </div>
                                        <div class="col-md-4"><input type="date" name="due_date" class="form-control form-control-sm"></div>
                                        <div class="col-md-4"><input type="time" name="due_time" class="form-control form-control-sm"></div>
                                        <div class="col-md-4"><input type="text" name="location" class="form-control form-control-sm" placeholder="Location"></div>
                                        <div class="col-12"><textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Notes…"></textarea></div>
                                    </div>
                                    <div class="text-end mt-2"><button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Schedule activity</button></div>
                                </form>
                            </div>
                        </div>

                        <div class="crm-panel">
                            <div class="crm-panel-head">Timeline</div>
                            <div class="crm-panel-body">
                                <?php $__empty_1 = true; $__currentLoopData = $deal->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="crm-timeline-item <?php echo e($activity->done ? 'done' : ''); ?>">
                                        <div class="crm-timeline-icon"><i class="bi <?php echo e($activity->icon); ?>"></i></div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-semibold" style="font-size:.85rem; <?php echo e($activity->done ? 'text-decoration:line-through;opacity:.6;' : ''); ?>"><?php echo e($activity->subject); ?></span>
                                                <span class="text-muted" style="font-size:.72rem;"><?php echo e(ucfirst($activity->type)); ?></span>
                                            </div>
                                            <?php if($activity->note): ?><div class="text-muted" style="font-size:.8rem;"><?php echo e($activity->note); ?></div><?php endif; ?>
                                            <div class="text-muted" style="font-size:.72rem;">
                                                <?php if($activity->due_date): ?><i class="bi bi-clock me-1"></i><?php echo e($activity->due_date->format('M d, Y')); ?> <?php echo e($activity->due_time); ?><?php endif; ?>
                                                · <?php echo e($activity->user?->name); ?>

                                            </div>
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            <form method="POST" action="<?php echo e(route('pipedrive.activities.toggle', $activity)); ?>"><?php echo csrf_field(); ?>
                                                <button class="btn btn-sm <?php echo e($activity->done ? 'btn-success' : 'btn-outline-success'); ?>" title="Mark done"><i class="bi bi-check-lg"></i></button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('pipedrive.activities.destroy', $activity)); ?>" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="crm-empty"><i class="bi bi-calendar-x"></i>No activities yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="tab-pane fade" id="tab-notes">
                        <div class="crm-panel mb-3">
                            <div class="crm-panel-body">
                                <form method="POST" action="<?php echo e(route('pipedrive.notes.store', $deal)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <textarea name="content" class="form-control" rows="3" placeholder="Write a note…" required></textarea>
                                    <div class="text-end mt-2"><button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Add note</button></div>
                                </form>
                            </div>
                        </div>

                        <div class="crm-panel">
                            <div class="crm-panel-head">Notes &amp; communication history</div>
                            <div class="crm-panel-body">
                                <?php $__empty_1 = true; $__currentLoopData = $deal->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="crm-timeline-item">
                                        <div class="crm-timeline-icon" style="background:#fff9db;color:#e67700;"><i class="bi <?php echo e($note->pinned ? 'bi-pin-angle-fill' : 'bi-sticky'); ?>"></i></div>
                                        <div class="flex-grow-1">
                                            <div style="font-size:.85rem; white-space:pre-line;"><?php echo e($note->content); ?></div>
                                            <div class="text-muted" style="font-size:.72rem;"><?php echo e($note->user?->name); ?> · <?php echo e($note->created_at->diffForHumans()); ?></div>
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            <form method="POST" action="<?php echo e(route('pipedrive.notes.pin', $note)); ?>"><?php echo csrf_field(); ?>
                                                <button class="btn btn-sm <?php echo e($note->pinned ? 'btn-warning' : 'btn-outline-warning'); ?>" title="Pin"><i class="bi bi-pin-angle"></i></button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('pipedrive.notes.destroy', $note)); ?>" onsubmit="return confirm('Delete?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="crm-empty"><i class="bi bi-sticky"></i>No notes yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="lostModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="<?php echo e(route('pipedrive.deals.lost', $deal)); ?>" class="modal-content">
                <?php echo csrf_field(); ?>
                <div class="modal-header"><h6 class="modal-title">Mark deal as lost</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <label class="form-label">Reason (optional)</label>
                    <input type="text" name="lost_reason" class="form-control" placeholder="e.g. Budget, chose competitor…">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm">Mark as lost</button>
                </div>
            </form>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/deals/show.blade.php ENDPATH**/ ?>