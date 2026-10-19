
<style>
    .tc-card { display: flex; flex-direction: column; }
    .tc-count { font-size: .67rem; font-weight: 700; padding: .12rem .5rem; border-radius: 20px; background: var(--ts-accent-s); color: var(--ts-accent); }

    .tc-thread {
        position: relative;
        height: 460px;
        overflow-y: auto;
        padding: .9rem 1rem .6rem;
        background: #f3f5f9;
        background-image: radial-gradient(rgba(15, 23, 42, .045) 1px, transparent 1px);
        background-size: 18px 18px;
        scroll-behavior: smooth;
        overscroll-behavior: contain;
    }
    .tc-thread::-webkit-scrollbar { width: 6px; }
    .tc-thread::-webkit-scrollbar-thumb { background: #cfd6e2; border-radius: 6px; }

    .tc-state { height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: var(--ts-muted); font-size: .8rem; gap: .4rem; }
    .tc-state .tc-ico { width: 30px; height: 30px; color: #c3cad6; stroke-width: 1.6; }
    .tc-state .tc-retry { margin-top: .3rem; }

    .tc-skel { display: flex; gap: .55rem; margin-bottom: .9rem; }
    .tc-skel span { display: block; border-radius: 12px; background: linear-gradient(90deg, #e6eaf1 25%, #eef1f6 50%, #e6eaf1 75%); background-size: 200% 100%; animation: tc-shimmer 1.2s infinite; }
    .tc-skel .a { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; }
    .tc-skel .b { height: 44px; width: 55%; }
    .tc-skel.mine { justify-content: flex-end; }
    @keyframes tc-shimmer { to { background-position: -200% 0; } }

    .tc-day { display: flex; justify-content: center; margin: .9rem 0 .7rem; }
    .tc-day span { font-size: .66rem; font-weight: 700; letter-spacing: .3px; color: var(--ts-sub); background: rgba(255, 255, 255, .95); border: 1px solid var(--ts-border); padding: .2rem .7rem; border-radius: 20px; box-shadow: 0 1px 2px rgba(15, 23, 42, .05); }

    .tc-msg { display: flex; align-items: flex-end; gap: .5rem; margin-top: .8rem; }
    .tc-msg.cont { margin-top: .18rem; }
    .tc-msg.mine { flex-direction: row-reverse; }

    .tc-avatar { width: 32px; height: 32px; border-radius: 50%; flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 800; color: #fff; user-select: none; }
    .tc-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .tc-msg.cont .tc-avatar, .tc-msg.mine .tc-avatar { visibility: hidden; }
    .tc-msg.mine .tc-avatar { width: 0; margin: 0; }

    .tc-col { display: flex; flex-direction: column; max-width: min(78%, 620px); min-width: 0; }
    .tc-msg.mine .tc-col { align-items: flex-end; }
    .tc-name { font-size: .7rem; font-weight: 700; color: var(--ts-sub); margin: 0 0 .2rem .35rem; }
    .tc-msg.cont .tc-name, .tc-msg.mine .tc-name { display: none; }

    .tc-row { display: flex; align-items: center; gap: .25rem; }
    .tc-msg.mine .tc-row { flex-direction: row-reverse; }

    .tc-bubble { position: relative; padding: .5rem .75rem .35rem; border-radius: 4px 14px 14px 14px; background: #fff; border: 1px solid #e3e8ef; box-shadow: 0 1px 1px rgba(15, 23, 42, .04); min-width: 0; transition: box-shadow .25s, background .25s; }
    .tc-msg.cont .tc-bubble { border-radius: 14px; }
    .tc-msg.mine .tc-bubble { background: #e8f0fe; border-color: #cfdcf7; background: color-mix(in srgb, var(--ts-accent) 9%, #fff); border-color: color-mix(in srgb, var(--ts-accent) 22%, #fff); border-radius: 14px 4px 14px 14px; }
    .tc-msg.mine.cont .tc-bubble { border-radius: 14px; }
    .tc-msg.flash .tc-bubble { box-shadow: 0 0 0 3px color-mix(in srgb, var(--ts-accent) 35%, transparent); }

    .tc-body { font-size: .82rem; line-height: 1.5; color: var(--ts-text); word-wrap: break-word; overflow-wrap: anywhere; }
    .tc-body p, .tc-body ul, .tc-body ol { margin: 0 0 .35rem; }
    .tc-body p:last-child, .tc-body ul:last-child, .tc-body ol:last-child { margin-bottom: 0; }
    .tc-body ul, .tc-body ol { padding-left: 1.2rem; }
    .tc-body a { color: var(--ts-accent); text-decoration: underline; }
    .tc-body img { max-width: 100%; height: auto; border-radius: 6px; }

    .tc-meta { display: flex; justify-content: flex-end; align-items: center; gap: .3rem; margin-top: .15rem; font-size: .62rem; color: var(--ts-muted); white-space: nowrap; }

    .tc-quote { display: block; width: 100%; text-align: left; border: 0; border-left: 3px solid var(--ts-accent); background: rgba(15, 23, 42, .045); border-radius: 6px; padding: .3rem .55rem; margin-bottom: .35rem; cursor: pointer; }
    .tc-quote b { display: block; font-size: .68rem; color: var(--ts-accent); }
    .tc-quote span { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: .74rem; color: var(--ts-sub); white-space: pre-line; }

    .tc-actions { display: flex; gap: .1rem; opacity: 0; transition: opacity .15s; }
    .tc-msg:hover .tc-actions, .tc-msg:focus-within .tc-actions { opacity: 1; }
    .tc-actions button { width: 26px; height: 26px; border: 0; border-radius: 50%; background: transparent; color: var(--ts-muted); display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; }
    .tc-actions button:hover { background: #fff; color: var(--ts-text); box-shadow: 0 1px 3px rgba(15, 23, 42, .12); }
    .tc-actions button.danger:hover { color: var(--ts-danger); }
    @media (hover: none) { .tc-actions { opacity: .75; } }

    .tc-jump { position: sticky; bottom: .4rem; margin-left: auto; display: none; align-items: center; gap: .3rem; width: max-content; border: 0; border-radius: 20px; background: var(--ts-accent); color: #fff; font-size: .72rem; font-weight: 700; padding: .3rem .8rem; box-shadow: 0 4px 14px rgba(37, 99, 235, .35); cursor: pointer; z-index: 2; }
    .tc-jump.show { display: flex; }

    .tc-composer { border-top: 1px solid var(--ts-border); background: #fff; padding: .6rem .75rem .55rem; }
    .tc-context { display: none; align-items: center; gap: .5rem; padding: .4rem .6rem; margin-bottom: .45rem; border-left: 3px solid var(--ts-accent); border-radius: 6px; background: var(--ts-accent-s); }
    .tc-context.show { display: flex; }
    .tc-context.editing { border-left-color: var(--ts-warning); background: var(--ts-warning-s); }
    .tc-context div { flex: 1; min-width: 0; }
    .tc-context b { display: block; font-size: .68rem; color: var(--ts-accent); }
    .tc-context.editing b { color: var(--ts-warning); }
    .tc-context span { display: block; font-size: .74rem; color: var(--ts-sub); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tc-context button { border: 0; background: transparent; color: var(--ts-muted); cursor: pointer; padding: .2rem; border-radius: 50%; }
    .tc-context button:hover { background: rgba(15, 23, 42, .06); color: var(--ts-text); }

    .tc-input-row { display: flex; align-items: flex-end; gap: .5rem; }
    .tc-input {
        flex: 1; min-height: 40px; max-height: 160px; resize: none; overflow-y: auto;
        border: 1px solid var(--ts-border); border-radius: 20px; padding: .55rem .95rem;
        font-size: .82rem; line-height: 1.4; color: var(--ts-text); background: var(--ts-bg); outline: none;
        transition: border-color var(--ts-ease), box-shadow var(--ts-ease), background var(--ts-ease);
    }
    .tc-input:focus { border-color: var(--ts-accent); box-shadow: 0 0 0 3px rgba(37, 99, 235, .1); background: #fff; }
    .tc-input:disabled { opacity: .7; }
    .tc-send { width: 40px; height: 40px; flex-shrink: 0; border: 0; border-radius: 50%; background: var(--ts-accent); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; cursor: pointer; transition: background var(--ts-ease), transform var(--ts-ease), opacity var(--ts-ease); }
    .tc-send:hover:not(:disabled) { background: var(--ts-accent-d); transform: scale(1.05); }
    .tc-send:disabled { opacity: .45; cursor: not-allowed; }
    .tc-send .spinner-border { width: 1rem; height: 1rem; border-width: .15em; }
    .tc-ico { width: 15px; height: 15px; display: block; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .tc-send .tc-ico { width: 18px; height: 18px; }

    .tc-foot { display: flex; justify-content: space-between; align-items: center; gap: .5rem; margin-top: .35rem; padding: 0 .3rem; font-size: .64rem; color: var(--ts-muted); }
    .tc-foot .over { color: var(--ts-danger); font-weight: 700; }
    .tc-error { display: none; align-items: center; gap: .5rem; margin-bottom: .45rem; padding: .45rem .65rem; border-radius: 8px; background: var(--ts-danger-s); border: 1px solid #fecaca; color: var(--ts-danger); font-size: .75rem; }
    .tc-error.show { display: flex; }
    .tc-error span { flex: 1; }
    .tc-error button { border: 0; background: transparent; color: var(--ts-danger); font-weight: 700; font-size: .72rem; cursor: pointer; text-decoration: underline; }

    @media (max-width: 767px) {
        .tc-thread { height: 60vh; min-height: 300px; padding: .7rem .6rem .5rem; }
        .tc-col { max-width: 86%; }
        .tc-hint { display: none; }
    }
</style>

<div class="ts-card tc-card" id="task-comments-<?php echo e($task->id); ?>" data-task-id="<?php echo e($task->id); ?>">
    <div class="ts-card-header">
        <span class="ts-card-title"><i class="bi bi-chat-dots me-1"></i>Comments</span>
        <span class="tc-count" data-role="count" hidden>0</span>
    </div>

    <div class="tc-thread" data-role="thread" aria-live="polite">
        <div data-role="list">
            <div class="tc-skel"><span class="a"></span><span class="b"></span></div>
            <div class="tc-skel mine"><span class="b" style="width:40%"></span></div>
            <div class="tc-skel"><span class="a"></span><span class="b" style="width:65%"></span></div>
        </div>
        <button type="button" class="tc-jump" data-role="jump"><svg class="tc-ico" viewBox="0 0 24 24"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>New comments</button>
    </div>

    <div class="tc-composer">
        <div class="tc-error" data-role="error" role="alert">
            <svg class="tc-ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg><span data-role="error-text"></span>
            <button type="button" data-role="error-retry">Retry</button>
        </div>
        <div class="tc-context" data-role="context">
            <div><b data-role="context-title"></b><span data-role="context-text"></span></div>
            <button type="button" data-role="context-cancel" title="Cancel (Esc)" aria-label="Cancel"><svg class="tc-ico" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
        </div>
        <div class="tc-input-row">
            <textarea class="tc-input" data-role="input" rows="1" maxlength="10000"
                placeholder="Write a comment…" aria-label="Write a comment"></textarea>
            <button type="button" class="tc-send" data-role="send" title="Send" aria-label="Send" disabled></button>
        </div>
        <div class="tc-foot">
            <span class="tc-hint">Enter to send · Shift + Enter for a new line</span>
            <span data-role="counter"></span>
        </div>
    </div>
</div>

<script>
    (function() {
        const root = document.getElementById('task-comments-<?php echo e($task->id); ?>');
        if (!root) return;

        // One live instance at a time: tear down whatever an earlier task load left behind.
        if (window.__taskComments) window.__taskComments.destroy();

        const cfg = {
            taskId: <?php echo e((int) $task->id); ?>,
            meId: <?php echo e((int) auth()->id()); ?>,
            csrf: <?php echo json_encode(csrf_token(), 15, 512) ?>,
            listUrl: <?php echo json_encode(route('comments.index', $task->id), 512) ?>,
            storeUrl: <?php echo json_encode(route('comments.store'), 15, 512) ?>,
            itemUrl: <?php echo json_encode(route('comments.update', ['id' => '__ID__']), 512) ?>,
            avatarBase: <?php echo json_encode(asset('/public/storage'), 15, 512) ?>,
            maxLength: 10000,
            pollMs: 15000,
            groupGapMs: 5 * 60 * 1000,
        };

        const $ = (role) => root.querySelector('[data-role="' + role + '"]');
        const els = {
            thread: $('thread'), list: $('list'), jump: $('jump'), count: $('count'),
            input: $('input'), send: $('send'), counter: $('counter'),
            error: $('error'), errorText: $('error-text'), errorRetry: $('error-retry'),
            context: $('context'), contextTitle: $('context-title'), contextText: $('context-text'), contextCancel: $('context-cancel'),
        };

        const state = {
            comments: [],      // sorted oldest → newest
            nodes: new Map(),  // id → { el, sig }
            signature: '',
            loaded: false,
            fetching: null,
            sending: false,
            mode: null,        // null | { type: 'reply'|'edit', comment }
            pendingToken: null,
            lastFailed: null,
            timer: null,
            destroyed: false,
        };

        /* ── helpers ───────────────────────────────────────── */
        const svg = (d) => '<svg class="tc-ico" viewBox="0 0 24 24" aria-hidden="true">' + d + '</svg>';
        const ICON = {
            send: svg('<path d="M22 2 11 13"/><path d="M22 2 15 22l-4-9-9-4 20-7z"/>'),
            check: svg('<path d="M20 6 9 17l-5-5"/>'),
            reply: svg('<path d="M9 17 4 12l5-5"/><path d="M20 18v-2a4 4 0 0 0-4-4H4"/>'),
            copy: svg('<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>'),
            edit: svg('<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>'),
            trash: svg('<path d="M3 6h18"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>'),
            chat: svg('<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'),
            offline: svg('<path d="M1 1l22 22"/><path d="M16.7 11.1A11 11 0 0 1 19 12.5M5 12.5a11 11 0 0 1 5.2-2.4M10.7 5.1A16 16 0 0 1 22.6 9M1.4 9a16 16 0 0 1 4.4-2.7M8.5 16.1a6 6 0 0 1 7 0M12 20h.01"/>'),
        };

        const esc = (s) => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const uuid = () => (window.crypto && crypto.randomUUID) ? crypto.randomUUID() : Date.now().toString(36) + Math.random().toString(36).slice(2);
        const palette = ['#2563eb', '#7c3aed', '#db2777', '#ea580c', '#059669', '#0891b2', '#4f46e5', '#b45309'];
        const colorFor = (id) => palette[Math.abs(Number(id) || 0) % palette.length];
        const initials = (name) => String(name || '?').trim().split(/\s+/).slice(0, 2).map(p => p[0]).join('').toUpperCase() || '?';
        const timeFmt = new Intl.DateTimeFormat(undefined, { hour: 'numeric', minute: '2-digit' });
        const fullFmt = new Intl.DateTimeFormat(undefined, { dateStyle: 'full', timeStyle: 'short' });
        const dayKey = (d) => d.getFullYear() + '-' + d.getMonth() + '-' + d.getDate();

        function dayLabel(d) {
            const today = new Date(); today.setHours(0, 0, 0, 0);
            const that = new Date(d); that.setHours(0, 0, 0, 0);
            const diff = Math.round((today - that) / 86400000);
            if (diff === 0) return 'Today';
            if (diff === 1) return 'Yesterday';
            if (diff > 1 && diff < 7) return that.toLocaleDateString(undefined, { weekday: 'long' });
            return that.toLocaleDateString(undefined, { day: 'numeric', month: 'short', year: that.getFullYear() === today.getFullYear() ? undefined : 'numeric' });
        }

        function request(method, url, body) {
            return fetch(url, {
                method,
                credentials: 'same-origin',
                headers: Object.assign(
                    { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': cfg.csrf },
                    body ? { 'Content-Type': 'application/json' } : {}
                ),
                body: body ? JSON.stringify(body) : undefined,
            }).then(async (res) => {
                let data = null;
                try { data = await res.json(); } catch (e) { /* non-JSON (e.g. session expired page) */ }
                if (!res.ok) {
                    let msg = (data && (data.message || (data.errors && Object.values(data.errors)[0]?.[0]))) || null;
                    if (res.status === 419) msg = 'Your session expired. Refresh the page and try again.';
                    if (res.status === 401) msg = 'You are signed out. Sign in again and retry.';
                    const err = new Error(msg || ('Request failed (' + res.status + ').'));
                    err.status = res.status;
                    throw err;
                }
                if (!data) throw new Error('Unexpected response from the server.');
                return data;
            });
        }

        const nearBottom = () => els.thread.scrollHeight - els.thread.scrollTop - els.thread.clientHeight < 80;
        function scrollToBottom(smooth) {
            els.thread.style.scrollBehavior = smooth ? 'smooth' : 'auto';
            els.thread.scrollTop = els.thread.scrollHeight;
            els.thread.style.scrollBehavior = '';
            els.jump.classList.remove('show');
        }

        /* ── rendering (keyed: existing bubbles are reused, never rebuilt) ── */
        function avatarHtml(user) {
            const fallback = '<span>' + esc(initials(user.name)) + '</span>';
            if (!user.img) return fallback;
            return '<img src="' + esc(cfg.avatarBase + '/' + user.img) + '" alt="" loading="lazy" onerror="this.replaceWith(Object.assign(document.createElement(\'span\'),{textContent:' + esc(JSON.stringify(initials(user.name))) + '}))">';
        }

        function buildNode(c) {
            const el = document.createElement('div');
            el.className = 'tc-msg' + (c.is_mine ? ' mine' : '');
            el.dataset.id = c.id;
            const created = new Date(c.created_at);

            const quote = c.reply_to
                ? '<button type="button" class="tc-quote" data-act="goto" data-target="' + c.reply_to.id + '"><b>' + esc(c.reply_to.user_name) + '</b><span>' + esc(c.reply_to.excerpt) + '</span></button>'
                : '';

            const actions = '<div class="tc-actions">'
                + '<button type="button" data-act="reply" title="Reply" aria-label="Reply">' + ICON.reply + '</button>'
                + '<button type="button" data-act="copy" title="Copy text" aria-label="Copy text">' + ICON.copy + '</button>'
                + (c.is_mine
                    ? '<button type="button" data-act="edit" title="Edit" aria-label="Edit">' + ICON.edit + '</button>'
                    + '<button type="button" data-act="delete" class="danger" title="Delete" aria-label="Delete">' + ICON.trash + '</button>'
                    : '')
                + '</div>';

            el.innerHTML =
                '<div class="tc-avatar" style="background:' + colorFor(c.user.id) + '">' + avatarHtml(c.user) + '</div>'
                + '<div class="tc-col">'
                + '<div class="tc-name">' + esc(c.user.name) + '</div>'
                + '<div class="tc-row">'
                + '<div class="tc-bubble">' + quote
                + '<div class="tc-body"></div>'
                + '<div class="tc-meta" title="' + esc(fullFmt.format(created)) + '">'
                + (c.edited ? '<span>edited</span>·' : '')
                + '<span>' + esc(timeFmt.format(created)) + '</span>'
                + '</div></div>'
                + actions
                + '</div></div>';

            // Body is HTML that the server already purified.
            el.querySelector('.tc-body').innerHTML = c.body;
            return el;
        }

        const sigOf = (c) => [c.updated_at, c.edited, c.body, c.reply_to ? c.reply_to.id + ':' + c.reply_to.excerpt : '', c.user.name, c.user.img].join('|');

        function render(forceBottom) {
            const wasNearBottom = nearBottom();
            const prevLast = state.lastRenderedId;

            els.count.hidden = state.comments.length === 0;
            els.count.textContent = state.comments.length;

            if (!state.comments.length) {
                state.nodes.clear();
                state.signature = '';
                els.list.innerHTML = '<div class="tc-state">' + ICON.chat + '<div><b>No comments yet</b></div><div>Start the conversation — updates, questions and decisions about this task go here.</div></div>';
                state.lastRenderedId = null;
                return;
            }

            // Nothing changed? Leave the DOM alone (this is what removes the flicker on refresh).
            const signature = state.comments.map(c => c.id + '@' + sigOf(c)).join(',');
            if (signature === state.signature && els.list.querySelector('.tc-msg')) return;
            state.signature = signature;

            const frag = document.createDocumentFragment();
            const keep = new Set();
            let prev = null;

            state.comments.forEach((c) => {
                const created = new Date(c.created_at);
                if (!prev || dayKey(new Date(prev.created_at)) !== dayKey(created)) {
                    const day = document.createElement('div');
                    day.className = 'tc-day';
                    day.innerHTML = '<span>' + esc(dayLabel(created)) + '</span>';
                    frag.appendChild(day);
                    prev = null;
                }

                const sig = sigOf(c);
                let entry = state.nodes.get(c.id);
                if (!entry || entry.sig !== sig) {
                    entry = { el: buildNode(c), sig };
                    state.nodes.set(c.id, entry);
                }

                const grouped = prev && prev.user_id === c.user_id
                    && !c.reply_to
                    && (created - new Date(prev.created_at)) < cfg.groupGapMs;
                entry.el.classList.toggle('cont', !!grouped);

                frag.appendChild(entry.el);
                keep.add(c.id);
                prev = c;
            });

            for (const id of state.nodes.keys()) if (!keep.has(id)) state.nodes.delete(id);

            els.list.replaceChildren(frag);

            const last = state.comments[state.comments.length - 1];
            state.lastRenderedId = last.id;

            if (forceBottom || wasNearBottom) {
                scrollToBottom(!forceBottom && state.loaded);
            } else if (prevLast !== last.id) {
                els.jump.classList.add('show');
            }
        }

        /* ── data ───────────────────────────────────────────── */
        function load(opts = {}) {
            if (state.fetching) return state.fetching; // never run two list requests at once
            state.fetching = request('GET', cfg.listUrl)
                .then((data) => {
                    if (state.destroyed) return;
                    state.comments = data.comments || [];
                    const first = !state.loaded;
                    state.loaded = true;
                    render(first || opts.bottom);
                })
                .catch((err) => {
                    if (state.destroyed) return;
                    if (!state.loaded) {
                        els.list.innerHTML = '<div class="tc-state">' + ICON.offline + '<div><b>Couldn\'t load comments</b></div><div>' + esc(err.message) + '</div><button type="button" class="ts-btn ts-btn-outline ts-btn-sm tc-retry" data-act="reload">Try again</button></div>';
                    }
                })
                .finally(() => { state.fetching = null; });
            return state.fetching;
        }

        function upsert(comment) {
            const i = state.comments.findIndex(c => c.id === comment.id);
            if (i === -1) state.comments.push(comment);
            else state.comments[i] = comment;
            state.comments.sort((a, b) => (a.created_at < b.created_at ? -1 : a.created_at > b.created_at ? 1 : a.id - b.id));
        }

        /* ── composer ───────────────────────────────────────── */
        function autosize() {
            els.input.style.height = 'auto';
            els.input.style.height = Math.min(els.input.scrollHeight, 160) + 'px';
        }

        function updateComposer() {
            const len = els.input.value.length;
            const blank = els.input.value.trim() === '';
            els.send.disabled = state.sending || blank || len > cfg.maxLength;
            els.counter.textContent = len > cfg.maxLength * 0.8 ? len.toLocaleString() + ' / ' + cfg.maxLength.toLocaleString() : '';
            els.counter.className = len > cfg.maxLength ? 'over' : '';
            autosize();
        }

        function setSending(on) {
            state.sending = on;
            els.input.disabled = on;
            els.send.innerHTML = on
                ? '<span class="spinner-border" role="status" aria-label="Sending"></span>'
                : (state.mode && state.mode.type === 'edit' ? ICON.check : ICON.send);
            updateComposer();
        }

        function showError(message, retryable) {
            els.errorText.textContent = message;
            els.errorRetry.style.display = retryable ? '' : 'none';
            els.error.classList.add('show');
        }
        const clearError = () => els.error.classList.remove('show');

        function setMode(mode) {
            state.mode = mode;
            if (!mode) {
                els.context.classList.remove('show', 'editing');
            } else {
                els.context.classList.add('show');
                els.context.classList.toggle('editing', mode.type === 'edit');
                els.contextTitle.textContent = mode.type === 'edit' ? 'Editing your comment' : 'Replying to ' + mode.comment.user.name;
                els.contextText.textContent = (mode.comment.text || '').replace(/\s+/g, ' ');
            }
            els.input.placeholder = mode && mode.type === 'edit' ? 'Edit your comment…' : 'Write a comment…';
            setSending(state.sending);
        }

        function cancelMode() {
            if (state.mode && state.mode.type === 'edit') {
                els.input.value = state.mode.draft || '';
            }
            setMode(null);
            updateComposer();
        }

        function submit() {
            if (state.sending) return; // blocks double clicks / double Enter
            const text = els.input.value.trim();
            if (!text || text.length > cfg.maxLength) return;
            clearError();

            const mode = state.mode;
            let call;
            if (mode && mode.type === 'edit') {
                call = request('PUT', cfg.itemUrl.replace('__ID__', mode.comment.id), { comment: text });
            } else {
                // Same token on retry → the server returns the already-saved comment instead of a duplicate.
                state.pendingToken = state.pendingToken || uuid();
                call = request('POST', cfg.storeUrl, {
                    task_id: cfg.taskId,
                    comment: text,
                    reply_to_id: mode && mode.type === 'reply' ? mode.comment.id : null,
                    client_token: state.pendingToken,
                });
            }

            setSending(true);
            call.then((data) => {
                    if (state.destroyed) return;
                    state.pendingToken = null;
                    upsert(data.comment);
                    els.input.value = mode && mode.type === 'edit' ? (mode.draft || '') : '';
                    setMode(null);
                    render(true);
                    flash(data.comment.id);
                })
                .catch((err) => {
                    if (state.destroyed) return;
                    // Text stays in the box so nothing is lost.
                    const retryable = !err.status || err.status >= 500 || err.status === 409 || err.status === 429;
                    if (err.status && err.status < 500 && err.status !== 409 && err.status !== 429) state.pendingToken = null;
                    showError(err.message || 'Could not send your comment.', retryable);
                })
                .finally(() => {
                    if (state.destroyed) return;
                    setSending(false);
                    els.input.focus();
                });
        }

        /* ── actions on a message ───────────────────────────── */
        function flash(id) {
            const entry = state.nodes.get(id);
            if (!entry) return;
            entry.el.classList.add('flash');
            setTimeout(() => entry.el.classList.remove('flash'), 1400);
        }

        function goTo(id) {
            const entry = state.nodes.get(id);
            if (!entry) {
                window.toastr && toastr.info('That comment is no longer available.');
                return;
            }
            entry.el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            flash(id);
        }

        function remove(comment, button) {
            if (!confirm('Delete this comment? This cannot be undone.')) return;
            button.disabled = true;
            request('DELETE', cfg.itemUrl.replace('__ID__', comment.id))
                .then(() => {
                    if (state.destroyed) return;
                    if (state.mode && state.mode.comment.id === comment.id) cancelMode();
                    const entry = state.nodes.get(comment.id);
                    const finish = () => {
                        state.comments = state.comments
                            .filter(c => c.id !== comment.id)
                            .map(c => (c.reply_to && c.reply_to.id === comment.id) ? Object.assign({}, c, { reply_to: null }) : c);
                        render(false);
                    };
                    if (entry) {
                        entry.el.style.transition = 'opacity .2s';
                        entry.el.style.opacity = '0';
                        setTimeout(finish, 200);
                    } else finish();
                })
                .catch((err) => {
                    button.disabled = false;
                    window.toastr ? toastr.error(err.message || 'Could not delete the comment.') : alert(err.message);
                });
        }

        function onListClick(e) {
            const btn = e.target.closest('[data-act]');
            if (!btn || !root.contains(btn)) return;
            const act = btn.dataset.act;
            if (act === 'reload') { load(); return; }
            if (act === 'goto') { goTo(Number(btn.dataset.target)); return; }

            const msg = btn.closest('.tc-msg');
            const comment = msg && state.comments.find(c => c.id === Number(msg.dataset.id));
            if (!comment) return;

            if (act === 'reply') {
                if (state.mode && state.mode.type === 'edit') cancelMode();
                setMode({ type: 'reply', comment });
                els.input.focus();
            } else if (act === 'edit') {
                const draft = state.mode && state.mode.type === 'edit' ? state.mode.draft : els.input.value;
                setMode({ type: 'edit', comment, draft });
                els.input.value = comment.text;
                updateComposer();
                els.input.focus();
                els.input.setSelectionRange(els.input.value.length, els.input.value.length);
            } else if (act === 'delete') {
                remove(comment, btn);
            } else if (act === 'copy') {
                navigator.clipboard && navigator.clipboard.writeText(comment.text)
                    .then(() => window.toastr && toastr.success('Copied'))
                    .catch(() => {});
            }
        }

        /* ── wiring (all handlers live on this card, so they vanish with it) ── */
        const coarse = window.matchMedia && window.matchMedia('(pointer: coarse)').matches;
        const handlers = {
            keydown(e) {
                if (e.key === 'Enter' && !e.shiftKey && !e.isComposing && !coarse) {
                    e.preventDefault();
                    submit();
                } else if (e.key === 'Escape' && state.mode) {
                    e.preventDefault();
                    cancelMode();
                }
            },
            input() { clearError(); updateComposer(); },
            scroll() { if (nearBottom()) els.jump.classList.remove('show'); },
        };

        els.input.addEventListener('keydown', handlers.keydown);
        els.input.addEventListener('input', handlers.input);
        els.send.addEventListener('click', submit);
        els.errorRetry.addEventListener('click', submit);
        els.contextCancel.addEventListener('click', cancelMode);
        els.jump.addEventListener('click', () => scrollToBottom(true));
        els.thread.addEventListener('scroll', handlers.scroll, { passive: true });
        root.addEventListener('click', onListClick);

        // Light polling so other people's comments appear without a page refresh.
        state.timer = setInterval(() => {
            if (!root.isConnected) { instance.destroy(); return; }
            if (document.hidden || state.sending) return;
            load();
        }, cfg.pollMs);

        const onVisible = () => { if (!document.hidden && root.isConnected) load(); };
        document.addEventListener('visibilitychange', onVisible);

        const instance = {
            refresh: () => load(),
            destroy() {
                state.destroyed = true;
                clearInterval(state.timer);
                document.removeEventListener('visibilitychange', onVisible);
                if (window.__taskComments === instance) window.__taskComments = null;
            },
        };
        window.__taskComments = instance;

        // Kept for the task board, which calls loadComments() after opening a task.
        window.loadComments = () => instance.refresh();

        setSending(false);
        load({ bottom: true });
    })();
</script>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/vendor/task-comments.blade.php ENDPATH**/ ?>