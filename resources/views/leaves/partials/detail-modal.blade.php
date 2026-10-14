{{-- Leave request details, balance check, timeline and review actions. Opens for any [data-leave="id"]. --}}
<div class="modal fade xa-modal xa" id="xaLeave" tabindex="-1" aria-labelledby="xaLeaveTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="xaLeaveTitle">Leave request</h5>
                    <div class="xa-sub" data-l="subtitle"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div data-l="loading" class="xa-empty">Loading…</div>
                <div data-l="body" hidden>
                    <div class="xa-grid xa-grid-2">
                        <dl class="xa-dl">
                            <dt>Status</dt><dd data-l="status"></dd>
                            <dt>Type</dt><dd data-l="type"></dd>
                            <dt>From</dt><dd data-l="start"></dd>
                            <dt>To</dt><dd data-l="end"></dd>
                            <dt>Working days</dt><dd data-l="days"></dd>
                            <dt>Reason</dt><dd data-l="reason" style="font-weight:500"></dd>
                            <dt data-l="review-k">Reviewer note</dt><dd data-l="review" style="font-weight:500"></dd>
                        </dl>
                        <div>
                            <div class="xa-label mb-2">Balance check</div>
                            <div data-l="balance"></div>
                            <div data-l="overlap" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="xa-label mt-4 mb-2">History</div>
                    <ul class="xa-timeline" data-l="events"></ul>

                    <form data-l="review-form" class="mt-3" hidden>
                        <div class="xa-field">
                            <label for="xaLeaveComment">Comment <span class="xa-muted" style="text-transform:none;letter-spacing:0">(optional, shared with the employee)</span></label>
                            <textarea id="xaLeaveComment" name="comment" class="xa-textarea" maxlength="1000"></textarea>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer" data-l="actions" hidden>
                <button type="button" class="xa-btn xa-btn-danger" data-act="cancel" hidden>Cancel request</button>
                <button type="button" class="xa-btn xa-btn-danger" data-act="revoke" hidden>Revoke approval</button>
                <button type="button" class="xa-btn xa-btn-danger" data-act="reject" hidden>Reject</button>
                <button type="button" class="xa-btn xa-btn-primary" data-act="approve" hidden>Approve</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('xaLeave');
    if (!el || el.dataset.ready) return;
    el.dataset.ready = '1';
    const modal = bootstrap.Modal.getOrCreateInstance(el);
    const $ = (k) => el.querySelector('[data-l="' + k + '"]');
    const act = (k) => el.querySelector('[data-act="' + k + '"]');
    const base = @json(url('/leaves')) + '/';
    const n = (v) => v === null || v === undefined ? '∞' : String(Math.round(v * 10) / 10);
    const verbs = { submitted: 'Submitted', approved: 'Approved', rejected: 'Rejected', cancelled: 'Cancelled by employee', revoked: 'Approval revoked' };
    let current = null;

    async function open(id) {
        $('loading').hidden = false; $('body').hidden = true; $('actions').hidden = true;
        modal.show();
        try {
            const d = await XA.request(base + id, { method: 'GET' });
            current = d;
            $('subtitle').textContent = d.employee + (d.department ? ' · ' + d.department : '');
            $('status').innerHTML = '<span class="xa-badge xa-badge-' + XA.esc(d.status) + '">' + XA.esc(d.status.charAt(0).toUpperCase() + d.status.slice(1)) + '</span>';
            $('type').innerHTML = '<span class="xa-balance-name" style="--c:' + XA.esc(d.type_color) + ';font-weight:600">' + XA.esc(d.type) + '</span>';
            $('start').textContent = d.start;
            $('end').textContent = d.end;
            $('days').textContent = n(d.days);
            $('reason').textContent = d.reason || '—';
            $('review-k').hidden = $('review').hidden = !d.review_comment;
            $('review').textContent = d.review_comment ? d.review_comment + (d.reviewer ? ' — ' + d.reviewer : '') : '';

            $('balance').innerHTML = !d.limited
                ? '<div class="xa-callout">' + XA.esc(d.type) + ' has no fixed limit.</div>'
                : '<table class="xa-table"><thead><tr><th>Year</th><th class="num">This request</th><th class="num">Entitled</th><th class="num">Used</th><th class="num">Pending</th><th class="num">Left</th></tr></thead><tbody>'
                    + d.balance.map(b => {
                        const left = b.remaining === null ? null : (d.status === 'pending' ? b.remaining - b.days : b.remaining);
                        const bad = left !== null && left < 0;
                        return '<tr><td>' + XA.esc(b.year) + '</td><td class="num">' + n(b.days) + '</td><td class="num">' + n(b.entitlement) + '</td><td class="num">' + n(b.used) + '</td><td class="num">' + n(b.pending) + '</td><td class="num"><strong style="color:' + (bad ? '#b42318' : 'inherit') + '">' + n(left) + '</strong></td></tr>';
                    }).join('') + '</tbody></table>'
                    + (d.status === 'pending' ? '<div class="xa-help">“Left” = after approving this request.</div>' : '');

            $('overlap').innerHTML = d.team_overlap.length
                ? '<div class="xa-callout is-warn"><strong>Also off:</strong> ' + d.team_overlap.map(XA.esc).join(', ') + '</div>' : '';

            $('events').innerHTML = d.events.map(e => '<li><strong>' + XA.esc(verbs[e.action] || e.action) + '</strong>' + (e.by ? ' by ' + XA.esc(e.by) : '')
                + (e.comment ? '<div>“' + XA.esc(e.comment) + '”</div>' : '') + '<small>' + XA.esc(e.at || '') + '</small></li>').join('');

            const pending = d.status === 'pending';
            act('approve').hidden = act('reject').hidden = !(d.can_review && pending);
            act('revoke').hidden = !(d.can_review && d.status === 'approved');
            act('cancel').hidden = !d.can_cancel;
            $('review-form').hidden = !(d.can_review && (pending || d.status === 'approved'));
            $('review-form').reset();
            $('actions').hidden = [act('approve'), act('reject'), act('revoke'), act('cancel')].every(b => b.hidden);
            $('loading').hidden = true; $('body').hidden = false;
        } catch (e) {
            modal.hide();
        }
    }

    async function run(action, btn) {
        if (action === 'reject' && !$('review-form').comment.value.trim() && !confirm('Reject without a comment?')) return;
        if (action === 'revoke' && !confirm('Revoke this approved leave? The days go back to the balance.')) return;
        if (action === 'cancel' && !confirm('Cancel this leave request?')) return;
        XA.busy(btn, true, '…');
        try {
            const data = await XA.request(base + current.id + '/' + action, { body: { comment: $('review-form').comment.value } });
            XA.toast('success', data.success);
            modal.hide();
            setTimeout(() => location.reload(), 500);
        } catch (e) {
            XA.busy(btn, false);
        }
    }

    ['approve', 'reject', 'revoke', 'cancel'].forEach(a => act(a).addEventListener('click', (e) => run(a, e.currentTarget)));

    window.XALeave = { open };
    document.addEventListener('click', (e) => {
        const row = e.target.closest('[data-leave]');
        if (row && !e.target.closest('a,button,input,select,textarea')) open(row.dataset.leave);
        const btn = e.target.closest('[data-leave-open]');
        if (btn) { e.preventDefault(); open(btn.dataset.leaveOpen); }
    });
});
</script>
