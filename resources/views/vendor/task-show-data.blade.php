<style>
    /* ════════════════════════════════════════════
   TASK DETAIL — Design System
   Aesthetic: Refined utilitarian (Linear-inspired)
   Light mode · crisp hierarchy · zero noise
════════════════════════════════════════════ */
    :root {
        --ts-white: #ffffff;
        --ts-bg: #f5f7fa;
        --ts-surface: #ffffff;
        --ts-border: #e2e8f0;
        --ts-border-2: #f0f4f8;
        --ts-text: #0f172a;
        --ts-sub: #475569;
        --ts-muted: #94a3b8;
        --ts-accent: #2563eb;
        --ts-accent-s: #eff6ff;
        --ts-accent-d: #1d4ed8;
        --ts-success: #16a34a;
        --ts-success-s: #f0fdf4;
        --ts-warning: #d97706;
        --ts-warning-s: #fffbeb;
        --ts-danger: #dc2626;
        --ts-danger-s: #fef2f2;
        --ts-info: #0284c7;
        --ts-info-s: #f0f9ff;
        --ts-radius: 10px;
        --ts-radius-sm: 7px;
        --ts-shadow: 0 1px 3px rgba(15, 23, 42, .06), 0 4px 16px rgba(15, 23, 42, .05);
        --ts-shadow-lg: 0 8px 30px rgba(15, 23, 42, .1);
        --ts-ease: .17s cubic-bezier(.4, 0, .2, 1);
    }

    /* ── Base ─────────────────────────────────── */
    .ts-wrap {
        background: var(--ts-bg);
        padding: 1rem;
    }

    /* ── Card shell ───────────────────────────── */
    .ts-card {
        background: var(--ts-surface);
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius);
        box-shadow: var(--ts-shadow);
        overflow: hidden;
        margin-bottom: .85rem;
    }

    .ts-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .65rem 1rem;
        border-bottom: 1px solid var(--ts-border);
        background: #fafbfd;
    }

    .ts-card-title {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        color: var(--ts-muted);
        margin: 0;
    }

    .ts-card-body {
        padding: 1rem;
    }

    .ts-card-body-flush {
        padding: 0;
    }

    /* ── Row / grid helpers ───────────────────── */
    .ts-row {
        display: flex;
        flex-wrap: wrap;
        gap: .85rem;
    }

    .ts-col {
        flex: 1;
        min-width: 0;
    }

    .ts-col-4 {
        flex: 0 0 calc(33.33% - .57rem);
        min-width: 240px;
    }

    .ts-col-6 {
        flex: 0 0 calc(50% - .425rem);
        min-width: 260px;
    }

    .ts-col-8 {
        flex: 0 0 calc(66.66% - .28rem);
        min-width: 300px;
    }

    @media(max-width:768px) {

        .ts-col-4,
        .ts-col-6,
        .ts-col-8 {
            flex: 1 1 100%;
        }
    }

    /* ── Form controls ────────────────────────── */
    .ts-label {
        display: block;
        font-size: .68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--ts-muted);
        margin-bottom: .3rem;
    }

    .ts-input,
    .ts-select,
    .ts-textarea {
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        padding: .4rem .7rem;
        font-size: .8rem;
        color: var(--ts-text);
        background: var(--ts-bg);
        width: 100%;
        outline: none;
        transition: border-color var(--ts-ease), box-shadow var(--ts-ease);
    }

    .ts-input:focus,
    .ts-select:focus,
    .ts-textarea:focus {
        border-color: var(--ts-accent);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        background: var(--ts-white);
    }

    .ts-input[readonly],
    .ts-input[disabled] {
        color: var(--ts-sub);
        cursor: default;
    }

    .ts-textarea {
        resize: vertical;
        min-height: 80px;
    }

    .ts-select {
        cursor: pointer;
    }

    /* ── Buttons ──────────────────────────────── */
    .ts-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .4rem .85rem;
        border-radius: var(--ts-radius-sm);
        font-size: .78rem;
        font-weight: 600;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all var(--ts-ease);
        white-space: nowrap;
        text-decoration: none;
        line-height: 1.4;
    }

    .ts-btn-sm {
        padding: .28rem .6rem;
        font-size: .72rem;
    }

    .ts-btn-primary {
        background: var(--ts-accent);
        color: #fff;
        border-color: var(--ts-accent);
    }

    .ts-btn-primary:hover {
        background: var(--ts-accent-d);
        color: #fff;
    }

    .ts-btn-success {
        background: var(--ts-success);
        color: #fff;
        border-color: var(--ts-success);
    }

    .ts-btn-success:hover {
        background: #15803d;
        color: #fff;
    }

    .ts-btn-outline {
        background: var(--ts-white);
        color: var(--ts-text);
        border-color: var(--ts-border);
    }

    .ts-btn-outline:hover {
        background: var(--ts-bg);
        border-color: #c5cde0;
    }

    .ts-btn-danger {
        background: var(--ts-danger-s);
        color: var(--ts-danger);
        border-color: #fecaca;
    }

    .ts-btn-danger:hover {
        background: #fee2e2;
    }

    .ts-btn-ghost {
        background: transparent;
        border-color: transparent;
        color: var(--ts-muted);
    }

    .ts-btn-ghost:hover {
        background: var(--ts-bg);
        color: var(--ts-text);
    }

    .ts-btn-done {
        background: var(--ts-success-s);
        color: var(--ts-success);
        border-color: #bbf7d0;
        cursor: default;
    }

    /* ── Field group ──────────────────────────── */
    .ts-field {
        display: flex;
        flex-direction: column;
        gap: .28rem;
    }

    /* ── Meta strip (status bar) ──────────────── */
    .ts-meta-strip {
        background: var(--ts-surface);
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius);
        box-shadow: var(--ts-shadow);
        padding: .85rem 1rem;
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: .7rem;
        margin-bottom: .85rem;
    }

    .ts-meta-strip.is-complete {
        border-left: 3px solid var(--ts-success);
    }

    /* ── Date cards ───────────────────────────── */
    .ts-date-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: .7rem;
        margin-bottom: .85rem;
    }

    .ts-date-card {
        border-radius: var(--ts-radius);
        padding: .75rem 1rem;
        border-left: 3px solid transparent;
    }

    .ts-date-card.dc-amber {
        background: var(--ts-warning-s);
        border-color: #f59e0b;
    }

    .ts-date-card.dc-green {
        background: var(--ts-success-s);
        border-color: #22c55e;
    }

    .ts-date-card.dc-blue {
        background: var(--ts-info-s);
        border-color: #38bdf8;
    }

    .ts-date-card.dc-red {
        background: var(--ts-danger-s);
        border-color: #f87171;
    }

    .ts-date-card .ts-label {
        margin-bottom: .25rem;
    }

    .ts-date-val {
        font-size: .82rem;
        font-weight: 600;
        color: var(--ts-text);
    }

    .ts-date-val input {
        background: transparent;
        border: none;
        outline: none;
        font-size: .82rem;
        font-weight: 600;
        color: var(--ts-text);
        width: 100%;
    }

    /* ── Badges ───────────────────────────────── */
    .ts-badge {
        display: inline-block;
        font-size: .67rem;
        font-weight: 700;
        padding: .18rem .5rem;
        border-radius: 20px;
        line-height: 1.4;
    }

    .ts-badge-blue {
        background: #dbeafe;
        color: #1e40af;
    }

    .ts-badge-green {
        background: #dcfce7;
        color: #15803d;
    }

    .ts-badge-amber {
        background: #fef9c3;
        color: #92400e;
    }

    .ts-badge-red {
        background: #fee2e2;
        color: #991b1b;
    }

    .ts-badge-gray {
        background: #f1f5f9;
        color: #475569;
    }

    .ts-badge-purple {
        background: #ede9fe;
        color: #5b21b6;
    }

    /* ── Table ────────────────────────────────── */
    .ts-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .78rem;
    }

    .ts-table thead th {
        background: #f8f9fd;
        font-size: .65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--ts-muted);
        padding: .55rem .9rem;
        border-bottom: 1px solid var(--ts-border);
        white-space: nowrap;
    }

    .ts-table tbody td {
        padding: .6rem .9rem;
        border-bottom: 1px solid var(--ts-border-2);
        color: var(--ts-text);
        vertical-align: middle;
    }

    .ts-table tbody tr:last-child td {
        border-bottom: none;
    }

    .ts-table tbody tr:hover {
        background: #fafbfd;
    }

    .ts-table a {
        color: var(--ts-accent);
        text-decoration: none;
    }

    .ts-table a:hover {
        text-decoration: underline;
    }

    .ts-table-scroll {
        max-height: 420px;
        overflow-y: auto;
    }

    .ts-table-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .ts-table-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 4px;
    }

    /* ── Completion form ──────────────────────── */
    .ts-complete-form-wrap {
        display: none;
        margin-bottom: .85rem;
    }

    .ts-complete-form-wrap .ts-card {
        border-left: 3px solid var(--ts-warning);
    }

    .ts-complete-form-wrap .ts-card-body {
        background: #fffcf5;
    }

    .ts-check-row {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .5rem .7rem;
        background: var(--ts-bg);
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        margin-bottom: .45rem;
    }

    .ts-check-row label {
        font-size: .8rem;
        font-weight: 600;
        color: var(--ts-text);
        margin: 0;
        cursor: pointer;
    }

    .ts-check-row input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: var(--ts-accent);
        cursor: pointer;
        flex-shrink: 0;
    }

    .ts-form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: .6rem;
        margin-bottom: .65rem;
    }

    .ts-form-group {
        margin-bottom: .65rem;
    }

    .ts-form-group label {
        font-size: .75rem;
        font-weight: 700;
        color: var(--ts-text);
        display: block;
        margin-bottom: .28rem;
    }

    .ts-form-group .ts-input,
    .ts-form-group .ts-select,
    .ts-form-group .ts-textarea {
        background: var(--ts-white);
    }

    /* ── Comment card ─────────────────────────── */
    .ts-comment {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .75rem;
        background: var(--ts-bg);
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        margin-bottom: .45rem;
    }

    .ts-comment-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .ts-comment-name {
        font-size: .78rem;
        font-weight: 700;
        color: var(--ts-text);
    }

    .ts-comment-time {
        font-size: .67rem;
        color: var(--ts-muted);
    }

    .ts-comment-body {
        font-size: .8rem;
        color: var(--ts-sub);
        margin-top: .25rem;
        white-space: pre-wrap;
    }

    /* ── Timeline ─────────────────────────────── */
    .ts-tl-item {
        display: flex;
        align-items: flex-start;
        gap: .55rem;
        padding: .38rem 0;
        border-bottom: 1px solid var(--ts-border-2);
    }

    .ts-tl-item:last-child {
        border-bottom: none;
    }

    .ts-tl-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--ts-accent);
        flex-shrink: 0;
        margin-top: .35rem;
    }

    .ts-tl-text {
        font-size: .78rem;
        font-weight: 600;
        color: var(--ts-text);
        flex: 1;
    }

    .ts-tl-time {
        font-size: .67rem;
        color: var(--ts-muted);
    }

    /* ── Collaborator row ─────────────────────── */
    .ts-collab {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .5rem .65rem;
        background: var(--ts-bg);
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        margin-bottom: .35rem;
    }

    .ts-collab img {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .ts-collab-name {
        font-size: .78rem;
        font-weight: 700;
        color: var(--ts-text);
    }

    .ts-collab-email {
        font-size: .67rem;
        color: var(--ts-muted);
    }

    /* ── Attachment row ───────────────────────── */
    .ts-attachment {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .55rem .75rem;
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        margin-bottom: .35rem;
        background: var(--ts-white);
        transition: background var(--ts-ease);
    }

    .ts-attachment:hover {
        background: var(--ts-bg);
    }

    .ts-attachment-thumb {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .ts-attachment-name {
        font-size: .78rem;
        font-weight: 600;
        color: var(--ts-text);
    }

    .ts-attachment-meta {
        font-size: .67rem;
        color: var(--ts-muted);
    }

    /* ── Credential row ───────────────────────── */
    .ts-cred {
        padding: .65rem .85rem;
        border-bottom: 1px solid var(--ts-border-2);
    }

    .ts-cred:last-child {
        border-bottom: none;
    }

    .ts-cred-key {
        font-size: .66rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: var(--ts-muted);
    }

    .ts-cred-val {
        font-size: .8rem;
        font-weight: 600;
        color: var(--ts-text);
    }

    /* ── Danger zone ──────────────────────────── */
    .ts-danger-zone {
        background: var(--ts-danger-s);
        border: 1px solid #fecaca;
        border-radius: var(--ts-radius);
        padding: .9rem 1rem;
        margin-bottom: .85rem;
    }

    .ts-danger-title {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: var(--ts-danger);
        margin-bottom: .55rem;
    }

    /* ── Floating save ────────────────────────── */
    .btn-save-pulse {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 999;
        background: var(--ts-success);
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: .55rem 1.35rem;
        font-size: .82rem;
        font-weight: 700;
        box-shadow: 0 4px 20px rgba(22, 163, 74, .35);
        cursor: pointer;
        transition: all var(--ts-ease);
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .btn-save-pulse:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(22, 163, 74, .4);
    }

    /* ── Completion animation ─────────────────── */
    .completion-anim-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 0;
    }

    .completion-anim-wrapper svg {
        width: 72px;
        height: 72px;
    }

    .st0 {
        fill: #51BBA0;
        fill-opacity: .4;
    }

    .st1 {
        fill: #51BBA0;
        fill-opacity: .1;
    }

    .st2 {
        fill: #51BBA0;
    }

    /* ── Repeating badge ──────────────────────── */
    .ts-repeat-badge {
        font-size: .67rem;
        font-weight: 700;
        padding: .18rem .5rem;
        border-radius: 20px;
        background: var(--ts-danger-s);
        color: var(--ts-danger);
        border: 1px solid #fecaca;
    }

    /* ── Cred form grid ───────────────────────── */
    .ts-cred-form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .45rem;
        margin-bottom: .45rem;
        align-items: end;
    }

    @media(max-width:767px) {
        .ts-cred-form-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* ── Link group ───────────────────────────── */
    .link-group {
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        padding: .85rem;
        margin-bottom: .6rem;
        background: var(--ts-bg);
    }

    .url-group {
        border: 1px solid var(--ts-border);
        border-radius: var(--ts-radius-sm);
        padding: .85rem;
        margin-bottom: .6rem;
        background: var(--ts-bg);
    }
</style>

{{-- ══════════════════════════════════════════
     COMPLETION ANIMATION (hidden by default)
══════════════════════════════════════════ --}}
<div class="completion-anim-wrapper" style="display:none;">
    <svg id="completion" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 101">
        <title>C1DBEBC0-CF7A-42D3-B615-1AB5DE73C3E9</title>
        <g id="configurator">
            <g id="configurator_completion">
                <g id="stars">
                    <circle class="st0" cx="14" cy="18" r="1" />
                    <circle class="st0" cx="27" cy="20" r="1" />
                    <circle class="st0" cx="76" cy="20" r="1" />
                    <circle class="st0" cx="61.5" cy="12.5" r="1.5" />
                    <circle class="st0" cx="94" cy="53" r="1" />
                    <circle class="st0" cx="88" cy="14" r="1" />
                    <circle class="st0" cx="59" cy="1" r="1" />
                    <circle class="st0" cx="43" cy="9" r="2" />
                    <path class="st0" d="M28.5 3.8L26 6l2.2-2.5L26 1l2.5 2.2L31 1l-2.2 2.5L31 6z" />
                    <path class="st0"
                        d="M3.5 50.9l-2.1 2.4 1.7-2.7-2.9-1.2 3.1.8.2-3.2.2 3.2 3.1-.8-2.9 1.2 1.7 2.7z" />
                    <path class="st0" d="M93.5 27.8L91 30l2.2-2.5L91 25l2.5 2.2L96 25l-2.2 2.5L96 30z" />
                    <circle class="st0" cx="91" cy="40" r="2" />
                    <circle class="st0" cx="7" cy="36" r="2" />
                    <circle class="st0" cx="7.5" cy="5.5" r=".5" />
                </g>
            </g>
        </g>
        <g id="cirkel">
            <g id="Mask">
                <path class="st1" d="M49 21c22.1 0 40 17.9 40 40s-17.9 40-40 40S9 83.1 9 61s17.9-40 40-40z" />
            </g>
        </g>
        <path id="check" class="st2"
            d="M31.3 64.3c-1.2-1.5-3.4-1.9-4.9-.7-1.5 1.2-1.9 3.4-.7 4.9l7.8 10.4c1.3 1.7 3.8 1.9 5.3.4L71.1 47c1.4-1.4 1.4-3.6 0-5s-3.6-1.4-5 0L36.7 71.5l-5.4-7.2z" />
    </svg>
</div>

{{-- ══════════════════════════════════════════
     COMPLETION FORM (shown on Complete click)
══════════════════════════════════════════ --}}
<div class="completion-form-wrapper mb-10 ts-complete-form-wrap">
    <div class="ts-card">
        <div class="ts-card-header">
            <span class="ts-card-title" style="color:var(--ts-warning);">
                <i class="bi bi-clipboard-check me-1"></i> Complete This Task
            </span>
        </div>
        <div class="ts-card-body">
            <input type="hidden" name="task_id" value="{{ $task->id }}">

            @if ($task->title === 'Handover Meet with The Sales Manager')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="handover_meeting">
                    <div class="ts-check-row">
                        <input id="handover_meeting" class="form-check-input" type="checkbox" required
                            name="fields[0][value]">
                        <label for="handover_meeting">Have you done Handover Meet with The Sales Manager?</label>
                    </div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title === 'Keywords Approval')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <p style="font-size:.8rem;font-weight:700;margin-bottom:.65rem;">Keyword Approval Process</p>
                    <input type="hidden" name="fields[0][name]" value="seo_admin_approval">
                    <div class="ts-check-row"><input type="checkbox" class="form-check-input" id="seo_admin_approval"
                            required name="fields[0][value]"><label for="seo_admin_approval">SEO Admin Approval
                            (Completed)</label></div>
                    <input type="hidden" name="fields[1][name]" value="client_approval">
                    <div class="ts-check-row"><input type="checkbox" class="form-check-input" id="client_approval"
                            required name="fields[1][value]"><label for="client_approval">Client Approval
                            (Completed)</label></div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Mark as Complete</button>
                </form>
            @elseif ($task->title === 'Intro Call')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="intro_call">
                    <div class="ts-check-row"><input id="intro_call" class="form-check-input" type="checkbox"
                            required name="fields[0][value]"><label for="intro_call">Have you done Intro Call?</label>
                    </div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title === 'Onboarding Email')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="onboarding_email">
                    <div class="ts-check-row"><input id="onboarding_email" class="form-check-input" type="checkbox"
                            required name="fields[0][value]"><label for="onboarding_email">Have you sent Onboarding
                            Email?</label></div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title === 'Send SEO Benchmark Report to client')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="benchmark_report_to_client">
                    <div class="ts-check-row"><input id="benchmark_report_to_client" class="form-check-input"
                            type="checkbox" required name="fields[0][value]"><label
                            for="benchmark_report_to_client">Have you sent benchmark report to client?</label></div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'First Client Meeting')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="location" hidden>
                    <div class="ts-form-group"><label>Location</label>
                        <select class="ts-select" name="fields[0][value]">
                            <option value="Zoom">Zoom</option>
                            <option value="10X Office">10X Office</option>
                            <option value="Client Office">Client Office</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Upload the Content Intake Form')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="content_intake_form" hidden>
                    <div class="ts-form-group"><label>Upload the Content Intake Form</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Upload the Local Listing Form')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="local_listing_form" hidden>
                    <div class="ts-form-group"><label>Upload the Local Listing Form</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'On-site Audit (from SE ranking)')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="onsite_from_se_ranking" hidden>
                    <div class="ts-form-group"><label>On-site Audit (from SE ranking)</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'PUBLISH CONTENT')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="publish_content">
                    <div class="ts-check-row"><input id="publish_content" class="form-check-input" type="checkbox"
                            required name="fields[0][value]"><label for="publish_content">Have you published
                            content?</label></div>
                    <button class="ts-btn ts-btn-success mt-2" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Backlink Audit & DISAVOW')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="backlink_audit" hidden>
                    <div class="ts-form-group"><label>Document of Backlink Audit</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <input type="text" name="fields[1][name]" value="disavow" hidden>
                    <div class="ts-form-group"><label>Document of DISAVOW <span
                                style="font-weight:400;color:var(--ts-muted);">(optional)</span></label><input
                            type="file" name="fields[1][value]" class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($relatedData && in_array($relatedData->sub_type, ['SEO CONTENT', 'BLOG CONTENT']))
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="number_of_words" hidden>
                    <div class="ts-form-group"><label>Number Of Words</label><input class="ts-input" type="number"
                            name="fields[0][value]"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif (
                $relatedData &&
                    in_array($relatedData->sub_type, [
                        'RE-ONSITE',
                        'CHANGES IN CONTENT',
                        'CREATE NEW PAGE',
                        'REPLACE/UPLOAD CONTENT',
                        'ONSITE AUDIT',
                        'LP DESIGN',
                        'BANNERS',
                        'VIDEO',
                        'REVISIONS',
                        'SITE SPEED AND RENDERING',
                    ]))
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="attachment" hidden>
                    <div class="ts-form-group"><label>Attachment</label><input type="file" name="fields[][value]"
                            required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'SEO Content Request')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="seo_content_request_document" hidden>
                    <div class="ts-form-group"><label>Upload SEO Content Request Document</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <input type="hidden" name="fields[1][name]" value="content_completion_word_count">
                    <div class="ts-form-group"><label>Content Completion Word Count</label><input
                            id="content_completion_word_count" class="ts-input" type="number" required
                            name="fields[1][value]"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Content Audit')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="content_audit" hidden>
                    <div class="ts-form-group"><label>Content Audit Document</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'First On-site')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="first_onsite_report" hidden>
                    <div class="ts-form-group"><label>First On-site Report</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Request Local Listing')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="request_local_listing" hidden>
                    <div class="ts-form-group"><label>Upload Request Local Listing Doc</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Create the Project on SE Ranking')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="benchmark_report" hidden>
                    <div class="ts-form-group"><label>Attach Benchmark Report</label><input type="file"
                            name="fields[0][value]" required class="ts-input"></div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Performance Report')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="performance" hidden>
                    <div class="ts-form-group"><label>Performance</label>
                        <select name="fields[0][value]" class="ts-select">
                            <option value="Excellent">Excellent</option>
                            <option value="Needs Improvement">Needs Improvement</option>
                            <option value="Unsatisfactory">Unsatisfactory</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Campaign Meeting (Deep-dive) with Tech')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[1][name]" value="minutes_of_meeting" hidden>
                    <div class="ts-form-group"><label>Minutes of Meeting</label>
                        <textarea name="fields[1][value]" class="ts-textarea" rows="6"></textarea>
                    </div>
                    <input type="hidden" name="fields[0][name]" value="expectations" hidden>
                    <div class="ts-form-group"><label>Status</label>
                        <select name="fields[0][value]" class="ts-select">
                            <option value="Exceeds Expectations">Exceeds Expectations</option>
                            <option value="Meets Expectations">Meets Expectations</option>
                            <option value="Below Expectations">Below Expectations</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Campaign Meeting (Deep-dive) with Client')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[1][name]" value="minutes_of_meeting" hidden>
                    <div class="ts-form-group"><label>Minutes of Meeting</label>
                        <textarea name="fields[1][value]" class="ts-textarea" rows="6"></textarea>
                    </div>
                    <input type="hidden" name="fields[0][name]" value="status" hidden>
                    <div class="ts-form-group"><label>Status</label>
                        <select name="fields[0][value]" class="ts-select">
                            <option value="Very Satisfied">Very Satisfied</option>
                            <option value="Neutral">Neutral</option>
                            <option value="Dissatisfied">Dissatisfied</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Renewal Reminder')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="renewal_reminder_status">
                    <div class="ts-check-row"><input id="renewal_reminder_status" class="form-check-input"
                            type="checkbox" required name="fields[0][value]"><label
                            for="renewal_reminder_status">Have you sent Renewal Reminder?</label></div>
                    <input type="hidden" name="fields[1][name]" value="feeling" hidden>
                    <div class="ts-form-group mt-2"><label>Feeling</label>
                        <select name="fields[1][value]" class="ts-select">
                            <option value="Confident">Confident</option>
                            <option value="Unsure">Unsure</option>
                            <option value="No Renewal">No Renewal</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Renewal Contract')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="renewal_contract_status">
                    <div class="ts-check-row"><input id="renewal_contract_status" class="form-check-input"
                            type="checkbox" required name="fields[0][value]"><label
                            for="renewal_contract_status">Have you sent Follow-Up?</label></div>
                    <input type="hidden" name="fields[1][name]" value="feeling" hidden>
                    <div class="ts-form-group mt-2"><label>Feeling</label>
                        <select name="fields[1][value]" class="ts-select">
                            <option value="Confirmed">Confirmed</option>
                            <option value="Awaiting Response">Awaiting Response</option>
                            <option value="Unlikely">Unlikely</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Renewal Status')
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="hidden" name="fields[0][name]" value="status" hidden>
                    <div class="ts-form-group"><label>Renewal Status</label>
                        <select name="fields[0][value]" class="ts-select">
                            <option value="Pending">Pending</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif (
                $task->title == 'Request Backlinks' ||
                    ($relatedData && in_array($relatedData->sub_type, ['MANUAL LINKS', 'IH LINKS', 'OH LINKS'])))
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="notes" hidden>
                    <div class="ts-form-group"><label>Notes</label>
                        <textarea class="ts-textarea" name="fields[0][value]" rows="8" required placeholder="Enter notes…"></textarea>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @elseif ($task->title == 'Add the Client Credentials')
                <form id="clientCredentialsForm">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <div id="credentialsContainer">
                        <div class="ts-cred-form-row credentialRow">
                            <input required class="ts-input" type="text" name="credentials[0][platform_name]"
                                placeholder="Platform Name">
                            <input class="ts-input" type="text" name="credentials[0][url]"
                                placeholder="Website URL">
                            <input required class="ts-input" type="text" name="credentials[0][username]"
                                placeholder="Username/Email">
                            <div style="display:flex;gap:.4rem;align-items:center;">
                                <input class="ts-input" type="password" name="credentials[0][password]"
                                    placeholder="Password">
                                <button type="button"
                                    class="removeCredential ts-btn ts-btn-danger ts-btn-sm">Remove</button>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;gap:.5rem;margin-top:.75rem;">
                        <button class="ts-btn ts-btn-outline ts-btn-sm" type="button" id="addCredential"><i
                                class="bi bi-plus me-1"></i>Add More</button>
                        <button class="ts-btn ts-btn-success" type="submit"><i
                                class="bi bi-check2-circle me-1"></i>Submit</button>
                    </div>
                </form>
            @elseif($task->title == 'Add KW Mapping')
                <form id="keywordForm3" class="mt-2">
                    @csrf
                    <input type="hidden" name="type" value="closing">
                    <div id="urlGroups">
                        <div class="url-group">
                            <div class="ts-form-group"><label>URL</label><input type="url"
                                    class="ts-input url-input" placeholder="https://example.com" required></div>
                            <div class="ts-form-group"><label>Keywords <span
                                        style="font-size:.68rem;color:var(--ts-muted);font-weight:400;">(one per
                                        line)</span></label>
                                <textarea class="ts-textarea keyword-textarea" rows="4" placeholder="Enter keywords…" required></textarea>
                            </div>
                            <div style="text-align:right;"><button type="button"
                                    class="ts-btn ts-btn-danger ts-btn-sm remove-url-group"><i
                                        class="bi bi-trash me-1"></i>Remove</button></div>
                        </div>
                    </div>
                    <div
                        style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:.5rem;margin-top:.75rem;">
                        <button type="button" class="ts-btn ts-btn-outline" id="addUrlGroup"><i
                                class="bi bi-plus-circle me-1"></i>Add URL Group</button>
                        <button type="submit" class="ts-btn ts-btn-success"><i
                                class="bi bi-check2-circle me-1"></i>Submit Keywords</button>
                    </div>
                </form>
            @elseif ($task->title === 'Connect the GA, GSC, GTM, and GMB')
                <form class="ga-gmb-form" enctype="multipart/form-data" style="display:none">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    @foreach (['GA', 'GSC', 'GTM', 'GMB'] as $gi => $tool)
                        <input type="hidden" name="fields[{{ $gi }}][name]" value="{{ $tool }}">
                        <input type="checkbox" checked id="{{ $tool }}" class="form-check-input" required
                            name="fields[{{ $gi }}][value]">
                    @endforeach
                </form>
                <form id="clientCredentialsForm2">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <div id="credentialsContainer">
                        @foreach (['GA', 'GSC', 'GTM', 'GMB'] as $gi => $tool)
                            <div class="ts-cred-form-row credentialRow" style="margin-bottom:.45rem;">
                                <input class="ts-input" type="text"
                                    name="credentials[{{ $gi }}][platform_name]"
                                    value="{{ $tool }}" readonly>
                                <input class="ts-input" type="email"
                                    name="credentials[{{ $gi }}][username]" placeholder="Email *" required>
                                <input class="ts-input" type="password"
                                    name="credentials[{{ $gi }}][password]" placeholder="Password">
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:.75rem;">
                        <button class="ts-btn ts-btn-success" type="submit"><i
                                class="bi bi-check2-circle me-1"></i>Submit</button>
                    </div>
                </form>
            @elseif($task->title == 'OFFSITE - PARTNER LINKS TASK')
                <form id="partnerLinkForm">
                    @csrf
                    <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <div id="link-sections">
                        <div class="link-group">
                            <div class="ts-form-row">
                                <div class="ts-form-group"><label>Keyword</label><input type="text"
                                        name="links[0][keyword]" class="ts-input" required></div>
                                <div class="ts-form-group"><label>URL</label><input type="url"
                                        name="links[0][url]" class="ts-input" required></div>
                                <div class="ts-form-group"><label>Backlink</label><input type="url"
                                        name="links[0][backlink]" class="ts-input" required></div>
                                <div class="ts-form-group"><label>Amount</label><input type="number" step="0.01"
                                        name="links[0][amount]" class="ts-input"></div>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex;gap:.5rem;margin-top:.6rem;">
                        <button type="button" id="addMore" class="ts-btn ts-btn-outline"><i
                                class="bi bi-plus me-1"></i>Add More</button>
                        <button type="submit" class="ts-btn ts-btn-primary"><i class="bi bi-send me-1"></i>Submit
                            All</button>
                    </div>
                </form>
            @else
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="text" name="fields[0][name]" value="notes" hidden>
                    <div class="ts-form-group"><label>Notes</label>
                        <textarea class="ts-textarea" name="fields[0][value]" rows="8" required placeholder="Enter notes…"></textarea>
                    </div>
                    <button class="ts-btn ts-btn-success" type="submit"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</button>
                </form>
            @endif
        </div>
    </div>
    <div id="responseMessage"></div>
</div>

{{-- ══════════════════════════════════════════
     PROJECT + ACTION BAR
══════════════════════════════════════════ --}}
<div class="ts-card" style="margin-bottom:.85rem;">
    <div class="ts-card-body" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:.65rem;">
        <div class="ts-field" style="flex:1;min-width:200px;">
            <span class="ts-label">Project</span>
            @if (($task->createdBy && $task->createdBy->id == auth()->user()->id) || auth()->user()->hasRole('ADMIN'))
                <div style="display:flex;align-items:center;gap:.4rem;flex-wrap:wrap;">
                    <select class="ts-select" style="flex:1;" name="project_id" onchange="updateTaskField(this)">
                        @foreach ($projects as $project)
                            <option {{ $task->project_id == $project->id ? 'selected' : '' }}
                                value="{{ $project->id }}">{{ $project->title }}</option>
                        @endforeach
                    </select>
                    <a href="{{ route('projects.show', $task->project->id) }}"
                        class="ts-btn ts-btn-outline ts-btn-sm"><i class="bi bi-eye"></i></a>

                    @if ($task->repeatingTask)
                        <span class="ts-repeat-badge" style="display:flex;align-items:center;gap:0.5rem;">
                            Repeating every {{ $task->repeatingTask->count }} {{ $task->repeatingTask->repeat_every }}

                            <form action="{{ route('tasks.stop-repeat', $task->id) }}" method="POST"
                                style="margin:0;display:inline;">
                                @csrf
                                <button type="submit" class="ts-btn ts-btn-sm"
                                    style="color:red; background:none; border:none; padding:0; text-decoration:underline; cursor:pointer;"
                                    onclick="return confirm('Are you sure you want to stop this task from repeating?');">
                                    Stop
                                </button>
                            </form>
                        </span>
                    @endif
                </div>
            @else
                <a href="{{ route('projects.show', $task->project->id) }}"
                    style="font-size:.82rem;font-weight:600;color:var(--ts-accent);">{{ $task->project->title }} —
                    {{ $task->project->companies->name ?? 'N/A' }}</a>
            @endif
        </div>
        @if ($task->contentTask && $task->contentTask->number_of_pages)
            <div class="ts-field"><span class="ts-label">Pages</span><input type="text" class="ts-input"
                    style="width:80px;" readonly name="number_of_pages"
                    value="{{ $task->contentTask->number_of_pages }}"></div>
        @endif
        @if ($task->type == 'DESIGN' && $relatedData)
            <div class="ts-field"><span class="ts-label">Designs</span><input type="text" class="ts-input"
                    style="width:80px;" readonly name="input_number" value="{{ $relatedData->input_number }}"></div>
        @endif
        <button onclick="taskLinkCopy('{{ route('tasks.show', $task->id) }}')" class="ts-btn ts-btn-outline"><i
                class="bi bi-share-fill"></i> Share</button>
        <a href="{{ route('tasks.show', $task->id) }}" class="ts-btn ts-btn-outline"><i
                class="bi bi-box-arrow-up-right"></i> Open</a>
    </div>
</div>

{{-- ══════════════════════════════════════════
     META STRIP — priority / assignee / status
══════════════════════════════════════════ --}}
<div class="ts-meta-strip {{ $task->status == 'Completed' ? 'is-complete' : '' }}">
    @if (auth()->user()->hasRole('ADMIN') ||
            ($task->createdBy && $task->createdBy->id == auth()->user()->id) ||
            auth()->user()->hasPermission('can edit task'))
        <div class="ts-field"><span class="ts-label">Priority</span>
            <select class="ts-select" id="priority" name="priority" onchange="updateTaskField(this)"
                style="min-width:110px;">
                <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
                <option value="Urgent" {{ $task->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
        </div>
        <div class="ts-field"><span class="ts-label">Assignee</span>
            <select onchange="updateTaskField(this)" class="ts-select" name="assigned_to" id="assigned_to"
                style="min-width:140px;">
                @foreach ($users as $user)
                    @if (!$task->assignedTo)
                        <option disabled value="-" selected>System</option>
                    @endif
                    <option {{ $task->assignedTo && $user->id == $task->assignedTo->id ? 'selected' : '' }}
                        value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    @else
        <div class="ts-field"><span class="ts-label">Priority</span><input readonly type="text" class="ts-input"
                value="{{ $task->priority }}" style="min-width:100px;"></div>
        <div class="ts-field"><span class="ts-label">Assignee</span><input readonly type="text" class="ts-input"
                value="{{ $task->assignedTo->name ?? '' }}" style="min-width:130px;"></div>
    @endif

    @if ($task->department_id)
        <div class="ts-field"><span class="ts-label">Department</span><input readonly type="text"
                class="ts-input" value="{{ $task->department->name }}" style="min-width:110px;"></div>
    @endif

    <div class="ts-field"><span class="ts-label">Created By</span><input readonly type="text" class="ts-input"
            value="{{ $task->createdBy->name ?? 'System or user deleted' }}" style="min-width:120px;"></div>

    <div class="ts-field"><span class="ts-label">Status</span>
        <select class="ts-select task-status" onchange="handleTaskStatusChange(this)"
            data-task-id="{{ $task->id }}" name="status" style="min-width:120px;">
            @foreach (['Todo', 'In Progress', 'Completed', 'Review', 'Blocked'] as $s)
                <option {{ !auth()->user()->hasPermission('can change task status') ? 'disabled' : '' }}
                    {{ $s === 'Completed' ? 'disabled' : '' }} value="{{ $s }}"
                    {{ $task->status == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>

    <div class="ts-field"><span class="ts-label">Type</span>
        <input readonly type="text" class="ts-input"
            value="{{ $task->type }}{{ $relatedData ? ' → ' . $relatedData->sub_type : '' }}"
            style="min-width:130px;">
    </div>

    {{-- Action buttons pushed right --}}
    <div style="display:flex;align-items:flex-end;gap:.4rem;flex-wrap:wrap;margin-left:auto;">
        @if ($task->status === 'Completed')
            <button type="button" class="ts-btn ts-btn-done" disabled><i
                    class="bi bi-check-circle-fill me-1"></i>Completed</button>
        @else
            <form id="task-complete-form-{{ $task->id }}"
                action="{{ route('task.markAsCompleted', $task->id) }}" method="POST"
                style="display:inline-block;">
                @csrf @method('PATCH')
                @if ($is_ajax)
                    <a class="ts-btn ts-btn-success" href="{{ route('tasks.show', $task->id) }}"><i
                            class="bi bi-check2-circle me-1"></i>Complete Task</a>
                @else
                    <button type="button" class="ts-btn ts-btn-success complete-task-btn"
                        data-task-id="{{ $task->id }}"><i class="bi bi-check2-circle me-1"></i>Complete
                        Task</button>
                @endif
            </form>
        @endif

        {{-- @if ($userPermissions->contains('can create tasks') && auth()->user()->id == $task->created_by)
            @if ($task->repeatingTask && $task->repeat_task)
                <button class="ts-btn ts-btn-danger ts-btn-sm disable-repeating"
                    data-task-id="{{ $task->id }}"><i class="bi bi-pause-circle me-1"></i>Disable Repeat</button>
            @endif
            @if ($task->repeatingTask && !$task->repeat_task)
                <button class="ts-btn ts-btn-success ts-btn-sm enable-repeating"
                    data-task-id="{{ $task->id }}"><i class="bi bi-play-circle me-1"></i>Enable Repeat</button>
            @endif
        @endif --}}

        @if ($task->parent_task_id)
            <a href="{{ route('tasks.show', $task->parent_task_id) }}" class="ts-btn ts-btn-outline ts-btn-sm"><i
                    class="bi bi-arrow-up-circle me-1"></i>Parent Task</a>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════
     ADD KW/URLs BUTTON + FORM
══════════════════════════════════════════ --}}
@if ($userPermissions->contains('can add offsite links'))
    @if (in_array($task->title, [
            'Request for R1',
            'Request for R2',
            'Request for R3',
            'Schedule R1',
            'Schedule R2',
            'Schedule R3',
            'Request Backlinks',
            'OFFSITE - PARTNER LINKS Task',
        ]) ||
            ($relatedData && in_array($relatedData->sub_type, ['MANUAL LINKS', 'IH LINKS', 'OH LINKS'])))
        <div style="display:flex;justify-content:flex-end;margin-bottom:.5rem;">
            <button class="ts-btn ts-btn-outline ts-btn-sm open-add-more-links-wrapper"><i
                    class="bi bi-plus-circle me-1"></i>Add KW/URLs</button>
        </div>
    @endif
    <div class="add-more-links-wrapper" style="display:none;margin-bottom:.85rem;">
        <div class="ts-card">
            <div class="ts-card-header"><span class="ts-card-title">Add Keywords &amp; URLs</span></div>
            <div class="ts-card-body">
                <form id="keywordForm2">
                    @csrf
                    <div class="ts-form-group"><label>Keyword</label><input type="text" class="ts-input"
                            id="mainKeyword" placeholder="Enter Keyword" required></div>
                    <div class="ts-form-group"><label>Link Cost (USD)</label><input type="number" class="ts-input"
                            value="0" id="mainLinkCost" placeholder="Link Cost USD" required></div>
                    <div class="ts-form-group"><label>Destination URL</label><input type="text" value="-"
                            required class="ts-input" id="destinationUrl" placeholder="Destination URL"></div>
                    <input type="hidden" name="type" value="closing">
                    <div class="ts-form-group"><label>URLs <span
                                style="font-size:.68rem;color:var(--ts-muted);font-weight:400;">(one per
                                line)</span></label>
                        <textarea class="ts-textarea" id="urlList" rows="5" required placeholder="Enter URLs…"></textarea>
                    </div>
                    <div style="text-align:right;"><button class="ts-btn ts-btn-primary" type="submit"><i
                                class="bi bi-floppy me-1"></i>Save Keywords</button></div>
                </form>
            </div>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════
     DATE CARDS
══════════════════════════════════════════ --}}
<div class="ts-date-grid">
    <div class="ts-date-card dc-amber">
        <span class="ts-label"><i class="bi bi-hash me-1"></i>Title</span>
        <div class="ts-date-val">
            @if ($relatedData && $relatedData->sub_type == 'ADHOC')
                <input type="text" onchange="updateTaskField(this)" id="title" name="title"
                    value="#{{ $task->id }} - {{ $task->title }}" required>
            @else
                <input type="text" readonly id="title" name="title"
                    value="#{{ $task->id }} - {{ $task->title }}">
            @endif
        </div>
    </div>
    <div class="ts-date-card dc-green">
        <span class="ts-label"><i class="bi bi-calendar-event me-1"></i>Start Date</span>
        <div class="ts-date-val"><input type="datetime" readonly disabled onchange="updateTaskField(this)"
                name="start_date" id="start_date" value="{{ $task->start_date }}" class="date-picker"></div>
    </div>
    @if ($task->status == 'Completed')
        <div class="ts-date-card dc-blue">
            <span class="ts-label"><i class="bi bi-check-circle me-1"></i>Completed At</span>
            <div class="ts-date-val"><input type="datetime" readonly disabled id="completed_date"
                    value="{{ $task->completed_date }}" class="date-picker"></div>
        </div>
    @endif
    <div class="ts-date-card dc-red">
        <span class="ts-label"><i class="bi bi-calendar-x me-1"></i>Due Date</span>
        <div class="ts-date-val"><input type="date" onchange="updateTaskField(this)" name="due_date"
                id="due_date" value="{{ $task->due_date }}" class="date-picker"></div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     DESCRIPTION + FILES (side by side)
══════════════════════════════════════════ --}}
<div class="ts-row" style="margin-bottom:.85rem;">
    <div class="ts-col-8 ts-card" style="min-height:480px;display:flex;flex-direction:column;">
        <div class="ts-card-header" style="position:sticky;top:0;z-index:2;"><span
                class="ts-card-title">Description</span></div>
        <div style="flex:1;overflow:hidden;">
            <textarea class="w-100 h-100 border-0 ts-textarea" style="border-radius:0;min-height:420px;padding:.85rem 1rem;"
                @if (auth()->user()->hasRole('ADMIN')) onchange="updateTaskField(this)" @endif name="description" id="description">{{ $task->description }}</textarea>
        </div>
    </div>
    <div class="ts-col-4 ts-card" style="min-height:480px;display:flex;flex-direction:column;">
        <div class="ts-card-header" style="position:sticky;top:0;z-index:2;">
            <span class="ts-card-title"><i class="bi bi-paperclip me-1"></i>Task Files</span>
            @if ($is_ajax)
                <a href="{{ route('tasks.show', $task->id) }}" class="ts-btn ts-btn-ghost ts-btn-sm">Add More</a>
            @else
                <button type="button" id="taskAttachments" class="ts-btn ts-btn-ghost ts-btn-sm"><i
                        class="bi bi-upload me-1"></i>Add More</button>
            @endif
        </div>
        <div class="ts-card-body" style="flex:1;overflow-y:auto;">
            <div id="uploadTaskAttachmentsForm" style="display:none;margin-bottom:.85rem;">
                <form action="{{ route('tasks.attachments.store', $task->id) }}" class="dropzone"
                    id="taskAttachmentDropzone" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                </form>
                <button type="button" class="ts-btn ts-btn-primary ts-btn-sm mt-2" id="uploadTaskFilesBtn"><i
                        class="bi bi-cloud-upload me-1"></i>Upload Files</button>
            </div>
            <div id="taskUploadStatus"></div>

            @if (
                $task->title == 'SEO Content Request' &&
                    ($task->project->tasks->where('title', 'Upload the Content Intake Form')->first() &&
                        $task->project->tasks->where('title', 'Upload the Content Intake Form')->first()->status == 'Completed'))
                @php $completions = $task->project->tasks->where('title','Upload the Content Intake Form')->first()->completions; @endphp
                @foreach ($completions as $completion)
                    @php $filePath = '/storage/app/public/'.$completion->field_value; @endphp
                    <div class="ts-attachment">
                        <i class="bi bi-file-earmark-text ts-attachment-thumb"
                            style="display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:var(--ts-muted);font-size:.9rem;"></i>
                        <div style="flex:1;min-width:0;">
                            <div class="ts-attachment-name">{{ $completion->field_name }}</div>
                        </div>
                        <a href="{{ asset($filePath) }}" download class="ts-btn ts-btn-outline ts-btn-sm"><i
                                class="bi bi-download"></i></a>
                    </div>
                @endforeach
                @php $completions = $task->project->tasks->where('title','Upload the Content Intake Form')->first()->attachments; @endphp
                @foreach ($completions as $completion)
                    @php $filePath = '/storage/app/public/'.$completion->file_path; @endphp
                    <div class="ts-attachment">
                        <i class="bi bi-file-earmark ts-attachment-thumb"
                            style="display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:var(--ts-muted);font-size:.9rem;"></i>
                        <div style="flex:1;min-width:0;">
                            <div class="ts-attachment-name">Content Intake Form</div>
                        </div>
                        <a href="{{ asset($filePath) }}" download class="ts-btn ts-btn-outline ts-btn-sm"><i
                                class="bi bi-download"></i></a>
                    </div>
                @endforeach
            @endif
            <div id="taskAttachmentsContainer"></div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SEO CONTENT REQUEST: keywords
══════════════════════════════════════════ --}}
@if (
    $task->project->tasks->where('title', 'Keywords Approval')->first() &&
        $task->project->tasks->where('title', 'Keywords Approval')->first()->status == 'Completed' &&
        $task->title == 'SEO Content Request')
    <div class="ts-row" style="margin-bottom:.85rem;">
        <div class="ts-col-6 ts-card">
            <div class="ts-card-header"><span class="ts-card-title">Project Keywords</span></div>
            <div class="ts-card-body-flush ts-table-scroll">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->project->tasks->where('title', 'Add KW Mapping')->first()->projectKeywords as $keyword)
                            <tr>
                                <td>{{ $keyword->keyword }}</td>
                                <td><a href="{{ $keyword->url }}" target="_blank">{{ $keyword->url }}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($task->project->tasks->where('title', 'Add KW Mapping')->first()->completions)
            <div class="ts-col-6 ts-card">
                <div class="ts-card-header"><span class="ts-card-title">KW Mapping Closing Inputs</span></div>
                <div class="ts-card-body-flush ts-table-scroll">
                    <table class="ts-table">
                        <tbody>
                            @foreach ($task->project->tasks->where('title', 'Add KW Mapping')->first()->completions as $completion)
                                <tr>
                                    @if ($completion->field_value === 'on')
                                        <td>{{ str_replace('_', ' ', ucfirst($completion->field_name)) }}</td>
                                        <td style="color:var(--ts-success);"><i class="bi bi-check-circle-fill"></i>
                                        </td>
                                    @elseif (Str::startsWith($completion->field_value, 'task_attachments/'))
                                        @php $filePath = '/public/storage/'.$completion->field_value; @endphp
                                        <td>Attachment</td>
                                        <td style="display:flex;gap:.3rem;">
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="ts-btn ts-btn-outline ts-btn-sm"><i
                                                    class="bi bi-download me-1"></i>Download</a>
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="ts-btn ts-btn-outline ts-btn-sm"><i
                                                    class="bi bi-eye me-1"></i>View</a>
                                        </td>
                                    @else
                                        <td>{{ str_replace('_', ' ', ucfirst($completion->field_name)) }}</td>
                                        <td>{{ $completion->field_value }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endif

{{-- ══════════════════════════════════════════
     CLOSING INPUTS + CREDENTIALS
══════════════════════════════════════════ --}}
@if (
    $task->completions->count() ||
        ($task->project->credentials->count() && $task->title == 'Add the Client Credentials'))
    <div class="ts-row" style="margin-bottom:.85rem;">
        @if ($task->completions->count())
            <div class="ts-col-6 ts-card">
                <div class="ts-card-header"><span class="ts-card-title"><i
                            class="bi bi-clipboard2-check me-1"></i>Closing Inputs</span></div>
                <div class="ts-card-body-flush ts-table-scroll">
                    <table class="ts-table">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($task->completions as $completion)
                                <tr>
                                    @if ($completion->field_value === 'on')
                                        <td>{{ str_replace('_', ' ', ucfirst($completion->field_name)) }}</td>
                                        <td style="color:var(--ts-success);"><i class="bi bi-check-circle-fill"></i>
                                        </td>
                                    @elseif (Str::startsWith($completion->field_value, 'task_attachments/'))
                                        @php $filePath = '/public/storage/'.$completion->field_value; @endphp
                                        <td>{{ $completion->field_name }}</td>
                                        <td style="display:flex;gap:.3rem;flex-wrap:wrap;">
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="ts-btn ts-btn-outline ts-btn-sm"><i
                                                    class="bi bi-download me-1"></i>Download</a>
                                            <a href="{{ asset($filePath) }}" target="_blank"
                                                class="ts-btn ts-btn-outline ts-btn-sm"><i
                                                    class="bi bi-eye me-1"></i>View</a>
                                        </td>
                                    @elseif ($completion->field_value != 'on')
                                        <td>{{ str_replace('_', ' ', ucfirst($completion->field_name)) }}</td>
                                        <td>{{ $completion->field_value }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($task->project->credentials->count() && $task->title == 'Add the Client Credentials')
            <div class="ts-col-6 ts-card">
                <div class="ts-card-header"><span class="ts-card-title"><i
                            class="bi bi-key me-1"></i>Credentials</span></div>
                <div style="max-height:400px;overflow-y:auto;">
                    @foreach ($task->project->credentials as $credential)
                        <div class="ts-cred">
                            <div style="margin-bottom:.25rem;"><span class="ts-cred-key">Platform</span> <span
                                    class="ts-cred-val">{{ $credential->platform_name }}</span></div>
                            <div style="margin-bottom:.25rem;"><span class="ts-cred-key">URL</span> <a
                                    href="{{ $credential->url }}" target="_blank"
                                    style="font-size:.78rem;color:var(--ts-accent);">Visit</a></div>
                            <div style="margin-bottom:.25rem;"><span class="ts-cred-key">Username</span> <span
                                    class="ts-cred-val">{{ $credential->username }}</span></div>
                            <div style="display:flex;align-items:center;gap:.5rem;">
                                <span class="ts-cred-key">Password</span>
                                <span class="password-toggle ts-cred-val"
                                    data-password="{{ $credential->password }}">••••••</span>
                                <a href="javascript:void(0);"
                                    class="toggle-password ts-btn ts-btn-ghost ts-btn-sm">Show</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endif

{{-- ══════════════════════════════════════════
     KW/URL TABLES
══════════════════════════════════════════ --}}
<div class="ts-row" style="margin-bottom:.85rem;">
    <div class="ts-col-6 starting-kw-url-wrapper" style="display:none;">
        <div class="ts-card h-100">
            <div class="ts-card-header">
                <span class="ts-card-title">Assigned Keywords &amp; URLs</span>
                <span class="ts-badge ts-badge-gray">Starting</span>
            </div>
            <div class="ts-card-body-flush ts-table-scroll">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Source URL</th>
                            @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="startingKwUrlsTable"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="ts-col-6 closing-kw-url-wrapper" style="display:none;">
        <div class="ts-card h-100">
            <div class="ts-card-header">
                <span class="ts-card-title">Task Keywords &amp; URLs</span>
                <span class="ts-badge ts-badge-green">Closing</span>
            </div>
            <div class="ts-card-body-flush ts-table-scroll">
                <table class="ts-table">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Source URL</th>
                            <th>Destination</th>
                            <th>Cost</th>
                            @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="closingKwUrlsTable"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="task_id" value="{{ $task->id }}">
<input type="hidden" name="created_by" value="{{ auth()->user()->id }}">

{{-- ══════════════════════════════════════════
     PARTNER LINKS
══════════════════════════════════════════ --}}
@if ($task->title == 'OFFSITE - PARTNER LINKS TASK')
    <div class="ts-card" style="margin-bottom:.85rem;">
        <div class="ts-card-header"><span class="ts-card-title"><i class="bi bi-link-45deg me-1"></i>Partner
                Links</span></div>
        <div class="ts-card-body-flush ts-table-scroll">
            <table class="ts-table">
                <thead>
                    <tr>
                        <th>Keyword</th>
                        <th>URL</th>
                        <th>Backlink</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($task->partnerLinks as $link)
                        <tr>
                            <td>{{ $link->keyword }}</td>
                            <td>{{ $link->url }}</td>
                            <td>{{ $link->backlink }}</td>
                            <td>{{ $link->amount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ══════════════════════════════════════════
     COMMENTS
══════════════════════════════════════════ --}}
<div class="ts-card" style="margin-bottom:.85rem;">
    <div class="ts-card-header"><span class="ts-card-title"><i class="bi bi-chat-dots me-1"></i>Task
            Comments</span></div>
    <div class="ts-card-body">
        <ul id="comment-list" style="list-style:none;padding:0;margin-bottom:.75rem;"></ul>
        <div style="display:flex;flex-direction:column;gap:.6rem;">
            <textarea rows="5" class="editor ts-textarea" style="resize:vertical;"></textarea>
            <div style="text-align:right;">
                <button type="button" id="add-commentt" data-taskID="{{ $task->id }}"
                    onclick="doComment()" class="ts-btn ts-btn-primary">
                    <i class="bi bi-send me-1"></i>Send
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     COLLABORATORS + ACTIVITY
══════════════════════════════════════════ --}}
<div class="ts-row" style="margin-bottom:.85rem;">
    <div class="ts-col-6 ts-card">
        <div class="ts-card-header">
            <span class="ts-card-title"><i class="bi bi-people me-1"></i>Collaborators</span>
            <select onchange="addCollaborator('{{ $task->id }}')" id="user_id" class="ts-select"
                style="min-width:140px;max-width:170px;">
                <option value="">Add user…</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="ts-card-body">
            <div id="collaborators-list"></div>
        </div>
    </div>
    <div class="ts-col-6 ts-card">
        <div class="ts-card-header"><span class="ts-card-title"><i class="bi bi-clock-history me-1"></i>Task
                Activity</span></div>
        <div class="ts-card-body" style="max-height:300px;overflow-y:auto;">
            @forelse ($task->history as $history)
                <div class="ts-tl-item">
                    <span class="ts-tl-dot"></span>
                    <span class="ts-tl-text">{{ ucwords(str_replace('_', ' ', $history->action)) }}</span>
                    <span class="ts-tl-time">{{ $history->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p style="font-size:.78rem;color:var(--ts-muted);text-align:center;margin:1rem 0;">No activity yet.
                </p>
            @endforelse
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     DANGER ZONE
══════════════════════════════════════════ --}}
@if (auth()->user()->hasRole('ADMIN'))
    <div class="ts-danger-zone">
        <div class="ts-danger-title">Danger Zone</div>
        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline-block;"
            onsubmit="return confirm('Are you sure you want to delete this task?');">
            @csrf @method('DELETE')
            <button type="submit" class="ts-btn ts-btn-danger"><i class="bi bi-trash me-1"></i>Delete
                Task</button>
        </form>
    </div>
@endif

{{-- ── Floating Save ── --}}
<button class="btn-save-pulse"><i class="bi bi-floppy me-1"></i>Save</button>
<input type="hidden" id="taskId" value="{{ $task->id }}">

{{-- ════════════════════════════════════════════════════════════
     ALL ORIGINAL SCRIPTS — 100% UNCHANGED FUNCTIONALITY
════════════════════════════════════════════════════════════ --}}
<script>
    $(document).ready(function() {
        $('#addUrlGroup').click(function() {
            $('#urlGroups').append(`
            <div class="url-group">
                <div class="ts-form-group"><label>URL</label><input type="url" class="ts-input url-input" placeholder="https://example.com" required></div>
                <div class="ts-form-group"><label>Keywords <span style="font-size:.68rem;color:var(--ts-muted);font-weight:400;">(one per line)</span></label><textarea class="ts-textarea keyword-textarea" rows="4" placeholder="Enter keywords..." required></textarea></div>
                <div style="text-align:right;"><button type="button" class="ts-btn ts-btn-danger ts-btn-sm remove-url-group"><i class="bi bi-trash me-1"></i>Remove</button></div>
            </div>`);
        });
        $(document).on('click', '.remove-url-group', function() {
            $(this).closest('.url-group').remove();
        });
        $('#keywordForm3').submit(function(e) {
            e.preventDefault();
            let combinedKeywords = [];
            $('.url-group').each(function() {
                const url = $(this).find('.url-input').val().trim();
                const keywordsRaw = $(this).find('.keyword-textarea').val();
                if (url && keywordsRaw) {
                    const keywords = keywordsRaw.split('\n').map(k => k.trim()).filter(k => k
                        .length > 0);
                    keywords.forEach(keyword => {
                        combinedKeywords.push({
                            keyword,
                            url
                        });
                    });
                }
            });
            if (combinedKeywords.length === 0) {
                toastr.error("Please enter at least one keyword.");
                return;
            }
            let formData = new FormData();
            formData.append("task_id", "{{ $task->id }}");
            formData.append("type", "closing");
            combinedKeywords.forEach((item, index) => {
                formData.append(`keywords[${index}][keyword]`, item.keyword);
                formData.append(`keywords[${index}][url]`, item.url);
            });
            $.ajax({
                url: "{{ route('projects.storeKeywords', $task->project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) triggerCompletionSubmit();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });

    $(document).on('click', '.toggle-password', function() {
        let span = $(this).prev('.password-toggle'),
            pw = span.data('password');
        span.text() === '••••••' ? (span.text(pw), $(this).text('Hide')) : (span.text('••••••'), $(this).text(
            'Show'));
    });

    var index = 1;
    $('#addMore').on('click', function() {
        const section = `<div class="link-group">
        <div class="ts-form-row">
            <div class="ts-form-group"><label>Keyword</label><input type="text" name="links[${index}][keyword]" class="ts-input" required></div>
            <div class="ts-form-group"><label>URL</label><input type="url" name="links[${index}][url]" class="ts-input" required></div>
            <div class="ts-form-group"><label>Backlink</label><input type="url" name="links[${index}][backlink]" class="ts-input" required></div>
            <div class="ts-form-group"><label>Amount</label><input type="number" step="0.01" name="links[${index}][amount]" class="ts-input"></div>
        </div>
        <div style="text-align:right;"><button type="button" class="ts-btn ts-btn-danger ts-btn-sm remove-section">Remove</button></div>
    </div>`;
        $('#link-sections').append(section);
        index++;
    });
    $(document).on('click', '.remove-section', function() {
        $(this).closest('.link-group').remove();
    });

    $('#partnerLinkForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route('partner-links.store-multiple') }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                if (res.success) {
                    toastr.success('Links added successfully!');
                    triggerCompletionSubmit();
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    function fetchCollaborators(taskId) {
        $.get("{{ route('task.collaborators', ['task' => ':id']) }}".replace(':id', taskId), function(response) {
            $('#collaborators-list').empty();
            response.forEach(collaborator => {
                $('#collaborators-list').append(`
            <div class="ts-collab">
                <img src="{{ asset('/public/storage') }}/${collaborator.user.img}" class="ts-collab img">
                <div style="flex:1;min-width:0;">
                    <div class="ts-collab-name">${collaborator.user.name}</div>
                    <div class="ts-collab-email">${collaborator.user.email}</div>
                </div>
                @if ($task->created_by == auth()->user()->id || auth()->user()->hasRole('ADMIN'))
                <button onclick="removeCollaborator(${collaborator.id}, ${taskId})" class="ts-btn ts-btn-danger ts-btn-sm">
                    <i class="bi bi-person-dash"></i>
                </button>
                @endif
            </div>`);
            });
        });
    }

    function addCollaborator(taskId) {
        $.post("{{ route('task.collaborators.store') }}", {
                task_id: taskId,
                user_id: $('#user_id').val(),
                _token: "{{ csrf_token() }}"
            },
            function(response) {
                fetchCollaborators(taskId);
                toastr.success(response.message);
            });
    }

    function removeCollaborator(id, taskId) {
        $.ajax({
            url: "{{ route('task.collaborators.destroy', ['collaborator' => ':id']) }}".replace(':id', id),
            type: 'DELETE',
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                fetchCollaborators(taskId);
                toastr.success(response.message);
            }
        });
    }

    function loadStartingKwUrls() {
        $.ajax({
            url: '{{ route('offsite.tasks.starting_kw_urls', ['task_id' => $task->id]) }}',
            type: "GET",
            success: function(data) {
                var tb = $("#startingKwUrlsTable");
                tb.empty();
                data.forEach(function(kwUrl) {
                    tb.append(`<tr id="row-${kwUrl.id}"><td>${kwUrl.keyword}</td><td><a href="${kwUrl.url}" target="_blank">${kwUrl.url}</a></td>
                @if (
                    $userPermissions->contains('can remove offsite links') &&
                        $task->status !== 'Completed' &&
                        ($task->createdBy && $task->createdBy->id == auth()->user()->id))
                    <td><button class="ts-btn ts-btn-danger ts-btn-sm delete-btn" data-id="${kwUrl.id}"><i class="bi bi-trash"></i></button></td>
                @endif
                </tr>`);
                });
                if (data.length > 0) $('.starting-kw-url-wrapper').slideDown();
            }
        });
    }

    function loadClosingKwUrls() {
        $.ajax({
            url: '{{ route('offsite.tasks.closing_kw_urls', ['task_id' => $task->id]) }}',
            type: "GET",
            success: function(data) {
                var tb = $("#closingKwUrlsTable");
                tb.empty();
                data.forEach(function(kwUrl) {
                    tb.append(`<tr id="row-${kwUrl.id}"><td>${kwUrl.keyword}</td><td><a href="${kwUrl.url}" target="_blank">${kwUrl.url}</a></td><td><a href="${kwUrl.destination_url}" target="_blank">${kwUrl.destination_url}</a></td><td>${kwUrl.link_cost}</td>
                @if ($userPermissions->contains('can remove offsite links') && $task->status !== 'Completed')
                    <td><button class="ts-btn ts-btn-danger ts-btn-sm delete-btn" data-id="${kwUrl.id}"><i class="bi bi-trash"></i></button></td>
                @endif
                </tr>`);
                });
                if (data.length > 0) $('.closing-kw-url-wrapper').slideDown();
            }
        });
    }
    setTimeout(() => {
        loadStartingKwUrls();
        loadClosingKwUrls();
    }, 2000);

    $(document).ready(function() {
        fetchCollaborators("{{ $task->id }}");
        var deleteUrlTemplate = '{{ route('project-keyword.delete', ['id' => ':id']) }}';
        $(document).off("click", ".delete-btn").on("click", ".delete-btn", function(event) {
            event.preventDefault();
            var id = $(this).data("id"),
                deleteUrl = deleteUrlTemplate.replace(":id", id);
            $.ajax({
                url: deleteUrl,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#row-" + id).fadeOut();
                    toastr.success("Keyword deleted successfully!");
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || "Failed to delete record!");
                }
            });
        });
        $(document).on('click', '.open-add-more-links-wrapper', function() {
            $('.add-more-links-wrapper').slideDown();
        });

        function toggleRepeatStatus(taskId, action) {
            $.ajax({
                url: "{{ url('/tasks') }}/" + taskId + "/" + action,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function() {
                    alert('Something went wrong. Try again.');
                }
            });
        }
        $(document).on('click', '.disable-repeating', function() {
            toggleRepeatStatus($(this).data('task-id'), "disable-repeating");
        });
        $(document).on('click', '.enable-repeating', function() {
            toggleRepeatStatus($(this).data('task-id'), "enable-repeating");
        });
    });

    $(document).on('click', '.complete-task-btn', function() {
        $('.completion-form-wrapper').slideDown();
        $(this).fadeOut();
    });

    var triggered = false;

    function triggerCompletionSubmit() {
        if (triggered) return;
        triggered = true;
        let button = $('.complete-task-btn');
        $('button[type="submit"]').prop('disabled', true).text('Loading...');
        setTimeout(() => $('button[type="submit"]').prop('disabled', false).text('Submit'), 3000);
        $.ajax({
            url: "{{ route('task.markAsCompleted', $task->id) }}",
            type: "PATCH",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    button.fadeIn().text("Completed").removeClass("ts-btn-success").addClass("ts-btn-done")
                        .prop("disabled", true);
                    toastr.success("Task marked as completed!", "Success");
                    $('.completion-form-wrapper').slideUp();
                    $('.completion-anim-wrapper').fadeIn();
                    setTimeout(() => {
                        $('.completion-anim-wrapper').fadeOut();
                        setTimeout(() => {
                            window.location.href = "{{ route('tasks.index') }}";
                        }, 1000);
                    }, 1000);
                } else {
                    toastr.error(response.message || "Something went wrong!");
                }
            },
            error: function() {
                toastr.error("Failed to mark task as completed.", "Error");
            }
        });
    }

    $(document).ready(function() {
        $(document).on("submit", "#taskForm", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            triggerCompletionSubmit();
            $.ajax({
                url: "{{ route('tasks.complete') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message, "Success");
                    if (xhr.status === 200) triggerCompletionSubmit();
                },
                error: function(xhr) {
                    toastr.error("Error: " + xhr.responseText, "Error");
                }
            });
        });
    });

    $(document).ready(function() {
        let credentialIndex = 1;
        $("#addCredential").click(function() {
            $("#credentialsContainer").append(`<div class="ts-cred-form-row credentialRow" style="margin-bottom:.45rem;">
            <input required class="ts-input" type="text" name="credentials[${credentialIndex}][platform_name]" placeholder="Platform Name">
            <input class="ts-input" type="text" name="credentials[${credentialIndex}][url]" placeholder="Website URL">
            <input required class="ts-input" type="text" name="credentials[${credentialIndex}][username]" placeholder="Username/Email">
            <div style="display:flex;gap:.4rem;align-items:center;">
                <input class="ts-input" type="password" name="credentials[${credentialIndex}][password]" placeholder="Password">
                <button type="button" class="removeCredential ts-btn ts-btn-danger ts-btn-sm">Remove</button>
            </div>
        </div>`);
            credentialIndex++;
        });
        $(document).on("click", ".removeCredential", function() {
            $(this).closest(".credentialRow").remove();
        });
        $("#clientCredentialsForm").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) triggerCompletionSubmit();
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
        $("#clientCredentialsForm2").submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('projects.storeClientCredentials', $task->project_id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response, textStatus, xhr) {
                    toastr.success(response.message);
                    if (xhr.status === 200) {
                        let form = $('.ga-gmb-form'),
                            fd = form.serialize();
                        $.ajax({
                            url: "{{ route('tasks.complete') }}",
                            type: "POST",
                            data: fd
                        });
                        setTimeout(() => triggerCompletionSubmit(), 1000);
                    }
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });

    $(document).ready(function() {
        $("#keywordForm2").submit(function(e) {
            e.preventDefault();
            let keyword = $("#mainKeyword").val().trim(),
                linkCost = $("#mainLinkCost").val().trim(),
                destinationUrl = $("#destinationUrl").val().trim(),
                urls = $("#urlList").val().trim().split("\n").filter(url => url.trim() !== "");
            if (!keyword || urls.length === 0) {
                alert("Please enter a keyword and at least one valid URL.");
                return;
            }
            let formData = new FormData();
            formData.append("_token", $('input[name="_token"]').val());
            formData.append("type", $('input[name="type"]').val());
            formData.append("task_id", "{{ $task->id }}");
            urls.forEach((url, index) => {
                formData.append(`keywords[${index}][keyword]`, keyword);
                formData.append(`keywords[${index}][link_cost]`, linkCost);
                formData.append(`keywords[${index}][destination_url]`, destinationUrl);
                formData.append(`keywords[${index}][url]`, url.trim());
            });
            $.ajax({
                url: "{{ route('projects.storeKeywords', $task->project->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    response.data.forEach(() => {
                        loadClosingKwUrls();
                        $('.add-more-links-wrapper').fadeOut();
                    });
                    ["#mainKeyword", "#mainLinkCost", "#destinationUrl", "#urlList"].forEach
                        (id => $(id).val(''));
                },
                error: function(xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        });
    });

    function timeAgo(date) {
        const seconds = Math.floor((new Date() - new Date(date)) / 1000);
        const intervals = [{
            label: "year",
            seconds: 31536000
        }, {
            label: "month",
            seconds: 2592000
        }, {
            label: "week",
            seconds: 604800
        }, {
            label: "day",
            seconds: 86400
        }, {
            label: "hour",
            seconds: 3600
        }, {
            label: "minute",
            seconds: 60
        }];
        for (const interval of intervals) {
            const count = Math.floor(seconds / interval.seconds);
            if (count > 0) return `${count} ${interval.label}${count !== 1 ? "s" : ""} ago`;
        }
        return seconds < 10 ? "Just now" : `${seconds} seconds ago`;
    }

    function doComment() {
        const button = document.getElementById('add-commentt'),
            taskId = button.getAttribute('data-taskID'),
            commentText = $('.editor').val();
        fetch("{{ route('comments.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    task_id: taskId,
                    comment: commentText
                })
            })
            .then(r => r.json()).then(data => {
                loadComments();
                if (typeof editor !== 'undefined') editor.setContent('');
            })
            .catch(error => console.error("Error:", error));
    }

    function loadComments() {
        let taskId = $("input[name='task_id']").val();
        $.get("{{ route('comments.index', $task->id) }}", function(data) {
            $('#comment-list').empty();
            data.forEach(comment => {
                $('#comment-list').append(`
            <div class="ts-comment">
                <img src="{{ asset('/public/storage') }}/${comment.user.img}" alt="" class="ts-comment-avatar">
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.2rem;">
                        <div>
                            <span class="ts-comment-name">${comment.user.name}${comment.user_id == {{ auth()->id() }} ? ' <span style="font-size:.65rem;color:var(--ts-muted);">(you)</span>' : ''}</span>
                            <span class="ts-comment-time comment-time d-block" data-time="${comment.created_at}"></span>
                        </div>
                        ${comment.user_id === {{ auth()->id() }} ? `<button type="button" data-id="${comment.id}" class="delete-comment ts-btn ts-btn-danger ts-btn-sm"><i class="bi bi-trash"></i></button>` : ''}
                    </div>
                    <p class="ts-comment-body">${comment.comment}</p>
                </div>
            </div>`);
            });
            updateTimestamps();
        });
    }

    function updateTimestamps() {
        document.querySelectorAll(".comment-time").forEach(el => {
            el.textContent = timeAgo(el.getAttribute("data-time"));
        });
    }
    setInterval(updateTimestamps, 30000);

    $(document).on('click', '.delete-comment', function() {
        let commentId = $(this).data('id');
        $.ajax({
            url: "{{ route('comments.destroy', ['id' => ':id']) }}".replace(':id', commentId),
            type: "DELETE",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function() {
                loadComments();
            }
        });
    });

    function loadTaskAttachments() {
        let taskId = "{{ $task->id }}";
        $.ajax({
            url: "{{ route('tasks.attachments.index', ':id') }}".replace(':id', taskId),
            type: "GET",
            success: function(response) {
                renderAttachments(response.attachments);
            }
        });
    }

    function renderAttachments(attachments) {
        let html = '';
        attachments.forEach(file => {
            let filePath = "{{ asset('/public/storage/') }}/" + file.file_path;
            let original_name = file?.original_name,
                extension = file.file_path.split('.').pop();
            let imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                createdAt = new Date(file.created_at).toLocaleString();
            html += `<div class="ts-attachment">
            <img src="${imageExtensions.includes(extension) ? filePath : '{{ asset('/images/doc.png') }}'}" alt="" class="ts-attachment-thumb" style="${imageExtensions.includes(extension)?'':'background:#f1f5f9;border-radius:6px;'}">
            <div style="flex:1;min-width:0;">
                <div class="ts-attachment-name"><a target="_blank" href="${filePath}">${original_name}</a></div>
                <div class="ts-attachment-meta">${file?.user?.name} · ${createdAt}</div>
            </div>
            <div style="display:flex;gap:.3rem;flex-shrink:0;">
                <a href="${filePath}" download="${original_name}" class="ts-btn ts-btn-outline ts-btn-sm"><i class="bi bi-download"></i></a>
                <button type="button" class="delete-attachment ts-btn ts-btn-danger ts-btn-sm" data-attachment-id="${file.id}" data-delete-url="{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', file.id)"><i class="bi bi-trash"></i></button>
            </div>
        </div>`;
        });
        $("#taskAttachmentsContainer").html(html);
    }

    var uploadedDocumentMap = {};
    Dropzone.autoDiscover = false;
    var taskDropzone = new Dropzone("#taskAttachmentDropzone", {
        url: "{{ route('tasks.attachments.store', $task->id) }}",
        autoProcessQueue: false,
        uploadMultiple: true,
        paramName: "attachments",
        maxFiles: 10,
        maxFilesize: 5,
        acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt",
        addRemoveLinks: true,
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        init: function() {
            var dz = this;
            dz.on("sending", function(file, xhr, formData) {
                var fd = new FormData(document.querySelector("#task-create-form"));
                fd.forEach((v, k) => formData.append(k, v));
                formData.append("attachments[]", file);
            });
            dz.on("success", function(f, r) {
                uploadedDocumentMap[f.name] = r.name;
            });
            dz.on("removedfile", function(f) {
                delete uploadedDocumentMap[f.name];
            });
        }
    });

    $(document).on('click', '#taskAttachments', function(e) {
        $('#uploadTaskAttachmentsForm').slideDown();
    });
    $(document).on("click", "#uploadTaskFilesBtn", function(e) {
        var formData = new FormData($("#taskAttachmentDropzone")[0]);
        taskDropzone.files.forEach(function(file) {
            formData.append("attachments[]", file);
        });
        $.ajax({
            url: "{{ route('tasks.attachments.store', $task->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success('Task attachments added');
                renderAttachments(response.attachments);
                $('#uploadTaskAttachmentsForm').slideUp();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Error in submission!");
            }
        });
    });
    $(document).on("click", ".delete-attachment", function() {
        let attachmentId = $(this).attr("data-attachment-id");
        if (confirm("Are you sure you want to delete this attachment?")) {
            $.ajax({
                url: "{{ route('tasks.attachments.destroy', ':id') }}".replace(':id', attachmentId),
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    if (response.success) {
                        $("#attachment-" + attachmentId).remove();
                        toastr.info("Task attachment deleted!");
                        loadTaskAttachments();
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function(xhr) {
                    console.error("Error:", xhr);
                    alert("Something went wrong!");
                }
            });
        }
    });

    function updateTaskField(element) {
        let allowEdit = {{ auth()->user()->hasPermission('can edit task') ? '1' : '0' }};
        let taskId = $("#taskId").val(),
            fieldName = $(element).attr("name"),
            fieldValue = $(element).val();
        if (allowEdit == 1) {
            setTimeout(() => {
                $.ajax({
                    url: "{{ route('tasks.update.json', ':id') }}".replace(':id', taskId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        field: fieldName,
                        value: fieldValue
                    },
                    success: function(response) {
                        toastr.success(`${response.message}`, "Success");
                    },
                    error: function(xhr) {
                        toastr.error("Update failed. Please try again.");
                    }
                });
            }, 500);
        } else {
            toastr.error("Editing Not Allowed");
        }
    }

    function handleTaskStatusChange(element) {
        let taskId = $(element).data("task-id"),
            newStatus = $(element).val();
        $.ajax({
            url: "{{ route('tasks.updateStatus', ':id') }}".replace(':id', taskId),
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                status: newStatus,
                task_id: taskId
            },
            success: function(response) {
                if (response) toastr.success(response.message);
                else toastr.error("Task status can't change!", "Error");
            },
            error: function(xhr) {
                toastr.error("Task status can't change!", "Error");
            }
        });
    }

    $(document).ready(function() {
        loadComments();
        loadTaskAttachments();
    });

    function taskLinkCopy(link) {
        navigator.clipboard.writeText(link)
            .then(() => toastr.success('Task link copied to clipboard!'))
            .catch(err => toastr.success('Could not copy the link.'));
    }
</script>
