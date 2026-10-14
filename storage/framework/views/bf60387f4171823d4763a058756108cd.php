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
     <?php $__env->slot('header', null, []); ?> Leave policies <?php $__env->endSlot(); ?>
    <?php echo $__env->make('attendance.partials.assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $n = fn ($v) => $v === null ? '∞' : rtrim(rtrim(number_format((float) $v, 1), '0'), '.');
        $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [$m => \Carbon\Carbon::create(2000, $m, 1)->format('F')]);
    ?>

    <div class="xa">
        <?php echo $__env->make('attendance.partials.tabs', ['active' => 'policies'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="xa-head">
            <div>
                <h1 class="xa-title">Leave policies &amp; balances</h1>
                <div class="xa-sub">Changes apply to the current leave year onwards. Past years keep the allowance they had.</div>
            </div>
        </div>

        <div class="xa-grid xa-grid-2 mb-3">
            
            <form class="xa-card" id="xaAllowance" novalidate>
                <div class="xa-card-head"><h2 class="xa-card-title">Annual leave allowance</h2></div>
                <div class="xa-card-body xa-stack">
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="pAllow">Days per employee per year</label>
                            <input type="number" id="pAllow" name="annual_leave_allowance" class="xa-input" min="0" max="365" step="1" value="<?php echo e($settings->annual_leave_allowance ?? 12); ?>" required>
                            <div class="xa-help">Default 12. Set 24 (or any number) — no code change needed.</div>
                        </div>
                        <div class="xa-field">
                            <label for="pMonth">Leave year starts</label>
                            <select id="pMonth" name="leave_year_start_month" class="xa-select">
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m); ?>" <?php if(($settings->leave_year_start_month ?? 1) == $m): echo 'selected'; endif; ?>>1 <?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="xa-help">Balances reset on this date each year.</div>
                        </div>
                    </div>
                    <div class="xa-callout is-info">
                        <strong>Reset rule:</strong> each leave year starts with the full allowance. Unused days are dropped, except for leave types with a carry-forward limit (up to that many days move to the next year). Leave that crosses the year boundary is split between the two years.
                    </div>
                    <div class="d-flex justify-content-end"><button type="submit" class="xa-btn xa-btn-primary">Save allowance</button></div>
                </div>
            </form>

            
            <div class="xa-card">
                <div class="xa-card-head">
                    <h2 class="xa-card-title">Who is tracked in attendance</h2>
                    <span class="xa-card-note">Untracked people get no absences and are left out of reports</span>
                </div>
                <div class="xa-table-wrap" style="max-height:300px">
                    <table class="xa-table">
                        <tbody>
                            <?php $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="xa-person">
                                            <?php echo $__env->make('attendance.partials.avatar', ['user' => $p], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                            <span style="min-width:0"><span class="xa-person-name d-block"><?php echo e($p->name); ?></span><span class="xa-person-meta d-block"><?php echo e($p->departmentNames() ?: $p->email); ?></span></span>
                                        </div>
                                    </td>
                                    <td class="num">
                                        <label class="xa-switch" title="Track attendance">
                                            <input type="checkbox" data-track="<?php echo e($p->id); ?>" <?php if($p->track_attendance): echo 'checked'; endif; ?> <?php if(!$canManageAttendance): echo 'disabled'; endif; ?> aria-label="Track attendance for <?php echo e($p->name); ?>">
                                            <span></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div class="xa-card mb-3">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Leave types</h2>
                <button type="button" class="xa-btn xa-btn-dark xa-btn-sm" data-type-edit="new"><iconify-icon icon="solar:add-circle-linear"></iconify-icon> Add type</button>
            </div>
            <div class="xa-table-wrap">
                <table class="xa-table">
                    <thead><tr><th>Type</th><th>Allowance</th><th class="num">Carry forward</th><th>Paid</th><th>Over balance</th><th>Reason</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($t->is_active ? '' : 'is-muted'); ?>">
                                <td><span class="xa-balance-name" style="--c: <?php echo e($t->color); ?>"><?php echo e($t->name); ?></span></td>
                                <td>
                                    <?php if($t->allowance_source === 'annual'): ?> <?php echo e($n($settings->annual_leave_allowance ?? 12)); ?> days <span class="xa-muted">(company allowance)</span>
                                    <?php elseif($t->allowance_source === 'fixed'): ?> <?php echo e($n($t->days_per_year)); ?> days
                                    <?php else: ?> <span class="xa-muted">No limit</span> <?php endif; ?>
                                </td>
                                <td class="num"><?php echo e($t->isLimited() && $t->carry_forward_max > 0 ? 'up to ' . $n($t->carry_forward_max) : '—'); ?></td>
                                <td><?php echo e($t->is_paid ? 'Paid' : 'Unpaid'); ?></td>
                                <td><?php echo e(!$t->isLimited() ? '—' : ($t->allow_negative ? 'Allowed' : 'Blocked')); ?></td>
                                <td><?php echo e($t->requires_reason ? 'Required' : 'Optional'); ?></td>
                                <td><span class="xa-badge <?php echo e($t->is_active ? 'xa-badge-approved' : 'xa-badge-cancelled'); ?>"><?php echo e($t->is_active ? 'Active' : 'Inactive'); ?></span></td>
                                <td class="num"><button type="button" class="xa-btn xa-btn-sm" data-type-edit="<?php echo e(json_encode($t->only(['id', 'code', 'name', 'color', 'allowance_source', 'days_per_year', 'carry_forward_max', 'is_paid', 'allow_negative', 'requires_reason', 'is_active']))); ?>">Edit</button></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="xa-card mb-3">
            <div class="xa-card-head">
                <h2 class="xa-card-title">Balances · <?php echo e($yearLabel); ?></h2>
                <div class="xa-actions">
                    <div class="xa-search"><iconify-icon icon="solar:magnifer-linear"></iconify-icon><input type="search" id="balQ" class="xa-input" placeholder="Search people" style="width:200px"></div>
                    <select class="xa-select" style="width:auto" onchange="location.href='<?php echo e(route('leaves.policies')); ?>?year='+this.value" aria-label="Leave year">
                        <?php for($y = $currentYear + 1; $y >= $currentYear - 3; $y--): ?>
                            <option value="<?php echo e($y); ?>" <?php if($y == $year): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="xa-table-wrap" style="max-height:520px">
                <table class="xa-table" id="balTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <?php $__currentLoopData = $limitedTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="num" title="Remaining / entitlement (used, pending)"><?php echo e($t->name); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $people->where('track_attendance', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr data-search="<?php echo e(strtolower($p->name . ' ' . $p->email)); ?>">
                                <td><span class="xa-person-name"><?php echo e($p->name); ?></span></td>
                                <?php $__currentLoopData = $balances[$p->id] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td class="num">
                                        <strong style="<?php echo e($b['remaining'] < 0 ? 'color:#b42318' : ''); ?>"><?php echo e($n($b['remaining'])); ?></strong><span class="xa-muted"> / <?php echo e($n($b['entitlement'])); ?></span>
                                        <div class="xa-muted" style="font-size:.72rem"><?php echo e($n($b['used'])); ?> used<?php echo e($b['pending'] ? ', ' . $n($b['pending']) . ' pending' : ''); ?><?php echo e(($b['carried'] || $b['adjustment']) ? ' · ' . ($b['carried'] ? '+' . $n($b['carried']) . ' cf ' : '') . ($b['adjustment'] ? ($b['adjustment'] > 0 ? '+' : '') . $n($b['adjustment']) . ' adj' : '') : ''); ?></div>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="num"><button type="button" class="xa-btn xa-btn-sm" data-adjust="<?php echo e($p->id); ?>" data-name="<?php echo e($p->name); ?>">Adjust</button></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="<?php echo e($limitedTypes->count() + 2); ?>"><div class="xa-empty">No tracked employees.</div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xa-card">
            <div class="xa-card-head"><h2 class="xa-card-title">Recent balance adjustments</h2></div>
            <div class="xa-table-wrap">
                <table class="xa-table">
                    <thead><tr><th>When</th><th>Employee</th><th>Type</th><th class="num">Year</th><th class="num">Days</th><th>Reason</th><th>By</th></tr></thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentAdjustments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="white-space:nowrap"><?php echo e($a->created_at?->format('d M Y, h:i A')); ?></td>
                                <td><?php echo e($a->balance?->user?->name); ?></td>
                                <td><?php echo e($a->balance?->type?->name); ?></td>
                                <td class="num"><?php echo e($a->balance?->year); ?></td>
                                <td class="num"><strong><?php echo e($a->days > 0 ? '+' : ''); ?><?php echo e($n($a->days)); ?></strong></td>
                                <td><?php echo e($a->reason); ?></td>
                                <td><?php echo e($a->admin?->name); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7"><div class="xa-muted py-2">No adjustments yet.</div></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="modal fade xa-modal xa" id="xaType" tabindex="-1" aria-labelledby="xaTypeTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaTypeForm" novalidate>
                <div class="modal-header"><h5 class="modal-title" id="xaTypeTitle">Leave type</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body xa-stack">
                    <div class="xa-form-row">
                        <div class="xa-field"><label for="tName">Name</label><input id="tName" name="name" class="xa-input" maxlength="60" required></div>
                        <div class="xa-field"><label for="tColor">Colour</label><input id="tColor" name="color" type="color" class="xa-input" style="padding:3px" value="#4f7a06"></div>
                    </div>
                    <div class="xa-field">
                        <label for="tSource">Allowance</label>
                        <select id="tSource" name="allowance_source" class="xa-select">
                            <?php $__currentLoopData = \App\Models\LeaveType::SOURCES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($k); ?>"><?php echo e($v); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="xa-form-row" data-limited>
                        <div class="xa-field" data-fixed><label for="tDays">Days per year</label><input id="tDays" name="days_per_year" type="number" class="xa-input" min="0" max="365" step="0.5"></div>
                        <div class="xa-field"><label for="tCarry">Max carry forward</label><input id="tCarry" name="carry_forward_max" type="number" class="xa-input" min="0" max="365" step="0.5" value="0"><div class="xa-help">0 = full reset each year.</div></div>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="is_paid" value="1"><span></span></span> Paid leave</label>
                        <label class="d-flex align-items-center gap-2 m-0" data-limited><span class="xa-switch"><input type="checkbox" name="allow_negative" value="1"><span></span></span> Allow requests beyond the balance</label>
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="requires_reason" value="1"><span></span></span> Reason required</label>
                        <label class="d-flex align-items-center gap-2 m-0"><span class="xa-switch"><input type="checkbox" name="is_active" value="1"><span></span></span> Active (employees can apply)</label>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button><button type="submit" class="xa-btn xa-btn-primary">Save</button></div>
            </form>
        </div>
    </div>

    
    <div class="modal fade xa-modal xa" id="xaAdjust" tabindex="-1" aria-labelledby="xaAdjustTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="xaAdjustForm" novalidate>
                <div class="modal-header"><div><h5 class="modal-title" id="xaAdjustTitle">Adjust balance</h5><div class="xa-sub" data-name></div></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                <div class="modal-body xa-stack">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="year" value="<?php echo e($year); ?>">
                    <div class="xa-form-row">
                        <div class="xa-field">
                            <label for="adjType">Leave type</label>
                            <select id="adjType" name="leave_type_id" class="xa-select">
                                <?php $__currentLoopData = $limitedTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="xa-field">
                            <label for="adjDays">Days (+ / −)</label>
                            <input id="adjDays" name="days" type="number" step="0.5" class="xa-input" placeholder="e.g. 2 or -1" required>
                        </div>
                    </div>
                    <div class="xa-field"><label for="adjReason">Reason</label><input id="adjReason" name="reason" class="xa-input" maxlength="255" placeholder="e.g. Worked on public holiday" required></div>
                    <div class="xa-help">Applies to leave year <?php echo e($yearLabel); ?>. Logged with your name.</div>
                </div>
                <div class="modal-footer"><button type="button" class="xa-btn" data-bs-dismiss="modal">Close</button><button type="submit" class="xa-btn xa-btn-primary">Save adjustment</button></div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const urls = {
            settings: <?php echo json_encode(route('leaves.policies.settings'), 15, 512) ?>,
            typeStore: <?php echo json_encode(route('leaves.types.store'), 15, 512) ?>,
            typeUpdate: <?php echo json_encode(route('leaves.types.update', ['type' => '__ID__']), 512) ?>,
            adjust: <?php echo json_encode(route('leaves.balances.adjust'), 15, 512) ?>,
            tracking: <?php echo json_encode(route('leaves.tracking', ['user' => '__ID__']), 512) ?>,
        };
        const reload = () => setTimeout(() => location.reload(), 600);

        async function submit(form, url, method, body) {
            const btn = form.querySelector('[type="submit"]');
            XA.showErrors(form, {});
            XA.busy(btn, true);
            try {
                const data = await XA.request(url, { method, body });
                XA.toast('success', data.success);
                return data;
            } catch (err) {
                XA.showErrors(form, err.errors);
                throw err;
            } finally {
                XA.busy(btn, false);
            }
        }

        const allowance = document.getElementById('xaAllowance');
        allowance.addEventListener('submit', (e) => {
            e.preventDefault();
            submit(allowance, urls.settings, 'POST', { annual_leave_allowance: allowance.annual_leave_allowance.value, leave_year_start_month: allowance.leave_year_start_month.value }).then(reload).catch(() => {});
        });

        // Types
        const typeModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('xaType'));
        const tf = document.getElementById('xaTypeForm');
        let editing = null;
        function syncSource() {
            const src = tf.allowance_source.value;
            tf.querySelectorAll('[data-limited]').forEach(n => n.hidden = src === 'none');
            tf.querySelector('[data-fixed]').hidden = src !== 'fixed';
        }
        tf.allowance_source.addEventListener('change', syncSource);
        document.querySelectorAll('[data-type-edit]').forEach(b => b.addEventListener('click', () => {
            const t = b.dataset.typeEdit === 'new' ? null : JSON.parse(b.dataset.typeEdit);
            editing = t;
            tf.reset();
            XA.showErrors(tf, {});
            document.getElementById('xaTypeTitle').textContent = t ? 'Edit ' + t.name : 'Add leave type';
            tf.name.value = t ? t.name : '';
            tf.color.value = t ? t.color : '#4f7a06';
            tf.allowance_source.value = t ? t.allowance_source : 'fixed';
            tf.allowance_source.querySelector('option[value="annual"]').disabled = false;
            if (t && t.code === 'annual') Array.from(tf.allowance_source.options).forEach(o => o.disabled = o.value !== 'annual');
            else Array.from(tf.allowance_source.options).forEach(o => o.disabled = false);
            tf.days_per_year.value = t && t.days_per_year !== null ? t.days_per_year : '';
            tf.carry_forward_max.value = t ? t.carry_forward_max : 0;
            tf.is_paid.checked = t ? !!t.is_paid : true;
            tf.allow_negative.checked = t ? !!t.allow_negative : false;
            tf.requires_reason.checked = t ? !!t.requires_reason : true;
            tf.is_active.checked = t ? !!t.is_active : true;
            syncSource();
            typeModal.show();
        }));
        tf.addEventListener('submit', (e) => {
            e.preventDefault();
            const body = {
                name: tf.name.value, color: tf.color.value, allowance_source: tf.allowance_source.value,
                days_per_year: tf.days_per_year.value || null, carry_forward_max: tf.carry_forward_max.value || 0,
                is_paid: tf.is_paid.checked, allow_negative: tf.allow_negative.checked, requires_reason: tf.requires_reason.checked, is_active: tf.is_active.checked,
            };
            submit(tf, editing ? urls.typeUpdate.replace('__ID__', editing.id) : urls.typeStore, editing ? 'PUT' : 'POST', body)
                .then(() => { typeModal.hide(); reload(); }).catch(() => {});
        });

        // Adjust
        const adjModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('xaAdjust'));
        const af = document.getElementById('xaAdjustForm');
        document.querySelectorAll('[data-adjust]').forEach(b => b.addEventListener('click', () => {
            af.reset();
            XA.showErrors(af, {});
            af.user_id.value = b.dataset.adjust;
            af.querySelector('[data-name]').textContent = b.dataset.name;
            adjModal.show();
        }));
        af.addEventListener('submit', (e) => {
            e.preventDefault();
            submit(af, urls.adjust, 'POST', Object.fromEntries(new FormData(af))).then(() => { adjModal.hide(); reload(); }).catch(() => {});
        });

        // Tracking
        document.querySelectorAll('[data-track]').forEach(cb => cb.addEventListener('change', async () => {
            try {
                const d = await XA.request(urls.tracking.replace('__ID__', cb.dataset.track), { body: { track_attendance: cb.checked } });
                XA.toast('success', d.success);
            } catch (e) { cb.checked = !cb.checked; }
        }));

        XA.liveFilter(document.getElementById('balQ'), () => document.querySelectorAll('#balTable tbody tr[data-search]'));
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/leaves/policies.blade.php ENDPATH**/ ?>