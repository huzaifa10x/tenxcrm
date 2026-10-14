
<style>
    :root {
        --ds-bg: #f0f2f7;
        --ds-surface: #ffffff;
        --ds-border: #e2e6ef;
        --ds-text: #1a1f36;
        --ds-muted: #6b7280;
        --ds-accent: #3b5bdb;
        --ds-accent-soft: #eef2ff;
        --ds-success: #087f5b;
        --ds-warning: #e67700;
        --ds-danger: #c92a2a;
        --ds-radius: 12px;
        --ds-radius-lg: 18px;
        --ds-shadow: 0 1px 4px rgba(0, 0, 0, .07), 0 4px 16px rgba(0, 0, 0, .05);
    }

    .crm-page { background: var(--ds-bg); min-height: 100vh; padding: 0; }

    .crm-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .75rem; margin-bottom: 1rem;
    }
    .crm-toolbar h1 { font-size: 1.4rem; font-weight: 800; color: var(--ds-text); margin: 0; }

    .crm-view-toggle .btn { font-size: .8rem; border-radius: 8px; }

    .crm-filter-bar {
        background: var(--ds-surface); border: 1px solid var(--ds-border);
        border-radius: var(--ds-radius); padding: 1rem; margin-bottom: 1rem; box-shadow: var(--ds-shadow);
    }
    .crm-filter-bar .form-label { font-size: .75rem; font-weight: 600; margin-bottom: .25rem; color: var(--ds-text); }
    .crm-filter-bar .form-control, .crm-filter-bar .form-select { font-size: .82rem; border-radius: 8px; border-color: var(--ds-border); }

    /* ── Kanban board ─────────────────────────────────────────── */
    .crm-board { display: flex; gap: .85rem; overflow-x: auto; padding-bottom: 1rem; align-items: flex-start; }
    .crm-col {
        flex: 0 0 290px; max-width: 240px; background: #eef1f6;
        border-radius: var(--ds-radius); display: flex; flex-direction: column;
        max-height: calc(100vh - 270px); border: 1px solid var(--ds-border);
    }
    .crm-col-head {
        padding: .65rem .85rem; border-top: 4px solid var(--ds-accent);
        border-radius: var(--ds-radius) var(--ds-radius) 0 0; background: #fff;
        position: sticky; top: 0; z-index: 2;
    }
    .crm-col-head .col-name { font-size: .8rem; font-weight: 800; color: var(--ds-text); text-transform: uppercase; letter-spacing: .3px; }
    .crm-col-head .col-meta { font-size: .72rem; color: var(--ds-muted); margin-top: .15rem; }
    .crm-col-body { padding: .6rem; overflow-y: auto; flex: 1; min-height: 60px; }

    .crm-card {
        background: #fff; border: 1px solid var(--ds-border); border-radius: 10px;
        padding: .7rem .8rem; margin-bottom: .55rem; box-shadow: 0 1px 2px rgba(0,0,0,.05);
        cursor: grab; transition: box-shadow .15s, transform .05s;
    }
    .crm-card:hover { box-shadow: 0 4px 14px rgba(0,0,0,.1); }
    .crm-card:active { cursor: grabbing; }
    .crm-card.sortable-ghost { opacity: .4; }
    .crm-card.sortable-chosen { transform: rotate(-1deg); }
    .crm-card .card-title { font-size: .85rem; font-weight: 700; color: var(--ds-text); line-height: 1.3; margin-bottom: .25rem; word-break: break-word; }
    .crm-card .card-sub { font-size: .74rem; color: var(--ds-muted); margin-bottom: .4rem; }
    .crm-card .card-foot { display: flex; align-items: center; justify-content: space-between; }
    .crm-card .card-value { font-size: .78rem; font-weight: 700; color: var(--ds-success); }
    .crm-card .card-avatar {
        width: 24px; height: 24px; border-radius: 50%; background: var(--ds-accent-soft);
        color: var(--ds-accent); display: inline-flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700;
    }
    .crm-card .card-label { font-size: .64rem; font-weight: 700; padding: .1rem .45rem; border-radius: 20px; }
    .crm-card.is-rotting { border-left: 3px solid var(--ds-warning); }
    .crm-rot-flag { color: var(--ds-warning); font-size: .72rem; }

    /* ── Deal detail ──────────────────────────────────────────── */
    .crm-stage-bar { display: flex; gap: 3px; margin: .75rem 0 1.25rem; }
    .crm-stage-seg { flex: 1; text-align: center; font-size: .68rem; font-weight: 700; color: #fff; padding: .35rem .2rem; position: relative; }
    .crm-stage-seg:first-child { border-radius: 6px 0 0 6px; }
    .crm-stage-seg:last-child { border-radius: 0 6px 6px 0; }
    .crm-stage-seg.passed { background: var(--ds-success); }
    .crm-stage-seg.current { background: #1f6f3d; }
    .crm-stage-seg.future { background: #ced4da; color: #495057; }
    .crm-stage-seg small { display: block; font-weight: 600; opacity: .9; font-size: .62rem; }

    .crm-panel { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius); box-shadow: var(--ds-shadow); }
    .crm-panel-head { padding: .85rem 1.1rem; border-bottom: 1px solid var(--ds-border); font-weight: 700; font-size: .9rem; color: var(--ds-text); }
    .crm-panel-body { padding: 1.1rem; }
    .crm-meta-row { display: flex; justify-content: space-between; padding: .4rem 0; font-size: .82rem; border-bottom: 1px dashed #eef1f6; }
    .crm-meta-row span:first-child { color: var(--ds-muted); }
    .crm-meta-row span:last-child { font-weight: 600; color: var(--ds-text); }

    .crm-timeline-item { display: flex; gap: .75rem; padding: .7rem 0; border-bottom: 1px solid #f0f2f7; }
    .crm-timeline-icon {
        width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
        background: var(--ds-accent-soft); color: var(--ds-accent);
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .crm-timeline-item.done .crm-timeline-icon { background: #ebfbee; color: var(--ds-success); }
    .crm-empty { text-align: center; color: var(--ds-muted); padding: 2.5rem 1rem; font-size: .85rem; }
    .crm-empty i { font-size: 2rem; display: block; margin-bottom: .5rem; }
</style>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/pipedrive/_styles.blade.php ENDPATH**/ ?>