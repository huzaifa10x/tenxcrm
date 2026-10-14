<?php
    $statusLabels = [
        'present' => 'On time',
        'late' => 'Late',
        'half_day' => 'Half day',
        'early_out' => 'Early out',
    ];
    $statusLabel = $statusLabels[$status] ?? ($status ? ucfirst(str_replace('_', ' ', $status)) : null);
?>

<div class="x-clock" id="xClock"
     data-state="<?php echo e($state); ?>"
     data-punch-in="<?php echo e($punchIn); ?>"
     data-in-label="<?php echo e($punchInLabel); ?>"
     data-in-url="<?php echo e(route('attendance.punchIn')); ?>"
     data-out-url="<?php echo e(route('attendance.punchOut')); ?>">

    <div class="x-clock-status">
        <span class="x-clock-dot" aria-hidden="true"></span>
        <div class="x-clock-text">
            <div class="x-clock-title" data-role="title">
                <?php switch($state):
                    case ('working'): ?> On the clock <?php break; ?>
                    <?php case ('done'): ?> Shift complete <?php break; ?>
                    <?php case ('off'): ?> <?php echo e($offReason); ?> <?php break; ?>
                    <?php default: ?> Not punched in
                <?php endswitch; ?>
            </div>
            <div class="x-clock-meta" data-role="meta">
                <?php if($state === 'working'): ?>
                    In at <?php echo e($punchInLabel); ?><?php if($statusLabel): ?> · <span class="x-clock-tag x-clock-tag-<?php echo e($status); ?>"><?php echo e($statusLabel); ?></span><?php endif; ?>
                <?php elseif($state === 'done'): ?>
                    <?php echo e($punchInLabel); ?> – <?php echo e($punchOutLabel); ?><?php if($totalHours): ?> · <?php echo e($totalHours); ?><?php endif; ?>
                <?php elseif($state === 'off'): ?>
                    No attendance needed today
                <?php else: ?>
                    <?php echo e($officeHours ? 'Office hours ' . $officeHours : 'Start your workday'); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="x-clock-timer" data-role="timer" aria-live="off" <?php if($state !== 'working'): ?> hidden <?php endif; ?>>00:00:00</div>

    <div class="x-clock-action">
        <button type="button" class="x-clock-btn x-clock-btn-in" data-role="in" <?php if($state !== 'idle'): ?> hidden <?php endif; ?>>
            <i class="bi bi-box-arrow-in-right"></i><span>Punch In</span>
        </button>
        <button type="button" class="x-clock-btn x-clock-btn-out" data-role="out" <?php if($state !== 'working'): ?> hidden <?php endif; ?>>
            <i class="bi bi-stop-circle"></i><span>Punch Out</span>
        </button>
        <button type="button" class="x-clock-btn x-clock-btn-done" data-role="done" disabled <?php if(!in_array($state, ['done', 'off'], true)): ?> hidden <?php endif; ?>>
            <i class="bi bi-check2-circle"></i><span><?php echo e($state === 'off' ? 'Day off' : 'Done for today'); ?></span>
        </button>
    </div>
</div>


<div class="modal fade" id="xClockConfirm" tabindex="-1" aria-labelledby="xClockConfirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content x-clock-modal">
            <div class="modal-body" style="padding: 24px 24px 8px;">
                <div class="x-clock-modal-icon"><i class="bi bi-stop-circle"></i></div>
                <h6 class="fw-bold mb-2" id="xClockConfirmTitle" style="font-size: 1.1rem;">Punch out for today?</h6>
                <p class="text-secondary-light mb-1" style="font-size: .9rem;">
                    You've worked <strong data-role="confirm-duration">—</strong> so far.
                </p>
                <p class="text-secondary-light mb-0" style="font-size: .85rem;">
                    Once you punch out you can't punch in again until tomorrow.
                </p>
            </div>
            <div class="modal-footer border-0" style="padding: 12px 24px 24px; gap: 8px;">
                <button type="button" class="btn btn-light border px-3" data-bs-dismiss="modal">Keep working</button>
                <button type="button" class="btn btn-dark px-3" data-role="confirm-out">Punch Out</button>
            </div>
        </div>
    </div>
</div>

<?php if (! $__env->hasRenderedOnce('b779781d-837f-4155-afce-19e49e52c2b0')): $__env->markAsRenderedOnce('b779781d-837f-4155-afce-19e49e52c2b0'); ?>
<style>
    .x-clock {
        display: flex;
        align-items: center;
        gap: 18px;
        background: #fff;
        border: 1px solid #e3e9e2;
        border-radius: 14px;
        padding: 10px 10px 10px 16px;
        box-shadow: 0 1px 2px rgba(15, 20, 18, .04), 0 4px 14px rgba(15, 20, 18, .05);
        min-width: 0;
    }

    .x-clock-status {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .x-clock-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #c3cbc5;
        flex-shrink: 0;
        box-shadow: 0 0 0 4px rgba(195, 203, 197, .25);
    }

    .x-clock[data-state="working"] .x-clock-dot {
        background: #a9e324;
        box-shadow: 0 0 0 4px rgba(169, 227, 36, .3);
        animation: x-clock-pulse 2s ease-in-out infinite;
    }

    .x-clock[data-state="done"] .x-clock-dot {
        background: #4f7a06;
        box-shadow: 0 0 0 4px rgba(79, 122, 6, .15);
    }

    @keyframes x-clock-pulse {
        50% { box-shadow: 0 0 0 7px rgba(169, 227, 36, .12); }
    }

    .x-clock-title {
        font-size: .9rem;
        font-weight: 700;
        color: #0f1412;
        line-height: 1.2;
        white-space: nowrap;
    }

    .x-clock-meta {
        font-size: .78rem;
        color: #66736b;
        margin-top: 2px;
        white-space: nowrap;
    }

    .x-clock-tag {
        font-weight: 600;
        color: #4f7a06;
    }

    .x-clock-tag-late,
    .x-clock-tag-half_day,
    .x-clock-tag-early_out {
        color: #b45309;
    }

    .x-clock-timer {
        font-variant-numeric: tabular-nums;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: .5px;
        color: #0f1412;
        padding: 6px 12px;
        border-radius: 9px;
        background: #f4f6f2;
        border: 1px solid #e3e9e2;
    }

    .x-clock-action {
        margin-left: auto;
    }

    .x-clock-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 136px;
        height: 40px;
        padding: 0 18px;
        border-radius: 10px;
        border: 1px solid transparent;
        font-size: .875rem;
        font-weight: 700;
        letter-spacing: .1px;
        cursor: pointer;
        transition: background-color .15s, box-shadow .15s, transform .05s;
    }

    .x-clock-btn i {
        font-size: 1.05rem;
    }

    .x-clock-btn:active:not(:disabled) {
        transform: translateY(1px);
    }

    .x-clock-btn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 .22rem rgba(169, 227, 36, .45);
    }

    .x-clock-btn-in {
        background: #a9e324;
        border-color: #a9e324;
        color: #0f1412;
    }

    .x-clock-btn-in:hover {
        background: #9ad01c;
        border-color: #9ad01c;
    }

    .x-clock-btn-out {
        background: #0f1412;
        border-color: #0f1412;
        color: #fff;
    }

    .x-clock-btn-out:hover {
        background: #232d27;
    }

    .x-clock-btn-done {
        background: #f4f6f2;
        border-color: #e3e9e2;
        color: #66736b;
        cursor: default;
    }

    .x-clock-btn[aria-busy="true"] {
        pointer-events: none;
        opacity: .85;
    }

    .x-clock-btn .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: .15em;
    }

    .x-clock-modal {
        border: 0;
        border-radius: 16px;
    }

    .x-clock-modal-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f4f6f2;
        color: #0f1412;
        display: grid;
        place-items: center;
        font-size: 1.3rem;
        margin-bottom: 14px;
    }

    [data-theme=dark] .x-clock {
        background: #18201b;
        border-color: #26302a;
    }

    [data-theme=dark] .x-clock-title,
    [data-theme=dark] .x-clock-timer {
        color: #fff;
    }

    [data-theme=dark] .x-clock-timer,
    [data-theme=dark] .x-clock-btn-done {
        background: #0f1412;
        border-color: #26302a;
    }

    [data-theme=dark] .x-clock-btn-out {
        background: #fff;
        color: #0f1412;
    }

    @media (max-width: 575px) {
        .x-clock {
            flex-wrap: wrap;
            width: 100%;
            gap: 12px;
        }

        .x-clock-action {
            width: 100%;
        }

        .x-clock-btn {
            width: 100%;
        }
    }
</style>

<script>
    (function () {
        const root = document.getElementById('xClock');
        if (!root || root.dataset.ready) return;
        root.dataset.ready = '1';

        const $ = (role) => root.querySelector('[data-role="' + role + '"]');
        const modalEl = document.getElementById('xClockConfirm');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        let timer = null;

        function notify(type, msg) {
            if (window.toastr) { toastr[type](msg); } else { alert(msg); }
        }

        function elapsed() {
            const start = Date.parse(root.dataset.punchIn || '');
            if (!start) return 0;
            return Math.max(0, Math.floor((Date.now() - start) / 1000));
        }

        function hms(sec) {
            const h = Math.floor(sec / 3600), m = Math.floor(sec % 3600 / 60), s = sec % 60;
            return [h, m, s].map(n => String(n).padStart(2, '0')).join(':');
        }

        function human(sec) {
            const h = Math.floor(sec / 3600), m = Math.floor(sec % 3600 / 60);
            return h ? h + 'h ' + String(m).padStart(2, '0') + 'm' : m + ' min';
        }

        function tick() { $('timer').textContent = hms(elapsed()); }

        function render(state) {
            root.dataset.state = state;
            $('in').hidden = state !== 'idle';
            $('out').hidden = state !== 'working';
            $('done').hidden = !['done', 'off'].includes(state);
            $('timer').hidden = state !== 'working';
            clearInterval(timer);
            if (state === 'working') { tick(); timer = setInterval(tick, 1000); }
        }

        function busy(btn, on, label) {
            if (on) {
                btn.dataset.html = btn.innerHTML;
                btn.setAttribute('aria-busy', 'true');
                btn.innerHTML = '<span class="spinner-border" role="status" aria-hidden="true"></span><span>' + label + '</span>';
            } else if (btn.dataset.html) {
                btn.removeAttribute('aria-busy');
                btn.innerHTML = btn.dataset.html;
            }
        }

        async function punch(type) {
            const btn = $(type);
            busy(btn, true, type === 'in' ? 'Punching in…' : 'Punching out…');
            try {
                const res = await fetch(root.dataset[type === 'in' ? 'inUrl' : 'outUrl'], {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: '{}',
                });
                const data = await res.json().catch(() => ({}));
                busy(btn, false);

                if (!res.ok || data.error) {
                    notify('error', data.error || (res.status === 419 ? 'Your session expired. Please refresh the page.' : 'Something went wrong. Please try again.'));
                    return;
                }

                notify('success', data.success);
                if (type === 'in') {
                    root.dataset.punchIn = data.punch_in;
                    root.dataset.inLabel = data.punch_in_label;
                    $('title').textContent = 'On the clock';
                    const labels = { present: 'On time', late: 'Late', half_day: 'Half day', early_out: 'Early out' };
                    const tag = data.status ? ' · <span class="x-clock-tag x-clock-tag-' + data.status + '">' + (labels[data.status] || data.status) + '</span>' : '';
                    $('meta').innerHTML = 'In at ' + data.punch_in_label + tag;
                    render('working');
                } else {
                    $('title').textContent = 'Shift complete';
                    const inLabel = root.dataset.inLabel || '';
                    $('meta').textContent = (inLabel ? inLabel + ' – ' : 'Out at ') + data.punch_out_label + (data.total_hours ? ' · ' + data.total_hours : '');
                    render('done');
                }
            } catch (e) {
                busy(btn, false);
                notify('error', 'Could not reach the server. Check your connection and try again.');
            }
        }

        $('in').addEventListener('click', () => punch('in'));

        $('out').addEventListener('click', () => {
            if (window.bootstrap && modalEl) {
                modalEl.querySelector('[data-role="confirm-duration"]').textContent = human(elapsed());
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            } else if (confirm('Punch out for today? You cannot punch in again until tomorrow.')) {
                punch('out');
            }
        });

        modalEl?.querySelector('[data-role="confirm-out"]').addEventListener('click', () => {
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            punch('out');
        });

        render(root.dataset.state);
    })();
</script>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/components/dashboards/punch-in-out.blade.php ENDPATH**/ ?>