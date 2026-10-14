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
     <?php $__env->slot('header', null, []); ?> My leave <?php $__env->endSlot(); ?>
    <?php echo $__env->make('attendance.partials.assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $n = fn ($v) => $v === null ? '∞' : rtrim(rtrim(number_format($v, 1), '0'), '.');
        $statusTabs = ['' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Cancelled'];
    ?>

    <div class="xa">
        <?php echo $__env->make('attendance.partials.tabs', ['active' => 'my-leave'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="xa-head">
            <div>
                <h1 class="xa-title">My leave</h1>
                <div class="xa-sub">Leave year <?php echo e($yearLabel); ?> (<?php echo e($yearRange[0]->format('d M Y')); ?> – <?php echo e($yearRange[1]->format('d M Y')); ?>) · Only working days are counted</div>
            </div>
            <div class="xa-actions">
                <select class="xa-select" style="width:auto" aria-label="Leave year" onchange="location.href='<?php echo e(route('leaves.index')); ?>?year='+this.value">
                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($y); ?>" <?php if($y == $year): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="button" class="xa-btn xa-btn-primary" data-bs-toggle="modal" data-bs-target="#xaApply"><iconify-icon icon="solar:calendar-add-linear"></iconify-icon> Apply for leave</button>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="xa-callout mb-3"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        
        <div class="xa-grid xa-grid-3 mb-3">
            <?php $__currentLoopData = $summary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $ent = $b['entitlement'];
                    $usedPct = $ent ? min(100, $b['used'] / max($ent, 0.01) * 100) : 0;
                    $pendPct = $ent ? min(100 - $usedPct, $b['pending'] / max($ent, 0.01) * 100) : 0;
                ?>
                <div class="xa-balance" style="--c: <?php echo e($b['type']->color); ?>">
                    <div class="xa-balance-top">
                        <div class="xa-balance-name"><?php echo e($b['type']->name); ?></div>
                        <?php if(!$b['type']->is_paid): ?><span class="xa-badge xa-badge-weekend no-dot">Unpaid</span><?php endif; ?>
                    </div>
                    <?php if($ent !== null): ?>
                        <div class="xa-balance-big"><?php echo e($n($b['remaining'])); ?> <small>of <?php echo e($n($ent)); ?> days left</small></div>
                        <div class="xa-balance-bar" role="img" aria-label="<?php echo e($n($b['used'])); ?> used, <?php echo e($n($b['pending'])); ?> pending of <?php echo e($n($ent)); ?>">
                            <span style="width: <?php echo e($usedPct); ?>%; background: <?php echo e($b['type']->color); ?>"></span>
                            <span style="width: <?php echo e($pendPct); ?>%; background: <?php echo e($b['type']->color); ?>; opacity: .35"></span>
                        </div>
                        <div class="xa-balance-rows">
                            <div><b><?php echo e($n($b['allowance'])); ?></b>Allowance</div>
                            <div><b><?php echo e($n($b['carried'] + $b['adjustment'])); ?></b>Carry/adj.</div>
                            <div><b><?php echo e($n($b['used'])); ?></b>Used</div>
                            <div><b><?php echo e($n($b['pending'])); ?></b>Pending</div>
                        </div>
                        <?php if($b['available'] < $b['remaining']): ?>
                            <div class="xa-help mt-2"><?php echo e($n(max(0, $b['available']))); ?> available to request after pending requests.</div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="xa-balance-big"><?php echo e($n($b['used'])); ?> <small>days taken</small></div>
                        <div class="xa-help mt-3">No fixed limit — every request is reviewed<?php echo e($b['pending'] ? ' · ' . $n($b['pending']) . ' pending' : ''); ?>.</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="xa-grid xa-grid-main">
            
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">My requests</h2>
                </div>
                <div class="xa-tabs px-3 mb-0" style="border-bottom:1px solid var(--x-border)">
                    <?php $__currentLoopData = $statusTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a class="xa-tab <?php echo e(($status ?? '') === $k ? 'active' : ''); ?>" href="<?php echo e(route('leaves.index', array_filter(['status' => $k, 'year' => request('year')]))); ?>">
                            <?php echo e($label); ?><?php if($k && ($counts[$k] ?? 0)): ?><span class="xa-count <?php echo e($k === 'pending' ? 'is-hot' : ''); ?>"><?php echo e($counts[$k]); ?></span><?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="xa-table-wrap">
                    <table class="xa-table">
                        <thead><tr><th>Dates</th><th>Type</th><th class="num">Days</th><th>Status</th><th class="xa-hide-sm">Reviewer note</th><th></th></tr></thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="is-clickable" data-leave="<?php echo e($r->id); ?>">
                                    <td>
                                        <div class="fw-semibold"><?php echo e($r->start_date->format('d M Y')); ?><?php echo e($r->start_date->ne($r->end_date) ? ' – ' . $r->end_date->format('d M Y') : ''); ?></div>
                                        <div class="xa-muted" style="font-size:.74rem">Applied <?php echo e($r->created_at->format('d M Y')); ?></div>
                                    </td>
                                    <td><span class="xa-balance-name" style="--c: <?php echo e($r->type->color); ?>; font-weight:600"><?php echo e($r->type->name); ?></span></td>
                                    <td class="num"><?php echo e($n($r->days)); ?></td>
                                    <td><span class="xa-badge xa-badge-<?php echo e($r->status); ?>"><?php echo e(ucfirst($r->status)); ?></span></td>
                                    <td class="xa-hide-sm" style="max-width:220px"><span class="xa-muted"><?php echo e(Str::limit($r->review_comment, 60) ?: '—'); ?></span></td>
                                    <td class="num">
                                        <?php if($r->status === 'pending'): ?>
                                            <button type="button" class="xa-btn xa-btn-sm xa-btn-danger" data-cancel="<?php echo e($r->id); ?>">Cancel</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr><td colspan="6"><div class="xa-empty"><iconify-icon icon="solar:calendar-minimalistic-linear"></iconify-icon>No leave requests<?php echo e($status ? ' with this status' : ' yet'); ?>.</div></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($requests->hasPages()): ?>
                    <div class="xa-pager"><?php echo e($requests->links()); ?></div>
                <?php endif; ?>
            </div>

            <div class="xa-stack">
                <div class="xa-card">
                    <div class="xa-card-head"><h2 class="xa-card-title">Upcoming time off</h2></div>
                    <div class="xa-card-body">
                        <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="d-flex justify-content-between align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                                <div>
                                    <div class="fw-semibold"><?php echo e($u->start_date->format('D d M')); ?><?php echo e($u->start_date->ne($u->end_date) ? ' – ' . $u->end_date->format('D d M') : ''); ?></div>
                                    <div class="xa-muted" style="font-size:.76rem"><?php echo e($u->type->name); ?> · <?php echo e($n($u->days)); ?> <?php echo e(Str::plural('day', $u->days)); ?></div>
                                </div>
                                <span class="xa-badge xa-badge-approved">Approved</span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="xa-muted">Nothing booked.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="xa-callout is-info">
                    <strong>How it works</strong><br>
                    Weekends and public holidays inside your dates aren't deducted. Pending requests hold the days until they're reviewed; you can cancel a request while it's pending. Unused annual leave resets at the start of each leave year<?php echo e($summary->firstWhere(fn ($b) => $b['type']->carry_forward_max > 0) ? ' (some types carry a few days forward)' : ''); ?>.
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade xa-modal xa" id="xaApply" tabindex="-1" aria-labelledby="xaApplyTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaApplyForm" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="xaApplyTitle">Apply for leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body xa-stack">
                    <div class="xa-field">
                        <label for="aType">Leave type</label>
                        <select name="leave_type_id" id="aType" class="xa-select" required>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $bal = $summary->firstWhere('type.id', $t->id); ?>
                                <option value="<?php echo e($t->id); ?>" data-reason="<?php echo e($t->requires_reason ? 1 : 0); ?>">
                                    <?php echo e($t->name); ?><?php echo e($bal && $bal['available'] !== null ? ' — ' . $n(max(0, $bal['available'])) . ' days available' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="aFrom">From</label>
                            <input type="date" name="start_date" id="aFrom" class="xa-input" required min="<?php echo e(now()->subDays(\App\Services\Leave\LeaveService::MAX_BACKDATE_DAYS)->toDateString()); ?>">
                        </div>
                        <div class="xa-field">
                            <label for="aTo">To</label>
                            <input type="date" name="end_date" id="aTo" class="xa-input" required>
                        </div>
                    </div>
                    <div data-preview class="xa-callout" hidden></div>
                    <div class="xa-field">
                        <label for="aReason">Reason <span data-reason-req class="text-danger" hidden>*</span></label>
                        <textarea name="reason" id="aReason" class="xa-textarea" maxlength="1000" placeholder="A short note for your manager"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="xa-btn xa-btn-primary">Submit request</button>
                </div>
            </form>
        </div>
    </div>

    <?php echo $__env->make('leaves.partials.detail-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('xaApplyForm');
        const preview = form.querySelector('[data-preview]');
        const urls = { preview: <?php echo json_encode(route('leaves.preview'), 15, 512) ?>, store: <?php echo json_encode(route('leaves.store'), 15, 512) ?>, cancel: <?php echo json_encode(route('leaves.cancel', ['leave' => '__ID__']), 512) ?> };

        if (location.hash === '#apply') bootstrap.Modal.getOrCreateInstance(document.getElementById('xaApply')).show();

        function syncReason() {
            const opt = form.leave_type_id.selectedOptions[0];
            form.querySelector('[data-reason-req]').hidden = !(opt && opt.dataset.reason === '1');
        }
        form.leave_type_id.addEventListener('change', () => { syncReason(); runPreview(); });
        syncReason();

        form.start_date.addEventListener('change', () => {
            if (!form.end_date.value || form.end_date.value < form.start_date.value) form.end_date.value = form.start_date.value;
            form.end_date.min = form.start_date.value;
            runPreview();
        });
        form.end_date.addEventListener('change', runPreview);

        let seq = 0;
        async function runPreview() {
            if (!form.start_date.value || !form.end_date.value || form.end_date.value < form.start_date.value) { preview.hidden = true; return; }
            const my = ++seq;
            try {
                const p = await XA.request(urls.preview, { body: { leave_type_id: form.leave_type_id.value, start_date: form.start_date.value, end_date: form.end_date.value }, quiet: true });
                if (my !== seq) return;
                let html = '', cls = '';
                if (!p.days) {
                    html = 'These dates are all weekends or holidays — no leave needed.'; cls = 'is-warn';
                } else {
                    html = '<strong>' + p.days + ' working ' + (p.days === 1 ? 'day' : 'days') + '</strong> will be deducted.';
                    p.years.forEach(y => {
                        if (y.available !== null) {
                            const after = y.available - y.days;
                            html += '<br>' + XA.esc(y.label) + ': ' + (after < 0
                                ? y.days + ' requested but only ' + Math.max(0, y.available) + ' available — <strong>not enough balance</strong>'
                                : Math.max(0, y.available) + ' available → ' + after + ' left after this request');
                            if (after < 0) cls = 'is-bad';
                        }
                    });
                }
                if (p.overlap) { html += '<br><strong>Overlaps an existing ' + XA.esc(p.overlap.status) + ' request.</strong>'; cls = 'is-bad'; }
                preview.className = 'xa-callout ' + cls;
                preview.innerHTML = html;
                preview.hidden = false;
            } catch (e) { preview.hidden = true; }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = form.querySelector('[type="submit"]');
            XA.showErrors(form, {});
            XA.busy(btn, true, 'Submitting…');
            try {
                const data = await XA.request(urls.store, { body: new FormData(form) });
                XA.toast('success', data.success);
                bootstrap.Modal.getInstance(document.getElementById('xaApply')).hide();
                setTimeout(() => location.href = <?php echo json_encode(route('leaves.index', ['status' => 'pending']), 512) ?>, 600);
            } catch (err) {
                XA.showErrors(form, err.errors);
            } finally {
                XA.busy(btn, false);
            }
        });

        document.addEventListener('click', async (e) => {
            const c = e.target.closest('[data-cancel]');
            if (!c) return;
            e.stopPropagation();
            if (!confirm('Cancel this leave request?')) return;
            XA.busy(c, true, '…');
            try {
                const data = await XA.request(urls.cancel.replace('__ID__', c.dataset.cancel));
                XA.toast('success', data.success);
                setTimeout(() => location.reload(), 500);
            } catch (err) { XA.busy(c, false); }
        }, true);
    });
    </script>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leaves/index.blade.php ENDPATH**/ ?>