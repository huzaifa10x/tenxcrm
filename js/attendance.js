/* Attendance & Leave — shared helpers (10X One). No build step; plain ES2017. */
(function () {
    'use strict';

    const csrf = () => (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

    function toast(type, msg) {
        if (window.toastr) {
            window.toastr[type === 'error' ? 'error' : 'success'](msg);
        } else {
            alert(msg);
        }
    }

    /** First validation message from a Laravel 422, or a readable fallback. */
    function errorMessage(data, status) {
        if (data && data.errors) {
            const first = Object.values(data.errors)[0];
            if (first && first.length) return first[0];
        }
        if (data && (data.message || data.error)) return data.message || data.error;
        if (status === 403) return 'You are not allowed to do that.';
        if (status === 419) return 'Your session expired. Refresh the page and try again.';
        return 'Something went wrong. Please try again.';
    }

    /**
     * JSON request. Resolves with data on success, rejects with {message, errors} after
     * showing a toast (pass {quiet:true} to skip the toast).
     */
    async function request(url, { method = 'POST', body = null, quiet = false } = {}) {
        const opts = {
            method,
            headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        };
        if (body instanceof FormData) {
            opts.body = body;
        } else if (body) {
            opts.headers['Content-Type'] = 'application/json';
            opts.body = JSON.stringify(body);
        }
        let res, data = null;
        try {
            res = await fetch(url, opts);
            data = await res.json().catch(() => null);
        } catch (e) {
            const err = { message: 'Network error — check your connection and try again.', errors: {} };
            if (!quiet) toast('error', err.message);
            throw err;
        }
        if (!res.ok) {
            const err = { message: errorMessage(data, res.status), errors: (data && data.errors) || {}, status: res.status };
            if (!quiet) toast('error', err.message);
            throw err;
        }
        return data || {};
    }

    /** Show field errors under inputs with matching name. */
    function showErrors(form, errors) {
        form.querySelectorAll('.xa-error[data-auto]').forEach(n => n.remove());
        form.querySelectorAll('.is-invalid').forEach(n => n.classList.remove('is-invalid'));
        Object.entries(errors || {}).forEach(([name, msgs]) => {
            const field = form.querySelector('[name="' + name + '"]') || form.querySelector('[name="' + name + '[]"]');
            if (!field) return;
            field.classList.add('is-invalid');
            const div = document.createElement('div');
            div.className = 'xa-error';
            div.dataset.auto = '1';
            div.textContent = msgs[0];
            (field.closest('.xa-field') || field.parentElement).appendChild(div);
        });
    }

    function busy(btn, on, label) {
        if (!btn) return;
        if (on) {
            btn.dataset.label = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = label || 'Saving…';
        } else {
            btn.disabled = false;
            if (btn.dataset.label) btn.innerHTML = btn.dataset.label;
        }
    }

    const esc = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    /** Client-side table sort for th.xa-sortable[data-key] using td[data-sort]. */
    function sortable(table) {
        if (!table) return;
        table.querySelectorAll('th.xa-sortable').forEach((th, idx) => {
            th.addEventListener('click', () => {
                const dir = th.dataset.dir === 'asc' ? 'desc' : 'asc';
                table.querySelectorAll('th.xa-sortable').forEach(t => delete t.dataset.dir);
                th.dataset.dir = dir;
                const col = Array.from(th.parentNode.children).indexOf(th);
                const body = table.tBodies[0];
                const rows = Array.from(body.rows).filter(r => !r.classList.contains('xa-no-sort'));
                rows.sort((a, b) => {
                    const av = a.cells[col]?.dataset.sort ?? a.cells[col]?.textContent.trim() ?? '';
                    const bv = b.cells[col]?.dataset.sort ?? b.cells[col]?.textContent.trim() ?? '';
                    const an = parseFloat(av), bn = parseFloat(bv);
                    const cmp = (!isNaN(an) && !isNaN(bn)) ? an - bn : String(av).localeCompare(String(bv));
                    return dir === 'asc' ? cmp : -cmp;
                });
                rows.forEach(r => body.appendChild(r));
            });
        });
    }

    /** Instant text filter for rows with data-search. */
    function liveFilter(input, rows, emptyRow) {
        if (!input) return;
        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            let shown = 0;
            rows().forEach(r => {
                const hit = !q || (r.dataset.search || '').includes(q);
                r.hidden = !hit;
                shown += hit ? 1 : 0;
            });
            if (emptyRow) emptyRow.hidden = shown > 0;
        });
    }

    window.XA = { request, toast, showErrors, busy, esc, sortable, liveFilter };
})();
