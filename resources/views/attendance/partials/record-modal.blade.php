{{-- Attendance record: details, notes and (for attendance managers) corrections. --}}
@php
    $__canManage = \App\Support\Access::manageAttendance(auth()->user());
    $__statuses = ['present' => 'Present', 'late' => 'Late', 'half_day' => 'Half day', 'early_out' => 'Early out', 'absent' => 'Absent', 'leave' => 'On leave', 'holiday' => 'Holiday'];
@endphp
<div class="modal fade xa-modal xa" id="xaRecordModal" tabindex="-1" aria-labelledby="xaRecordTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="xaRecordTitle">Attendance</h5>
                    <div class="xa-sub" data-r="subtitle"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div data-r="loading" class="xa-empty">Loading…</div>

                <div data-r="view" hidden>
                    <dl class="xa-dl">
                        <dt>Status</dt><dd data-r="status"></dd>
                        <dt>Punch in</dt><dd data-r="in"></dd>
                        <dt>Punch out</dt><dd data-r="out"></dd>
                        <dt>Worked</dt><dd data-r="worked"></dd>
                        <dt>Location</dt><dd data-r="location"></dd>
                        <dt data-r="edited-k">Last edited</dt><dd data-r="edited"></dd>
                    </dl>

                    @if ($__canManage)
                        <div class="mt-3">
                            <button type="button" class="xa-btn xa-btn-sm" data-r="edit-toggle">
                                <iconify-icon icon="solar:pen-2-linear"></iconify-icon> Correct this record
                            </button>
                        </div>
                    @endif

                    <div class="mt-4">
                        <div class="xa-label mb-2">Notes &amp; history</div>
                        <div data-r="notes"></div>
                        <form data-r="note-form" class="d-flex gap-2 mt-2">
                            <input type="text" name="note" class="xa-input" maxlength="255" placeholder="Add a note…" aria-label="Add a note" required>
                            <button class="xa-btn xa-btn-dark" type="submit">Add</button>
                        </form>
                    </div>
                </div>

                @if ($__canManage)
                    <form data-r="edit-form" class="xa-stack" hidden novalidate>
                        <div data-r="create-fields" class="xa-form-row" hidden>
                            <div class="xa-field">
                                <label for="xaRecUser">Employee</label>
                                <select name="user_id" id="xaRecUser" class="xa-select">
                                    @foreach (\App\Models\User::tracked()->orderBy('name')->get(['id', 'name']) as $__u)
                                        <option value="{{ $__u->id }}">{{ $__u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="xa-field">
                                <label for="xaRecDate">Date</label>
                                <input type="date" name="date" id="xaRecDate" class="xa-input" max="{{ now()->toDateString() }}">
                            </div>
                        </div>
                        <div class="xa-form-row">
                            <div class="xa-field">
                                <label for="xaRecIn">Punch in</label>
                                <input type="time" name="punch_in" id="xaRecIn" class="xa-input">
                            </div>
                            <div class="xa-field">
                                <label for="xaRecOut">Punch out</label>
                                <input type="time" name="punch_out" id="xaRecOut" class="xa-input">
                                <div class="xa-help">Earlier than punch in = next day (overnight).</div>
                            </div>
                        </div>
                        <div class="xa-field">
                            <label for="xaRecStatus">Status</label>
                            <select name="status" id="xaRecStatus" class="xa-select">
                                <option value="">Work it out from the punch-in time</option>
                                @foreach ($__statuses as $__k => $__v)
                                    <option value="{{ $__k }}">{{ $__v }}</option>
                                @endforeach
                            </select>
                            <div class="xa-help">No punch-in → absent / leave / holiday only.</div>
                        </div>
                        <div class="xa-field">
                            <label for="xaRecReason">Reason for the change <span class="text-danger">*</span></label>
                            <input type="text" name="reason" id="xaRecReason" class="xa-input" maxlength="255" placeholder="e.g. Forgot to punch out, confirmed with manager" required>
                            <div class="xa-help">Saved in the record's notes with your name.</div>
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="xa-btn" data-r="edit-cancel">Cancel</button>
                            <button type="submit" class="xa-btn xa-btn-primary">Save</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('xaRecordModal');
    if (!modalEl || modalEl.dataset.ready) return;
    modalEl.dataset.ready = '1';
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const $ = (r) => modalEl.querySelector('[data-r="' + r + '"]');
    const urls = {
        show: @json(route('attendance.records.show', ['attendance' => '__ID__'])),
        update: @json(route('attendance.records.update', ['attendance' => '__ID__'])),
        store: @json(route('attendance.records.store')),
        note: @json(route('attendance.notes.store', ['attendanceId' => '__ID__'])),
    };
    const labels = { present: 'Present', late: 'Late', half_day: 'Half day', early_out: 'Early out', absent: 'Absent', leave: 'On leave', holiday: 'Holiday' };
    let current = null, mode = 'view';

    function show(part) {
        ['loading', 'view'].forEach(p => $(p).hidden = p !== part);
        const form = $('edit-form');
        if (form) form.hidden = part !== 'edit';
    }

    function fmt(t) {
        if (!t) return '<span class="xa-dim">—</span>';
        const [h, m] = t.split(':').map(Number);
        return XA.esc(((h % 12) || 12) + ':' + String(m).padStart(2, '0') + ' ' + (h < 12 ? 'AM' : 'PM'));
    }

    function render(data) {
        const r = data.record;
        current = r;
        $('subtitle').textContent = (r.user ? r.user.name + ' · ' : '') + r.date_label;
        $('status').innerHTML = '<span class="xa-badge xa-badge-' + XA.esc(r.status || 'absent') + '">' + XA.esc(labels[r.status] || r.status || '—') + '</span>'
            + (r.late_minutes ? ' <span class="xa-flag xa-flag-late">' + r.late_minutes + ' min late</span>' : '')
            + (r.early_minutes ? ' <span class="xa-flag xa-flag-early">left ' + r.early_minutes + ' min early</span>' : '');
        $('in').innerHTML = fmt(r.punch_in);
        $('out').innerHTML = fmt(r.punch_out) + (r.auto_closed ? ' <span class="xa-flag xa-flag-missed">Missed punch-out · closed automatically</span>' : '');
        $('worked').innerHTML = r.worked && r.worked !== '—' ? XA.esc(r.worked) : '<span class="xa-dim">—</span>';
        $('location').innerHTML = r.location
            ? '<a href="https://maps.google.com/?q=' + encodeURIComponent(r.location) + '" target="_blank" rel="noopener">' + XA.esc(r.location) + '</a>'
            : '<span class="xa-dim">Not shared</span>';
        $('edited-k').hidden = $('edited').hidden = !r.edited_by;
        $('edited').textContent = r.edited_by ? r.edited_by + ' · ' + r.edited_at : '';
        $('notes').innerHTML = data.notes.length
            ? data.notes.map(n => '<div class="xa-note">' + XA.esc(n.note) + '<small>' + XA.esc(n.user || '') + ' · ' + XA.esc(n.at || '') + '</small></div>').join('')
            : '<div class="xa-muted" style="font-size:.82rem">No notes yet.</div>';
        const toggle = $('edit-toggle');
        if (toggle) toggle.hidden = !data.can_edit;
        $('note-form').hidden = !data.can_note;
        show('view');
    }

    async function load(id) {
        show('loading');
        try {
            render(await XA.request(urls.show.replace('__ID__', id), { method: 'GET' }));
        } catch (e) {
            modal.hide();
        }
    }

    function fillForm(r) {
        const f = $('edit-form');
        if (!f) return;
        f.reset();
        XA.showErrors(f, {});
        f.punch_in.value = r && r.punch_in ? r.punch_in : '';
        f.punch_out.value = r && r.punch_out ? r.punch_out : '';
        f.status.value = r && r.status && !r.punch_in ? r.status : '';
    }

    window.XARecord = {
        open(id) {
            mode = 'view';
            $('subtitle').textContent = '';
            document.getElementById('xaRecordTitle').textContent = 'Attendance record';
            modal.show();
            load(id);
        },
        create(userId, date) {
            mode = 'create';
            current = null;
            document.getElementById('xaRecordTitle').textContent = 'Add attendance';
            $('subtitle').textContent = 'For a day with no record';
            const f = $('edit-form');
            if (!f) return;
            fillForm(null);
            $('create-fields').hidden = false;
            if (userId) f.user_id.value = userId;
            f.date.value = date || '';
            show('edit');
            modal.show();
        },
    };

    const toggle = $('edit-toggle');
    if (toggle) toggle.addEventListener('click', () => {
        mode = 'edit';
        fillForm(current);
        $('create-fields').hidden = true;
        show('edit');
    });
    const cancel = $('edit-cancel');
    if (cancel) cancel.addEventListener('click', () => mode === 'create' ? modal.hide() : show('view'));

    const form = $('edit-form');
    if (form) form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        const body = { punch_in: form.punch_in.value || null, punch_out: form.punch_out.value || null, status: form.status.value || null, reason: form.reason.value };
        let url = urls.store, method = 'POST';
        if (mode === 'create') {
            body.user_id = form.user_id.value;
            body.date = form.date.value;
        } else {
            url = urls.update.replace('__ID__', current.id);
            method = 'PUT';
        }
        XA.busy(btn, true);
        try {
            const data = await XA.request(url, { method, body });
            XA.toast('success', data.success);
            modal.hide();
            setTimeout(() => location.reload(), 400);
        } catch (err) {
            XA.showErrors(form, err.errors);
        } finally {
            XA.busy(btn, false);
        }
    });

    $('note-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const f = e.target, btn = f.querySelector('button');
        XA.busy(btn, true, '…');
        try {
            await XA.request(urls.note.replace('__ID__', current.id), { body: { note: f.note.value } });
            f.reset();
            load(current.id);
        } catch (err) { /* toast shown */ } finally {
            XA.busy(btn, false);
        }
    });

    document.addEventListener('click', (e) => {
        const el = e.target.closest('[data-record]');
        if (el && !e.target.closest('a,button')) {
            window.XARecord.open(el.dataset.record);
        }
        const add = e.target.closest('[data-add-record]');
        if (add) {
            e.preventDefault();
            window.XARecord.create(add.dataset.user || '', add.dataset.date || '');
        }
    });
});
</script>
