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
    <?php if(auth()->user()->hasRole('ADMIN')): ?>
         <?php $__env->slot('header', null, []); ?> 
            <?php echo e(__('System Setting')); ?>

         <?php $__env->endSlot(); ?>
        <?php echo $__env->make('attendance.partials.assets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <?php
            $t = fn ($v, $d) => $v ? substr($v, 0, 5) : $d;
            $workingDays = $settings->working_days ?? [1, 2, 3, 4, 5];
            $holidays = collect($settings->holidays ?? [])->map(fn ($h) => trim((string) $h, " \"'[]"))->filter()->sort()->values();
        ?>

        <div class="xa">
            <?php echo $__env->make('attendance.partials.tabs', ['active' => 'settings'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="xa-head">
                <div>
                    <h1 class="xa-title">Attendance settings</h1>
                    <div class="xa-sub">Used for punch-in rules, late / half-day / early-out status and all attendance reports. Times are Asia/Dubai.</div>
                </div>
            </div>

            <form id="settingsForm" novalidate>
                <?php echo csrf_field(); ?>
                <div class="xa-grid xa-grid-2 mb-3">
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Working week &amp; hours</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div class="xa-field">
                                <span class="xa-label">Working days</span>
                                <div class="xa-days" role="group" aria-label="Working days">
                                    <?php $__currentLoopData = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label><input type="checkbox" name="working_days[]" value="<?php echo e($d); ?>" <?php if(in_array($d, $workingDays)): echo 'checked'; endif; ?>><span><?php echo e($label); ?></span></label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="xa-help">Other days are weekends: no punch-in, not counted as absences or leave.</div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sOpen">Office opens</label>
                                    <input type="time" id="sOpen" name="office_open_time" value="<?php echo e($t($settings->office_open_time ?? null, '09:00')); ?>" class="xa-input" required>
                                </div>
                                <div class="xa-field">
                                    <label for="sClose">Office closes</label>
                                    <input type="time" id="sClose" name="office_close_time" value="<?php echo e($t($settings->office_close_time ?? null, '18:00')); ?>" class="xa-input" required>
                                    <div class="xa-help">Missed punch-outs are closed at this time.</div>
                                </div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sEarly">Early punch-in window (min)</label>
                                    <input type="number" id="sEarly" name="early_punch_in_minutes" min="0" max="240" value="<?php echo e($settings->early_punch_in_minutes ?? 15); ?>" class="xa-input">
                                    <div class="xa-help">Early punches are recorded at opening time.</div>
                                </div>
                                <div class="xa-field">
                                    <label for="sBreak">Break ends</label>
                                    <input type="time" id="sBreak" name="break_end_time" value="<?php echo e($t($settings->break_end_time ?? null, '14:00')); ?>" class="xa-input" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Late, half-day &amp; early-out rules</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sGrace">Grace period (min)</label>
                                    <input type="number" id="sGrace" name="late_threshold" min="0" max="240" value="<?php echo e($settings->late_threshold ?? 15); ?>" class="xa-input" required>
                                    <div class="xa-help">Late = after opening + this. Late minutes count from opening time.</div>
                                </div>
                                <div class="xa-field">
                                    <label for="sHalf">Half day if in after</label>
                                    <input type="time" id="sHalf" name="half_day_threshold" value="<?php echo e($t($settings->half_day_threshold ?? null, '12:00')); ?>" class="xa-input" required>
                                </div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sOut">Early out if out before</label>
                                    <input type="time" id="sOut" name="early_out_threshold" value="<?php echo e($t($settings->early_out_threshold ?? ($settings->office_close_time ?? null), '18:00')); ?>" class="xa-input" required>
                                </div>
                                <div></div>
                            </div>
                            <div class="xa-form-row">
                                <div class="xa-field">
                                    <label for="sLateDed">Late arrivals per deduction</label>
                                    <input type="number" id="sLateDed" name="late_deduction_threshold" min="1" max="31" value="<?php echo e($settings->late_deduction_threshold ?? 3); ?>" class="xa-input" required>
                                </div>
                                <div class="xa-field">
                                    <label for="sHalfDed">Half days per deduction</label>
                                    <input type="number" id="sHalfDed" name="half_day_deduction_threshold" min="1" max="31" value="<?php echo e($settings->half_day_deduction_threshold ?? 3); ?>" class="xa-input" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xa-grid xa-grid-2 mb-3">
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Public holidays</h2><span class="xa-card-note"><?php echo e($holidays->count()); ?> saved</span></div>
                        <div class="xa-card-body">
                            <div class="d-flex gap-2">
                                <input type="date" id="holidayPick" class="xa-input" aria-label="Holiday date">
                                <button type="button" class="xa-btn xa-btn-dark" id="holidayAdd">Add</button>
                            </div>
                            <input type="hidden" name="holidays" id="holidaysField" value="<?php echo e($holidays->implode(',')); ?>">
                            <div class="xa-tags" id="holidayTags"></div>
                            <div class="xa-help mt-2">Holidays aren't working days: no punch-in, no absence, and not deducted from leave.</div>
                        </div>
                    </div>
                    <div class="xa-card">
                        <div class="xa-card-head"><h2 class="xa-card-title">Leave</h2></div>
                        <div class="xa-card-body xa-stack">
                            <div>Annual allowance: <strong><?php echo e($settings->annual_leave_allowance ?? 12); ?> days</strong> per employee, leave year starts <strong>1 <?php echo e(\Carbon\Carbon::create(2000, $settings->leave_year_start_month ?? 1, 1)->format('F')); ?></strong>.</div>
                            <div class="xa-help">Leave types, allowances, carry-forward and balances are managed on the Leave policies page. Approvers need the <em>can approve leaves</em> permission (Roles).</div>
                            <div><a class="xa-btn" href="<?php echo e(route('leaves.policies')); ?>">Open leave policies</a></div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="xa-btn xa-btn-primary">Save settings</button>
                </div>
            </form>
            <div id="responseMessage" class="mt-2"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('settingsForm');
                const field = document.getElementById('holidaysField');
                const tags = document.getElementById('holidayTags');
                const today = new Date().toISOString().slice(0, 10);
                let list = field.value ? field.value.split(',').filter(Boolean) : [];

                function render() {
                    list = Array.from(new Set(list)).sort();
                    field.value = list.join(',');
                    tags.innerHTML = list.length ? list.map(d => {
                        const label = new Date(d + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'short', day: '2-digit', month: 'short', year: 'numeric' });
                        return '<span class="xa-tag ' + (d < today ? 'is-past' : '') + '">' + XA.esc(label) + '<button type="button" data-del="' + d + '" aria-label="Remove ' + XA.esc(label) + '">×</button></span>';
                    }).join('') : '<span class="xa-muted" style="font-size:.82rem">No holidays added.</span>';
                }
                render();
                document.getElementById('holidayAdd').addEventListener('click', () => {
                    const v = document.getElementById('holidayPick').value;
                    if (v) { list.push(v); render(); document.getElementById('holidayPick').value = ''; }
                });
                tags.addEventListener('click', (e) => {
                    const b = e.target.closest('[data-del]');
                    if (b) { list = list.filter(d => d !== b.dataset.del); render(); }
                });

                form.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    if (!form.querySelector('[name="working_days[]"]:checked')) {
                        XA.toast('error', 'Pick at least one working day.');
                        return;
                    }
                    const btn = form.querySelector('[type="submit"]');
                    XA.showErrors(form, {});
                    XA.busy(btn, true);
                    try {
                        const data = await XA.request(<?php echo json_encode(route('settings.update'), 15, 512) ?>, { body: new FormData(form) });
                        XA.toast('success', data.success);
                    } catch (err) {
                        XA.showErrors(form, err.errors);
                    } finally {
                        XA.busy(btn, false);
                    }
                });
            });
        </script>
    <?php else: ?>
        <?php echo $__env->make('vendor.not-allowed', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/settings/index.blade.php ENDPATH**/ ?>