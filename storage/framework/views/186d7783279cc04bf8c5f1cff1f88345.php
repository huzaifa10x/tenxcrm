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
     <?php $__env->slot('header', null, []); ?> Leave requests <?php $__env->endSlot(); ?>
    <?php echo $__env->make('attendance.partials.assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $n = fn ($v) => rtrim(rtrim(number_format((float) $v, 1), '0'), '.');
        $statusTabs = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled', 'all' => 'All'];
        $keep = array_filter(\Illuminate\Support\Arr::except($filters, ['status']));
    ?>

    <div class="xa">
        <?php echo $__env->make('attendance.partials.tabs', ['active' => 'leave-admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Leave requests</h1>
                <div class="xa-sub">Review, approve or reject. Every decision is logged and the employee is notified.</div>
            </div>
            <?php if($canPolicies): ?>
                <div class="xa-actions"><a class="xa-btn" href="<?php echo e(route('leaves.policies')); ?>"><iconify-icon icon="solar:settings-linear"></iconify-icon> Policies &amp; balances</a></div>
            <?php endif; ?>
        </div>

        <?php if(session('success')): ?>
            <div class="xa-callout mb-3"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="xa-grid xa-grid-main">
            <div class="xa-card">
                <div class="xa-tabs px-3 mb-0 pt-1" style="border-bottom:1px solid var(--x-border)">
                    <?php $__currentLoopData = $statusTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="xa-tab <?php echo e($status === $k ? 'active' : ''); ?>" href="<?php echo e(route('leaves.admin', $keep + ['status' => $k])); ?>">
                            <?php echo e($label); ?><?php if($k !== 'all' && ($counts[$k] ?? 0)): ?><span class="xa-count <?php echo e($k === 'pending' ? 'is-hot' : ''); ?>"><?php echo e($counts[$k]); ?></span><?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <form class="xa-filters" method="GET" action="<?php echo e(route('leaves.admin')); ?>">
                    <input type="hidden" name="status" value="<?php echo e($status); ?>">
                    <div class="xa-field" style="flex:1;min-width:150px">
                        <label for="lQ">Search</label>
                        <div class="xa-search"><iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                            <input type="search" id="lQ" name="q" class="xa-input" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Employee name"></div>
                    </div>
                    <div class="xa-field">
                        <label for="lEmp">Employee</label>
                        <select id="lEmp" name="user_id" class="xa-select">
                            <option value="">Everyone</option>
                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($e->id); ?>" <?php if(($filters['user_id'] ?? '') == $e->id): echo 'selected'; endif; ?>><?php echo e($e->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lDept">Department</label>
                        <select id="lDept" name="department" class="xa-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($d->id); ?>" <?php if(($filters['department'] ?? '') == $d->id): echo 'selected'; endif; ?>><?php echo e($d->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lType">Type</label>
                        <select id="lType" name="leave_type_id" class="xa-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>" <?php if(($filters['leave_type_id'] ?? '') == $t->id): echo 'selected'; endif; ?>><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="xa-field">
                        <label for="lFrom">From</label>
                        <input type="date" id="lFrom" name="from" class="xa-input" value="<?php echo e($filters['from'] ?? ''); ?>">
                    </div>
                    <div class="xa-field">
                        <label for="lTo">To</label>
                        <input type="date" id="lTo" name="to" class="xa-input" value="<?php echo e($filters['to'] ?? ''); ?>">
                    </div>
                    <button class="xa-btn xa-btn-dark" type="submit">Filter</button>
                    <?php if($keep): ?><a class="xa-btn" href="<?php echo e(route('leaves.admin', ['status' => $status])); ?>">Reset</a><?php endif; ?>
                </form>
                <div class="xa-table-wrap">
                    <table class="xa-table">
                        <thead><tr><th>Employee</th><th>Dates</th><th>Type</th><th class="num">Days</th><th>Status</th><th class="xa-hide-sm">Submitted</th><th></th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="is-clickable" data-leave="<?php echo e($r->id); ?>">
                                    <td>
                                        <div class="xa-person">
                                            <?php echo $__env->make('attendance.partials.avatar', ['user' => $r->user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            <span style="min-width:0">
                                                <span class="xa-person-name d-block"><?php echo e($r->user->name); ?></span>
                                                <span class="xa-person-meta d-block"><?php echo e($r->user->departmentNames() ?: '—'); ?></span>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold" style="white-space:nowrap"><?php echo e($r->start_date->format('d M')); ?><?php echo e($r->start_date->ne($r->end_date) ? ' – ' . $r->end_date->format('d M') : ''); ?> <?php echo e($r->end_date->format('Y')); ?></div>
                                        <?php if($r->reason): ?><div class="xa-muted" style="font-size:.74rem;max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo e($r->reason); ?></div><?php endif; ?>
                                    </td>
                                    <td><span class="xa-balance-name" style="--c: <?php echo e($r->type->color); ?>; font-weight:600; white-space:nowrap"><?php echo e($r->type->name); ?></span></td>
                                    <td class="num"><?php echo e($n($r->days)); ?></td>
                                    <td>
                                        <span class="xa-badge xa-badge-<?php echo e($r->status); ?>"><?php echo e(ucfirst($r->status)); ?></span>
                                        <?php if($r->reviewer): ?><div class="xa-muted" style="font-size:.72rem">by <?php echo e($r->reviewer->name); ?></div><?php endif; ?>
                                    </td>
                                    <td class="xa-hide-sm xa-muted" style="white-space:nowrap"><?php echo e($r->created_at->diffForHumans()); ?></td>
                                    <td class="num"><button type="button" class="xa-btn xa-btn-sm <?php echo e($r->status === 'pending' ? 'xa-btn-primary' : ''); ?>" data-leave-open="<?php echo e($r->id); ?>"><?php echo e($r->status === 'pending' ? 'Review' : 'View'); ?></button></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="7"><div class="xa-empty"><iconify-icon icon="solar:inbox-line-linear"></iconify-icon><?php echo e($status === 'pending' ? 'All caught up — no pending requests.' : 'No requests match these filters.'); ?></div></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($requests->hasPages()): ?><div class="xa-pager"><?php echo e($requests->links()); ?></div><?php endif; ?>
            </div>

            <div class="xa-card">
                <div class="xa-card-head"><h2 class="xa-card-title">Upcoming absences</h2><span class="xa-card-note">Next 30 days</span></div>
                <div class="xa-card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="d-flex align-items-center justify-content-between gap-2 py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>" style="cursor:pointer" data-leave="<?php echo e($u->id); ?>">
                            <div style="min-width:0">
                                <div class="fw-semibold"><?php echo e($u->user->name); ?></div>
                                <div class="xa-muted" style="font-size:.76rem"><?php echo e($u->start_date->format('D d M')); ?><?php echo e($u->start_date->ne($u->end_date) ? ' – ' . $u->end_date->format('D d M') : ''); ?> · <?php echo e($n($u->days)); ?>d</div>
                            </div>
                            <span class="xa-badge no-dot" style="background: <?php echo e($u->type->color); ?>1a; color: <?php echo e($u->type->color); ?>"><?php echo e($u->type->name); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="xa-muted">No approved leave in the next 30 days.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('leaves.partials.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leaves/admin.blade.php ENDPATH**/ ?>