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
     <?php $__env->slot('header', null, []); ?> <?php echo e(__('Tasks')); ?> <?php $__env->endSlot(); ?>

    <?php
        $me = auth()->user();
        $tz = 'Asia/Dubai';
        $today = \Carbon\Carbon::now($tz)->startOfDay();
        $canChangeStatus = $me->hasPermission('can change task status');
        $canSeeAll = $me->hasRole('ADMIN') || $userPermissions->contains('can view all tasks');
        $canFilterPeople = $me->hasRole('ADMIN') || $userPermissions->contains('can view all tasks') || route('tasks.index') == url()->current();

        $scopes = [
            'assigned-to-me'        => ['label' => 'Assigned to me', 'icon' => 'bi-person-check'],
            'task-created-by-me'    => ['label' => 'Created by me', 'icon' => 'bi-pencil-square'],
            'where-im-collaborator' => ['label' => 'Collaborating', 'icon' => 'bi-people'],
        ];
        if ($canSeeAll) {
            $scopes['all-tasks'] = ['label' => 'All tasks', 'icon' => 'bi-grid'];
        }
        $scope = array_key_exists($scope, $scopes) ? $scope : 'assigned-to-me';
        // "Who" column: when the list is my own tasks, show who gave them to me.
        $whoIsCreator = $scope === 'assigned-to-me';

        $statuses = [
            'Todo'        => ['label' => 'To do',       'cls' => 'todo'],
            'In Progress' => ['label' => 'In progress', 'cls' => 'progress'],
            'Review'      => ['label' => 'Review',      'cls' => 'review'],
            'Completed'   => ['label' => 'Completed',   'cls' => 'done'],
            'Blocked'     => ['label' => 'Blocked',     'cls' => 'blocked'],
        ];
        $priorities = ['Urgent', 'High', 'Medium', 'Low'];
        $taskTypes = ['CONTENT','ONSITE','TECH MANAGER','OFFSITE','DESIGN','DEVELOPMENT','AM','SEO ADMIN','ADMIN','SALES MANAGER','SALES','ADHOC','SOCIAL MEDIA MANAGER'];

        $list = $tasks->where('repeat_task', '0');

        $initials = function ($name) {
            return collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)
                ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('') ?: '?';
        };
        $avatarHue = fn ($name) => crc32((string) $name) % 360;

        // Active server-side filters, shown as removable chips.
        $byId = $users->keyBy('id');
        $projectsById = $projects->keyBy('id');
        $chips = [];
        if (request('project_id')) $chips['project_id'] = 'Project: ' . ($projectsById[request('project_id')]->title ?? '#' . request('project_id'));
        if (request('user_id')) $chips['user_id'] = 'Assignee: ' . ($byId[request('user_id')]->name ?? '#' . request('user_id'));
        if (request('assigned_from')) $chips['assigned_from'] = 'Assigned by: ' . ($byId[request('assigned_from')]->name ?? '#' . request('assigned_from'));
        if (request('category')) $chips['category'] = 'Type: ' . request('category');
        if (request('all-automated')) $chips['all-automated'] = 'Automated only';
        if (request('start_date') || request('end_date')) $chips['start_date,end_date'] = 'Created: ' . (request('start_date') ?: '…') . ' → ' . (request('end_date') ?: '…');
        if (request('completed_date_start') || request('completed_date_end')) $chips['completed_date_start,completed_date_end'] = 'Completed: ' . (request('completed_date_start') ?: '…') . ' → ' . (request('completed_date_end') ?: '…');
        $moreFilterCount = count(array_intersect_key($chips, array_flip(['start_date,end_date', 'completed_date_start,completed_date_end', 'all-automated'])));
    ?>

    <style>
        .tq, #taskCreateModal, #offcanvasRight {
            --tq-bg: #f4f6f2;
            --tq-surface: #fff;
            --tq-border: #e3e9e2;
            --tq-line: #eef2ed;
            --tq-text: #0f1412;
            --tq-text-2: #3c4842;
            --tq-muted: #6b776f;
            --tq-lime: var(--x-lime, #a9e324);
            --tq-lime-hover: var(--x-lime-hover, #9ad01c);
            --tq-lime-soft: var(--x-lime-soft, #f2fadf);
            --tq-ink: var(--x-ink, #4f7a06);
            --tq-radius: 12px;
            --tq-shadow: 0 1px 2px rgba(15, 20, 18, .04), 0 2px 10px rgba(15, 20, 18, .04);
        }
        .tq { color: var(--tq-text); padding: 4px 0 40px; }

        /* ── Header ─────────────────────────────────────────────── */
        .tq-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 14px; }
        .tq .tq-title { font-size: 1.6rem !important; font-weight: 800; letter-spacing: -.5px; margin: 0; line-height: 1.2; }
        .tq-sub { color: var(--tq-muted); font-size: .88rem; margin-top: 4px; }
        .tq-head-actions { display: flex; gap: 8px; align-items: center; }

        .tq-btn { display: inline-flex; align-items: center; gap: 7px; height: 38px; padding: 0 14px; border-radius: 10px; font-size: .86rem; font-weight: 600; border: 1px solid transparent; cursor: pointer; white-space: nowrap; text-decoration: none; transition: background .15s, border-color .15s, color .15s, box-shadow .15s; }
        .tq-btn:focus-visible { outline: none; box-shadow: 0 0 0 .2rem rgba(169, 227, 36, .45); }
        .tq-btn-primary { background: var(--tq-lime); border-color: var(--tq-lime); color: var(--tq-text); font-weight: 700; }
        .tq-btn-primary:hover { background: var(--tq-lime-hover); border-color: var(--tq-lime-hover); color: var(--tq-text); }
        .tq-btn-ghost { background: transparent; color: var(--tq-muted); }
        .tq-btn-ghost:hover { background: var(--tq-surface); color: var(--tq-text); border-color: var(--tq-border); }
        .tq-btn-outline { background: var(--tq-surface); border-color: var(--tq-border); color: var(--tq-text-2); }
        .tq-btn-outline:hover { border-color: #c9d3c8; color: var(--tq-text); }
        .tq-btn .tq-count { background: var(--tq-text); color: #fff; border-radius: 20px; font-size: .68rem; padding: 1px 7px; }
        .tq-kbd { font: 600 .68rem/1 ui-monospace, SFMono-Regular, Menlo, monospace; border: 1px solid var(--tq-border); border-bottom-width: 2px; border-radius: 5px; padding: 2px 5px; color: var(--tq-muted); background: var(--tq-surface); }

        /* ── Scope tabs ─────────────────────────────────────────── */
        .tq-scopes { display: flex; gap: 4px; border-bottom: 1px solid var(--tq-border); margin-bottom: 16px; overflow-x: auto; scrollbar-width: none; }
        .tq-scopes::-webkit-scrollbar { display: none; }
        .tq-scope { display: inline-flex; align-items: center; gap: 7px; padding: 10px 14px; font-size: .88rem; font-weight: 600; color: var(--tq-muted); text-decoration: none; border-bottom: 2px solid transparent; margin-bottom: -1px; white-space: nowrap; }
        .tq-scope:hover { color: var(--tq-text); }
        .tq-scope.active { color: var(--tq-text); border-bottom-color: var(--tq-lime); }
        .tq-scope.active i { color: var(--tq-ink); }

        /* ── Toolbar ────────────────────────────────────────────── */
        .tq-toolbar { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 10px; }
        .tq-search { position: relative; flex: 1 1 260px; min-width: 220px; }
        .tq-search > i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--tq-muted); pointer-events: none; }
        .tq-search input { width: 100%; height: 38px; border: 1px solid var(--tq-border); border-radius: 10px; padding: 0 70px 0 38px; font-size: .88rem; background: var(--tq-surface); color: var(--tq-text); transition: border-color .15s, box-shadow .15s; }
        .tq-search input:focus { outline: none; border-color: var(--tq-lime); box-shadow: 0 0 0 .2rem rgba(169, 227, 36, .3); }
        .tq-search .tq-kbd { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); }
        .tq-select { height: 38px; line-height: 36px; border: 1px solid var(--tq-border); border-radius: 10px; padding: 0 30px 0 12px; font-size: .85rem; color: var(--tq-text-2); background-color: var(--tq-surface); max-width: 200px; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236b776f' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 10px center; background-size: 12px; }
        .tq-select:focus { outline: none; border-color: var(--tq-lime); box-shadow: 0 0 0 .2rem rgba(169, 227, 36, .3); }
        .tq-select.is-set { border-color: var(--tq-ink); color: var(--tq-text); font-weight: 600; background-color: var(--tq-lime-soft); }

        #tqSearchResults { position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: var(--tq-surface); border: 1px solid var(--tq-border); border-radius: 12px; box-shadow: 0 12px 32px rgba(15, 20, 18, .12); z-index: 1050; max-height: 340px; overflow-y: auto; padding: 6px; }
        #tqSearchResults .tq-sr-head { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: var(--tq-muted); padding: 6px 10px; }
        #tqSearchResults a { display: flex; flex-direction: column; gap: 2px; padding: 8px 10px; border-radius: 8px; text-decoration: none; color: var(--tq-text); }
        #tqSearchResults a:hover, #tqSearchResults a:focus { background: var(--tq-lime-soft); outline: none; }
        #tqSearchResults .tq-sr-title { font-weight: 600; font-size: .86rem; }
        #tqSearchResults .tq-sr-meta { font-size: .75rem; color: var(--tq-muted); }

        .tq-more { background: var(--tq-surface); border: 1px solid var(--tq-border); border-radius: var(--tq-radius); padding: 14px; margin-bottom: 10px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; align-items: end; }
        .tq-more label { display: block; font-size: .75rem; font-weight: 700; color: var(--tq-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .4px; }
        .tq-range { display: flex; gap: 6px; }
        .tq-range input { flex: 1; min-width: 0; height: 36px; border: 1px solid var(--tq-border); border-radius: 9px; padding: 0 10px; font-size: .84rem; }
        .tq-switch { display: flex; align-items: center; gap: 8px; font-size: .86rem; font-weight: 600; color: var(--tq-text-2); height: 36px; }

        .tq-chips { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; }
        .tq-chip { display: inline-flex; align-items: center; gap: 6px; background: var(--tq-lime-soft); color: var(--tq-ink); border: 1px solid #d9eea8; border-radius: 20px; padding: 3px 6px 3px 11px; font-size: .78rem; font-weight: 600; }
        .tq-chip a { color: inherit; display: grid; place-items: center; width: 18px; height: 18px; border-radius: 50%; text-decoration: none; }
        .tq-chip a:hover { background: rgba(79, 122, 6, .15); }
        .tq-clear { font-size: .8rem; font-weight: 600; color: var(--tq-muted); text-decoration: none; margin-left: 4px; }
        .tq-clear:hover { color: var(--tq-text); text-decoration: underline; }

        /* ── Quick views (due buckets) ──────────────────────────── */
        .tq-quick { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; margin-bottom: 16px; }
        .tq-qcard { text-align: left; background: var(--tq-surface); border: 1px solid var(--tq-border); border-radius: var(--tq-radius); padding: 12px 14px; cursor: pointer; transition: border-color .15s, box-shadow .15s, background .15s; display: flex; align-items: center; gap: 12px; box-shadow: var(--tq-shadow); }
        .tq-qcard:hover { border-color: #cdd7cb; }
        .tq-qcard.active { border-color: var(--tq-text); box-shadow: 0 0 0 1px var(--tq-text); }
        .tq-qicon { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; font-size: 1rem; flex-shrink: 0; }
        .tq-qnum { font-size: 1.3rem; font-weight: 800; line-height: 1; }
        .tq-qlabel { font-size: .78rem; color: var(--tq-muted); font-weight: 600; margin-top: 3px; }
        .tq-q-overdue .tq-qicon { background: #fdecec; color: #c53030; }
        .tq-q-overdue .tq-qnum { color: #c53030; }
        .tq-q-today .tq-qicon { background: #fff4e0; color: #b45309; }
        .tq-q-week .tq-qicon { background: var(--tq-lime-soft); color: var(--tq-ink); }
        .tq-q-nodue .tq-qicon { background: #f1f3f0; color: var(--tq-muted); }

        /* ── Status tabs + list controls ────────────────────────── */
        .tq-listbar { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
        .tq-status-tabs { display: flex; gap: 4px; background: #eaeee8; padding: 4px; border-radius: 11px; overflow-x: auto; scrollbar-width: none; }
        .tq-status-tabs::-webkit-scrollbar { display: none; }
        .tq-stab { border: 0; background: transparent; border-radius: 8px; padding: 6px 12px; font-size: .84rem; font-weight: 600; color: var(--tq-muted); display: inline-flex; align-items: center; gap: 7px; white-space: nowrap; cursor: pointer; }
        .tq-stab:hover { color: var(--tq-text); }
        .tq-stab.active { background: var(--tq-surface); color: var(--tq-text); box-shadow: 0 1px 3px rgba(15, 20, 18, .1); }
        .tq-stab .n { font-size: .72rem; font-weight: 700; color: var(--tq-muted); background: rgba(15, 20, 18, .06); border-radius: 20px; padding: 1px 7px; }
        .tq-stab.active .n { background: var(--tq-text); color: #fff; }
        .tq-listctl { display: flex; gap: 8px; align-items: center; }
        .tq-listctl .tq-select { height: 34px; line-height: 32px; font-size: .82rem; }

        /* ── Task list ──────────────────────────────────────────── */
        .tq-list { background: var(--tq-surface); border: 1px solid var(--tq-border); border-radius: var(--tq-radius); box-shadow: var(--tq-shadow); overflow: hidden; }
        .tq-row, .tq-thead { display: grid; grid-template-columns: minmax(0, 1fr) 170px 120px 96px 130px 64px; align-items: center; gap: 12px; padding: 0 16px 0 0; }
        .tq-thead { font-size: .7rem; font-weight: 700; color: var(--tq-muted); text-transform: uppercase; letter-spacing: .6px; background: #fafbf9; border-bottom: 1px solid var(--tq-border); min-height: 38px; }
        .tq-thead > :first-child { padding-left: 20px; }
        .tq-row { position: relative; min-height: 64px; border-bottom: 1px solid var(--tq-line); cursor: pointer; transition: background .12s; }
        .tq-row:last-child { border-bottom: 0; }
        .tq-row[hidden], .tq-more[hidden] { display: none !important; }
        .tq-row:hover { background: #fafcf6; }
        .tq-row:focus-visible { outline: none; background: #f6fbe9; box-shadow: inset 3px 0 0 var(--tq-lime); }
        .tq-row.is-open { background: #f4fae4; }
        .tq-row::before { content: ""; position: absolute; left: 0; top: 10px; bottom: 10px; width: 3px; border-radius: 0 3px 3px 0; background: transparent; }
        .tq-row[data-priority="Urgent"]::before { background: #e5484d; }
        .tq-row[data-priority="High"]::before { background: #f76b15; }

        .tq-task { padding: 10px 0 10px 20px; min-width: 0; }
        .tq-task-title { font-size: .95rem; font-weight: 650; color: var(--tq-text); line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .tq-row:hover .tq-task-title { color: var(--tq-ink); }
        .tq-row[data-status="Completed"] .tq-task-title { color: var(--tq-muted); text-decoration: line-through; text-decoration-color: #b8c2b9; }
        .tq-task-meta { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 4px; font-size: .76rem; color: var(--tq-muted); }
        .tq-task-meta .sep { color: #c6cec7; }
        .tq-id { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .72rem; }
        .tq-type { font-weight: 600; letter-spacing: .2px; }
        .tq-project { display: inline-flex; align-items: center; gap: 5px; color: var(--tq-text-2); max-width: 280px; min-width: 0; }
        .tq-project > span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .tq-project-link { display: inline-grid; place-items: center; width: 20px; height: 20px; border-radius: 5px; color: var(--tq-muted); text-decoration: none; opacity: .0; transition: opacity .12s, background .12s; }
        .tq-row:hover .tq-project-link, .tq-project-link:focus { opacity: 1; }
        .tq-project-link:hover { background: #eaeee8; color: var(--tq-text); }
        .tq-tag-auto { font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; color: #6b4fbb; background: #f1edfd; border-radius: 4px; padding: 1px 5px; }
        .tq-counts { display: inline-flex; gap: 8px; }
        .tq-counts span { display: inline-flex; align-items: center; gap: 3px; }

        .tq-person { display: flex; align-items: center; gap: 8px; min-width: 0; font-size: .83rem; color: var(--tq-text-2); }
        .tq-person > span:last-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .tq-avatar { width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; font-size: .66rem; font-weight: 700; flex-shrink: 0; color: #fff; }
        .tq-cell-label { display: none; }

        .tq-due { font-size: .82rem; color: var(--tq-text-2); white-space: nowrap; }
        .tq-due.overdue { color: #c53030; font-weight: 700; }
        .tq-due.today { color: #b45309; font-weight: 700; }
        .tq-due.none { color: #a3ada5; }
        .tq-due.done { color: var(--tq-muted); }

        .tq-prio { display: inline-flex; align-items: center; gap: 6px; font-size: .8rem; font-weight: 600; }
        .tq-prio i { font-size: .8rem; }
        .tq-prio-Urgent { color: #c53030; }
        .tq-prio-High { color: #c2410c; }
        .tq-prio-Medium { color: #5b6760; }
        .tq-prio-Low { color: #8a958d; }

        .tq-status { display: inline-flex; align-items: center; gap: 6px; border: 1px solid transparent; border-radius: 20px; padding: 3px 10px; font-size: .76rem; font-weight: 700; white-space: nowrap; background: none; cursor: default; }
        button.tq-status { cursor: pointer; }
        button.tq-status:hover { filter: brightness(.96); border-color: rgba(15, 20, 18, .12); }
        .tq-status .dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .tq-st-todo { background: #eef2ff; color: #3949ab; }
        .tq-st-progress { background: #f3ecff; color: #6d28d9; }
        .tq-st-review { background: #fff4e0; color: #b45309; }
        .tq-st-done { background: var(--tq-lime-soft); color: var(--tq-ink); }
        .tq-st-blocked { background: #fdecec; color: #c53030; }
        .tq-status-menu .dropdown-item { font-size: .85rem; display: flex; align-items: center; gap: 8px; padding: 7px 12px; border-radius: 6px; }
        .tq-status-menu { padding: 6px; border-radius: 10px; border-color: var(--tq-border); box-shadow: 0 10px 28px rgba(15, 20, 18, .12); min-width: 190px; }
        .tq-status-menu .dropdown-item.active, .tq-status-menu .dropdown-item:active { background: var(--tq-lime-soft); color: var(--tq-text); }
        .tq-status-menu .dropdown-header { font-weight: 700; color: #8a958d; font-size: .68rem; text-transform: uppercase; letter-spacing: .6px; padding: 4px 12px 6px; }

        .tq-actions { display: flex; justify-content: flex-end; gap: 2px; opacity: 0; transition: opacity .12s; }
        .tq-row:hover .tq-actions, .tq-row:focus-within .tq-actions { opacity: 1; }
        .tq-icon-btn { width: 30px; height: 30px; border-radius: 8px; display: grid; place-items: center; color: var(--tq-muted); background: transparent; border: 0; text-decoration: none; }
        .tq-icon-btn:hover { background: #eaeee8; color: var(--tq-text); }

        .tq-empty { padding: 56px 20px; text-align: center; color: var(--tq-muted); }
        .tq-empty i { font-size: 2rem; color: #c3ccc4; }
        .tq-empty .tq-empty-title { color: var(--tq-text); font-weight: 700; font-size: 1rem; margin: 10px 0 4px; }
        .tq-more-rows { padding: 12px; text-align: center; border-top: 1px solid var(--tq-line); }
        .tq-foot { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 12px; font-size: .78rem; color: var(--tq-muted); }
        .tq-foot .tq-kbd { margin: 0 2px; }

        /* Drawer */
        #offcanvasRight .offcanvas-header { border-bottom: 1px solid var(--tq-border); gap: 8px; }
        #offcanvasRight .tq-drawer-title { font-size: .95rem; font-weight: 700; margin: 0; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ── Responsive ─────────────────────────────────────────── */
        @media (max-width: 1199px) {
            .tq-row, .tq-thead { grid-template-columns: minmax(0, 1fr) 150px 110px 90px 124px 40px; }
            .tq-actions .tq-copy { display: none; }
        }
        @media (max-width: 991px) {
            .tq-quick { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .tq-thead { display: none; }
            .tq-row { grid-template-columns: 1fr auto; grid-template-areas: "task task" "who who" "due status"; padding: 12px 14px 12px 0; gap: 8px 12px; }
            .tq-task { grid-area: task; padding: 0 0 0 18px; }
            .tq-who { grid-area: who; padding-left: 18px; }
            .tq-due-cell { grid-area: due; padding-left: 18px; display: flex; gap: 12px; align-items: center; }
            .tq-prio-cell { display: none; }
            .tq-status-cell { grid-area: status; justify-self: end; }
            .tq-actions { display: none; }
            .tq-due-cell .tq-prio-inline { display: inline-flex !important; }
            .tq-project-link { opacity: 1; }
        }
        @media (max-width: 575px) {
            .tq .tq-title { font-size: 1.35rem !important; }
            .tq-head-actions .tq-btn-ghost span { display: none; }
            .tq-toolbar .tq-select { flex: 1 1 calc(50% - 4px); max-width: none; }
            .tq-search .tq-kbd { display: none; }
            .tq-listbar { flex-direction: column; align-items: stretch; }
            .tq-listctl { justify-content: space-between; }
        }

        [data-theme=dark] .tq { --tq-surface: #18201b; --tq-border: #26302a; --tq-line: #222b25; --tq-text: #f1f4f1; --tq-text-2: #cfd6d1; --tq-muted: #9aa59e; }
        [data-theme=dark] .tq-thead { background: #141b17; }
        [data-theme=dark] .tq-row:hover { background: #1d2620; }
        [data-theme=dark] .tq-status-tabs { background: #141b17; }
    </style>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-3 border-0 mb-3" style="font-size:.85rem;">
            <ul class="mb-0 ps-3"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
    <?php endif; ?>

    <div class="tq" id="tq">

        
        <div class="tq-head">
            <div>
                <h1 class="tq-title">Tasks</h1>
                <div class="tq-sub" id="tqSubtitle"><?php echo e($scopes[$scope]['label']); ?></div>
            </div>
            <div class="tq-head-actions">
                <a href="<?php echo e(request()->fullUrlWithQuery(['ui' => 'old'])); ?>" class="tq-btn tq-btn-ghost" title="Switch back to the previous tasks screen. Your tasks and filters stay the same.">
                    <i class="bi bi-clock-history"></i><span>Old UI</span>
                </a>
                <button type="button" class="tq-btn tq-btn-primary" data-bs-toggle="modal" data-bs-target="#taskCreateModal" title="New task (N)">
                    <i class="bi bi-plus-lg"></i> New task
                </button>
            </div>
        </div>

        
        <nav class="tq-scopes" aria-label="Which tasks">
            <?php $__currentLoopData = $scopes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(request()->fullUrlWithQuery(['type' => $key])); ?>" class="tq-scope <?php echo e($scope === $key ? 'active' : ''); ?>" <?php if($scope === $key): ?> aria-current="page" <?php endif; ?>>
                    <i class="bi <?php echo e($s['icon']); ?>"></i> <?php echo e($s['label']); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        
        <div class="tq-toolbar">
            <div class="tq-search">
                <i class="bi bi-search"></i>
                <input type="search" id="tqSearch" placeholder="Search tasks by title, ID or project" autocomplete="off" aria-label="Search tasks">
                <span class="tq-kbd">/</span>
                <div id="tqSearchResults" hidden></div>
            </div>

            <select class="tq-select <?php echo e(request('project_id') ? 'is-set' : ''); ?>" data-param="project_id" aria-label="Project">
                <option value="">All projects</option>
                <?php $__currentLoopData = $projects->sortBy('title'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($project->id); ?>" <?php if(request('project_id') == $project->id): echo 'selected'; endif; ?>><?php echo e($project->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <?php if($canFilterPeople && $scope !== 'assigned-to-me'): ?>
                <select class="tq-select <?php echo e(request('user_id') ? 'is-set' : ''); ?>" data-param="user_id" aria-label="Assignee">
                    <option value="">Any assignee</option>
                    <?php $__currentLoopData = $users->sortBy('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if(request('user_id') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>

            <?php if($scope !== 'task-created-by-me'): ?>
                <select class="tq-select <?php echo e(request('assigned_from') ? 'is-set' : ''); ?>" data-param="assigned_from" aria-label="Assigned by">
                    <option value="">Assigned by anyone</option>
                    <?php $__currentLoopData = $users->sortBy('name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php if(request('assigned_from') == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>

            <?php if($canFilterPeople): ?>
                <select class="tq-select <?php echo e(request('category') ? 'is-set' : ''); ?>" data-param="category" aria-label="Task type">
                    <option value="">All types</option>
                    <?php $__currentLoopData = $taskTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php if(request('category') === $t): echo 'selected'; endif; ?>><?php echo e(ucwords(strtolower($t))); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            <?php endif; ?>

            <select class="tq-select" id="tqPriority" aria-label="Priority">
                <option value="">Any priority</option>
                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p); ?>"><?php echo e($p); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="button" class="tq-btn tq-btn-outline" id="tqMoreToggle" aria-expanded="<?php echo e($moreFilterCount ? 'true' : 'false'); ?>" aria-controls="tqMore">
                <i class="bi bi-sliders2"></i> More filters
                <?php if($moreFilterCount): ?> <span class="tq-count"><?php echo e($moreFilterCount); ?></span> <?php endif; ?>
            </button>
        </div>

        <form class="tq-more" id="tqMore" <?php if(!$moreFilterCount): ?> hidden <?php endif; ?>>
            <div>
                <label>Created between</label>
                <div class="tq-range">
                    <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>" aria-label="Created from">
                    <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>" aria-label="Created to">
                </div>
            </div>
            <div>
                <label>Completed between</label>
                <div class="tq-range">
                    <input type="date" name="completed_date_start" value="<?php echo e(request('completed_date_start')); ?>" aria-label="Completed from">
                    <input type="date" name="completed_date_end" value="<?php echo e(request('completed_date_end')); ?>" aria-label="Completed to">
                </div>
            </div>
            <div>
                <label>Source</label>
                <label class="tq-switch form-switch m-0 p-0" style="text-transform:none;letter-spacing:0;">
                    <input class="form-check-input m-0" type="checkbox" name="all-automated" value="1" <?php if(request('all-automated')): echo 'checked'; endif; ?>>
                    Only automated tasks
                </label>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="tq-btn tq-btn-ghost" id="tqMoreCancel">Cancel</button>
                <button type="submit" class="tq-btn tq-btn-primary">Apply</button>
            </div>
        </form>

        <?php if($chips): ?>
            <div class="tq-chips" aria-label="Active filters">
                <?php $__currentLoopData = $chips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $params => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $remove = array_fill_keys(explode(',', $params), null); ?>
                    <span class="tq-chip"><?php echo e($label); ?><a href="<?php echo e(request()->fullUrlWithQuery($remove)); ?>" aria-label="Remove filter <?php echo e($label); ?>"><i class="bi bi-x"></i></a></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(request()->url()); ?>?type=<?php echo e($scope); ?>" class="tq-clear">Clear all</a>
            </div>
        <?php endif; ?>

        
        <div class="tq-quick" role="group" aria-label="Quick views">
            <button type="button" class="tq-qcard tq-q-overdue" data-quick="overdue">
                <span class="tq-qicon"><i class="bi bi-exclamation-circle"></i></span>
                <span><div class="tq-qnum" data-quick-count="overdue">0</div><div class="tq-qlabel">Overdue</div></span>
            </button>
            <button type="button" class="tq-qcard tq-q-today" data-quick="today">
                <span class="tq-qicon"><i class="bi bi-calendar-event"></i></span>
                <span><div class="tq-qnum" data-quick-count="today">0</div><div class="tq-qlabel">Due today</div></span>
            </button>
            <button type="button" class="tq-qcard tq-q-week" data-quick="week">
                <span class="tq-qicon"><i class="bi bi-calendar-week"></i></span>
                <span><div class="tq-qnum" data-quick-count="week">0</div><div class="tq-qlabel">Due in 7 days</div></span>
            </button>
            <button type="button" class="tq-qcard tq-q-nodue" data-quick="none">
                <span class="tq-qicon"><i class="bi bi-calendar-x"></i></span>
                <span><div class="tq-qnum" data-quick-count="none">0</div><div class="tq-qlabel">No due date</div></span>
            </button>
        </div>

        
        <div class="tq-listbar">
            <div class="tq-status-tabs" role="tablist" aria-label="Status">
                <button type="button" class="tq-stab" role="tab" data-status="open">Open <span class="n" data-status-count="open">0</span></button>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button" class="tq-stab" role="tab" data-status="<?php echo e($key); ?>"><?php echo e($s['label']); ?> <span class="n" data-status-count="<?php echo e($key); ?>">0</span></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <button type="button" class="tq-stab" role="tab" data-status="all">All <span class="n" data-status-count="all">0</span></button>
            </div>
            <div class="tq-listctl">
                <label class="visually-hidden" for="tqSort">Sort</label>
                <select class="tq-select" id="tqSort">
                    <option value="priority">Sort: Priority</option>
                    <option value="due">Sort: Due date</option>
                    <option value="newest">Sort: Newest first</option>
                    <option value="oldest">Sort: Oldest first</option>
                    <option value="title">Sort: Title A–Z</option>
                </select>
            </div>
        </div>

        
        <div class="tq-list" id="tqList">
            <div class="tq-thead" aria-hidden="true">
                <div>Task</div>
                <div><?php echo e($whoIsCreator ? 'Assigned by' : 'Assignee'); ?></div>
                <div>Due</div>
                <div>Priority</div>
                <div>Status</div>
                <div></div>
            </div>

            <div id="tqRows">
                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = $statuses[$task->status] ?? ['label' => $task->status, 'cls' => 'todo'];
                        $priority = in_array($task->priority, $priorities, true) ? $task->priority : 'Medium';
                        $person = $whoIsCreator ? ($task->createdBy->name ?? 'System') : ($task->assignedTo->name ?? 'Unassigned');
                        $due = $task->due_date ? \Carbon\Carbon::parse($task->due_date, $tz)->startOfDay() : null;
                        $isDone = $task->status === 'Completed';
                        $dueDiff = $due ? (int) $today->diffInDays($due, false) : null;
                        $bucket = $isDone ? 'done' : (!$due ? 'none' : ($dueDiff < 0 ? 'overdue' : ($dueDiff === 0 ? 'today' : ($dueDiff <= 7 ? 'week' : 'later'))));
                        if ($isDone) {
                            $dueLabel = $task->completed_date ? 'Done ' . \Carbon\Carbon::parse($task->completed_date)->format('d M') : 'Done';
                            $dueCls = 'done';
                        } elseif (!$due) {
                            $dueLabel = 'No due date'; $dueCls = 'none';
                        } elseif ($dueDiff < 0) {
                            $dueLabel = 'Overdue ' . abs($dueDiff) . 'd'; $dueCls = 'overdue';
                        } elseif ($dueDiff === 0) {
                            $dueLabel = 'Today'; $dueCls = 'today';
                        } elseif ($dueDiff === 1) {
                            $dueLabel = 'Tomorrow'; $dueCls = '';
                        } elseif ($dueDiff < 7) {
                            $dueLabel = $due->format('l'); $dueCls = '';
                        } else {
                            $dueLabel = $due->format($due->year === $today->year ? 'd M' : 'd M Y'); $dueCls = '';
                        }
                        $dueTitle = trim(($due ? 'Due ' . $due->format('d M Y') : 'No due date') . ($task->start_date ? ' · Starts ' . \Carbon\Carbon::parse($task->start_date)->setTimezone($tz)->format('d M Y, h:i A') : ''));
                        $projectTitle = $task->project->title ?? 'No project';
                    ?>
                    <div class="tq-row" role="button" tabindex="0"
                         data-id="<?php echo e($task->id); ?>"
                         data-status="<?php echo e($task->status); ?>"
                         data-priority="<?php echo e($priority); ?>"
                         data-bucket="<?php echo e($bucket); ?>"
                         data-due="<?php echo e($due ? $due->toDateString() : ''); ?>"
                         data-created="<?php echo e(optional($task->created_at)->timestamp ?? 0); ?>"
                         data-search="<?php echo e(mb_strtolower($task->title . ' #' . $task->id . ' ' . $task->id . ' ' . $projectTitle . ' ' . $task->type . ' ' . $person)); ?>"
                         aria-label="Open task <?php echo e($task->title); ?>">

                        <div class="tq-task">
                            <div class="tq-task-title"><?php echo e($task->title); ?></div>
                            <div class="tq-task-meta">
                                <span class="tq-id">#<?php echo e($task->id); ?></span>
                                <span class="sep">·</span>
                                <span class="tq-type"><?php echo e(ucwords(strtolower($task->type ?? 'Adhoc'))); ?></span>
                                <span class="sep">·</span>
                                <span class="tq-project" title="Project: <?php echo e($projectTitle); ?>">
                                    <i class="bi bi-folder2"></i><span><?php echo e($projectTitle); ?></span>
                                    <?php if($task->project): ?>
                                        <a href="<?php echo e(route('projects.show', $task->project->id)); ?>" target="_blank" rel="noopener" class="tq-project-link" title="Open project in a new tab" aria-label="Open project <?php echo e($projectTitle); ?> in a new tab" data-stop><i class="bi bi-box-arrow-up-right" style="font-size:.7rem;"></i></a>
                                    <?php endif; ?>
                                </span>
                                <?php if($task->is_automated): ?>
                                    <span class="tq-tag-auto" title="Created automatically by a workflow">Auto</span>
                                <?php endif; ?>
                                <?php if(($task->comments_count ?? 0) || ($task->attachments_count ?? 0)): ?>
                                    <span class="sep">·</span>
                                    <span class="tq-counts">
                                        <?php if($task->comments_count): ?> <span title="<?php echo e($task->comments_count); ?> comment(s)"><i class="bi bi-chat"></i><?php echo e($task->comments_count); ?></span> <?php endif; ?>
                                        <?php if($task->attachments_count): ?> <span title="<?php echo e($task->attachments_count); ?> attachment(s)"><i class="bi bi-paperclip"></i><?php echo e($task->attachments_count); ?></span> <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="tq-who">
                            <div class="tq-person" title="<?php echo e($whoIsCreator ? 'Assigned by' : 'Assignee'); ?>: <?php echo e($person); ?>">
                                <span class="tq-avatar" style="background:hsl(<?php echo e($avatarHue($person)); ?> 38% 42%);"><?php echo e($initials($person)); ?></span>
                                <span><?php echo e($person); ?></span>
                            </div>
                        </div>

                        <div class="tq-due-cell">
                            <span class="tq-due <?php echo e($dueCls); ?>" title="<?php echo e($dueTitle); ?>">
                                <?php if($dueCls === 'overdue'): ?><i class="bi bi-exclamation-circle-fill me-1"></i><?php endif; ?><?php echo e($dueLabel); ?>

                            </span>
                            <span class="tq-prio tq-prio-<?php echo e($priority); ?> tq-prio-inline" style="display:none;"><i class="bi bi-flag-fill"></i><?php echo e($priority); ?></span>
                        </div>

                        <div class="tq-prio-cell">
                            <span class="tq-prio tq-prio-<?php echo e($priority); ?>"><i class="bi bi-flag<?php echo e(in_array($priority, ['Urgent', 'High']) ? '-fill' : ''); ?>"></i><?php echo e($priority); ?></span>
                        </div>

                        <div class="tq-status-cell" data-stop>
                            <?php if($canChangeStatus): ?>
                                <div class="dropdown">
                                    <button type="button" class="tq-status tq-st-<?php echo e($status['cls']); ?>" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false" title="Change status">
                                        <span class="dot"></span><span class="label"><?php echo e($status['label']); ?></span><i class="bi bi-chevron-down" style="font-size:.65rem;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end tq-status-menu">
                                        <li><div class="dropdown-header">Move to</div></li>
                                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($key === 'Completed'): ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" data-complete><i class="bi bi-check2-circle"></i> Complete task…</a></li>
                                            <?php else: ?>
                                                <li><a class="dropdown-item <?php echo e($task->status === $key ? 'active' : ''); ?>" href="#" data-set-status="<?php echo e($key); ?>"><span class="tq-status tq-st-<?php echo e($s['cls']); ?>" style="padding:0;background:none;"><span class="dot"></span></span><?php echo e($s['label']); ?></a></li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <span class="tq-status tq-st-<?php echo e($status['cls']); ?>"><span class="dot"></span><?php echo e($status['label']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="tq-actions" data-stop>
                            <a href="<?php echo e(route('tasks.show', $task->id)); ?>" target="_blank" rel="noopener" class="tq-icon-btn" title="Open in a new tab" aria-label="Open task in a new tab"><i class="bi bi-box-arrow-up-right"></i></a>
                            <button type="button" class="tq-icon-btn tq-copy" data-copy="<?php echo e(url('tasks/' . $task->id)); ?>" title="Copy link" aria-label="Copy link to task"><i class="bi bi-link-45deg"></i></button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="tq-empty" id="tqEmpty" hidden>
                <i class="bi bi-check2-all"></i>
                <div class="tq-empty-title" id="tqEmptyTitle">Nothing here</div>
                <div id="tqEmptyText" style="font-size:.86rem;">No tasks match these filters.</div>
                <button type="button" class="tq-btn tq-btn-outline mt-3" id="tqEmptyReset" hidden>Clear search and quick filters</button>
            </div>

            <div class="tq-more-rows" id="tqMoreRows" hidden>
                <button type="button" class="tq-btn tq-btn-outline" id="tqShowMore">Show more</button>
            </div>
        </div>

        <div class="tq-foot">
            <span id="tqShowing"></span>
            <span class="d-none d-md-inline">Shortcuts: <span class="tq-kbd">/</span> search · <span class="tq-kbd">N</span> new task · <span class="tq-kbd">↑</span><span class="tq-kbd">↓</span> move · <span class="tq-kbd">Enter</span> open · <span class="tq-kbd">Esc</span> close</span>
        </div>
    </div>

    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="tqDrawerTitle" style="width:min(1020px,100vw);">
        <div class="offcanvas-header">
            <div class="tq-drawer-title" id="tqDrawerTitle">Task</div>
            <a href="#" target="_blank" rel="noopener" class="tq-icon-btn" id="tqDrawerOpen" title="Open in a new tab" aria-label="Open in a new tab"><i class="bi bi-box-arrow-up-right"></i></a>
            <button type="button" class="tq-icon-btn" id="tqDrawerCopy" title="Copy link" aria-label="Copy link"><i class="bi bi-link-45deg"></i></button>
            <button type="button" class="btn-close ms-1" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="taskDetails" style="background:#fafbf9;"></div>
    </div>

    
    <form action="<?php echo e(route('tasks.store')); ?>" id="task-create-form" method="POST" enctype="multipart/form-data">
        <div class="modal fade tk-modal" id="taskCreateModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border:0;border-radius:16px;">
                    <div class="modal-header" style="padding:1.1rem 1.25rem;">
                        <div class="modal-title fw-bold" style="font-size:1.05rem;">New task</div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="padding:1.25rem;">
                        <?php echo csrf_field(); ?>
                        <?php echo $__env->make('tasks.create', ['projects' => $projects], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                    <div class="modal-footer" style="padding:.85rem 1.25rem;">
                        <button type="button" class="tq-btn tq-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="tq-btn tq-btn-primary task-submit"><i class="bi bi-check2"></i> Create task</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <script type="text/javascript">
        var uploadedDocumentMap = {};
        Dropzone.autoDiscover = false;
        var myDropzone = new Dropzone("#taskAttachmentsDropzone", {
            url: "<?php echo e(route('tasks.store')); ?>",
            autoProcessQueue: false, uploadMultiple: true,
            paramName: "attachments", maxFiles: 10, maxFilesize: 5,
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.txt",
            addRemoveLinks: true,
            headers: { "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
            init: function() {
                var dz = this;
                dz.on("sending", function(file, xhr, formData) {
                    var fd = new FormData(document.querySelector("#task-create-form"));
                    fd.forEach((v, k) => formData.append(k, v));
                    formData.append("attachments[]", file);
                });
                dz.on("success", function(f, r) { uploadedDocumentMap[f.name] = r.name; });
                dz.on("removedfile", function(f) { delete uploadedDocumentMap[f.name]; });
            }
        });

        $('.task-submit').click(function(e) {
            e.preventDefault();
            if (!$('#department_id').val()) { toastr.error('Please select Department!'); $('#department_id').focus(); return; }
            if (!$('#sub_type').val()) { toastr.error('Please select Task Type!'); $('#sub_type').focus(); return; }
            $(this).attr('disabled', true).text('Creating…');
            setTimeout(() => $(this).attr('disabled', false).html('<i class="bi bi-check2"></i> Create task'), 3000);
            var fd = new FormData($("#task-create-form")[0]);
            myDropzone.files.forEach(f => fd.append("attachments[]", f));
            $.ajax({
                url: "<?php echo e(route('tasks.store')); ?>", type: "POST", data: fd,
                processData: false, contentType: false,
                success: function() {
                    toastr.success('Task created successfully');
                    setTimeout(() => window.location.reload(), 1200);
                },
                error: function(xhr) { console.log(xhr.responseText); toastr.error('Error creating task'); }
            });
        });
    </script>

    <script>
        /* Task detail drawer — loads the same detail view as the classic screen. */
        function loadTaskDetails(taskId) {
            $("input[name='task_id']").val(taskId);
            $('#taskDetails').html('<div class="d-flex justify-content-center align-items-center" style="height:200px;"><div class="spinner-border" style="color:#4f7a06;" role="status"></div></div>');
            $.ajax({
                url: '<?php echo e(route('tasks.show', ':id')); ?>'.replace(':id', taskId),
                method: 'GET', data: { is_ajax: true },
                success: function(r) {
                    $('#taskDetails').html(r);
                    if (typeof loadTaskAttachments === 'function') loadTaskAttachments();
                    if (typeof loadComments === 'function') loadComments();
                    if ($('.editor').length) $('.editor').richText();
                },
                error: function() {
                    $('#taskDetails').html('<p class="text-danger p-3" style="font-size:.85rem;">Task not found, deleted, or you do not have access.</p>');
                }
            });
        }

        (function () {
            const root = document.getElementById('tq');
            const rowsWrap = document.getElementById('tqRows');
            const rows = Array.from(rowsWrap.querySelectorAll('.tq-row'));
            const search = document.getElementById('tqSearch');
            const prioritySel = document.getElementById('tqPriority');
            const sortSel = document.getElementById('tqSort');
            const PAGE = 60;
            const OPEN = ['Todo', 'In Progress', 'Review'];
            const PRIO = { Urgent: 0, High: 1, Medium: 2, Low: 3 };
            const STORE = 'tq-state-v1';
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const statusMeta = <?php echo json_encode(collect($statuses)->map(fn ($s) => $s), 15, 512) ?>;
            const notify = (type, msg) => { if (window.toastr) toastr[type](msg); };

            // Sort is remembered on this device; the status tab only for this browser tab.
            let saved = {}, savedTab = null;
            try { saved = JSON.parse(localStorage.getItem(STORE)) || {}; savedTab = sessionStorage.getItem(STORE + '-tab'); } catch (e) {}
            const state = {
                status: savedTab || 'open',
                sort: saved.sort || 'priority',
                quick: null,
                q: '',
                priority: '',
                limit: PAGE,
            };
            sortSel.value = state.sort;

            function persist() {
                try {
                    localStorage.setItem(STORE, JSON.stringify({ sort: state.sort }));
                    sessionStorage.setItem(STORE + '-tab', state.status);
                } catch (e) {}
            }

            function inStatus(row, status) {
                const s = row.dataset.status;
                if (status === 'all') return true;
                if (status === 'open') return OPEN.includes(s);
                return s === status;
            }

            function inQuick(row, quick) {
                if (!quick) return true;
                const b = row.dataset.bucket;
                if (quick === 'week') return b === 'today' || b === 'week';
                return b === quick;
            }

            function matchesBase(row) {
                if (state.q && !row.dataset.search.includes(state.q)) return false;
                if (state.priority && row.dataset.priority !== state.priority) return false;
                return true;
            }

            function sortRows(list) {
                const by = {
                    priority: (a, b) => (PRIO[a.dataset.priority] - PRIO[b.dataset.priority]) || dueCmp(a, b),
                    due: (a, b) => dueCmp(a, b) || (PRIO[a.dataset.priority] - PRIO[b.dataset.priority]),
                    newest: (a, b) => b.dataset.created - a.dataset.created,
                    oldest: (a, b) => a.dataset.created - b.dataset.created,
                    title: (a, b) => a.querySelector('.tq-task-title').textContent.trim().localeCompare(b.querySelector('.tq-task-title').textContent.trim()),
                }[state.sort];
                return list.sort(by);
            }

            function dueCmp(a, b) {
                const x = a.dataset.due || '9999-12-31', y = b.dataset.due || '9999-12-31';
                return x < y ? -1 : x > y ? 1 : 0;
            }

            function render() {
                const base = rows.filter(matchesBase);

                // Counts follow search/priority/quick filters so the numbers always match the list.
                const forStatus = base.filter(r => inQuick(r, state.quick));
                const counts = { open: 0, all: forStatus.length };
                Object.keys(statusMeta).forEach(k => counts[k] = 0);
                forStatus.forEach(r => {
                    counts[r.dataset.status] = (counts[r.dataset.status] || 0) + 1;
                    if (OPEN.includes(r.dataset.status)) counts.open++;
                });
                root.querySelectorAll('[data-status-count]').forEach(el => el.textContent = counts[el.dataset.statusCount] || 0);

                // Quick-view counts are for open tasks in the current search.
                const openBase = base.filter(r => OPEN.includes(r.dataset.status));
                ['overdue', 'today', 'week', 'none'].forEach(q => {
                    root.querySelector('[data-quick-count="' + q + '"]').textContent = openBase.filter(r => inQuick(r, q)).length;
                });

                root.querySelectorAll('.tq-stab').forEach(b => {
                    const on = b.dataset.status === state.status;
                    b.classList.toggle('active', on);
                    b.setAttribute('aria-selected', on);
                    if (on) b.parentElement.scrollLeft = b.offsetLeft - b.parentElement.offsetLeft - 8;
                });
                root.querySelectorAll('.tq-qcard').forEach(b => {
                    const on = b.dataset.quick === state.quick;
                    b.classList.toggle('active', on);
                    b.setAttribute('aria-pressed', on);
                });

                const visible = sortRows(forStatus.filter(r => inStatus(r, state.status)));
                const shown = visible.slice(0, state.limit);
                const frag = document.createDocumentFragment();
                rows.forEach(r => r.hidden = true);
                shown.forEach(r => { r.hidden = false; frag.appendChild(r); });
                rowsWrap.prepend(frag);

                const empty = document.getElementById('tqEmpty');
                empty.hidden = visible.length > 0;
                if (!visible.length) {
                    const filtered = state.q || state.priority || state.quick;
                    document.getElementById('tqEmptyTitle').textContent = filtered ? 'No matching tasks' : (state.status === 'open' ? 'All caught up' : 'Nothing here');
                    document.getElementById('tqEmptyText').textContent = filtered
                        ? 'Try a different search, or clear the quick filters.'
                        : (state.status === 'open' ? 'You have no open tasks in this list.' : 'No tasks with this status.');
                    document.getElementById('tqEmptyReset').hidden = !filtered;
                }
                document.getElementById('tqMoreRows').hidden = visible.length <= state.limit;
                document.getElementById('tqShowMore').textContent = 'Show ' + Math.min(PAGE, visible.length - state.limit) + ' more';
                document.getElementById('tqShowing').textContent = visible.length
                    ? 'Showing ' + shown.length + ' of ' + visible.length + ' task' + (visible.length === 1 ? '' : 's')
                    : '';
            }

            // ── Status tabs, quick views, sort, search, priority
            root.querySelectorAll('.tq-stab').forEach(b => b.addEventListener('click', () => { state.status = b.dataset.status; state.limit = PAGE; persist(); render(); }));
            root.querySelectorAll('.tq-qcard').forEach(b => b.addEventListener('click', () => {
                state.quick = state.quick === b.dataset.quick ? null : b.dataset.quick;
                if (state.quick && !['open', 'all'].includes(state.status)) state.status = 'open';
                state.limit = PAGE; render();
            }));
            sortSel.addEventListener('change', () => { state.sort = sortSel.value; persist(); render(); });
            prioritySel.addEventListener('change', () => { state.priority = prioritySel.value; prioritySel.classList.toggle('is-set', !!state.priority); state.limit = PAGE; render(); });
            let t;
            search.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => { state.q = search.value.trim().toLowerCase(); state.limit = PAGE; render(); globalSearch(search.value.trim()); }, 120);
            });
            document.getElementById('tqShowMore').addEventListener('click', () => { state.limit += PAGE; render(); });
            document.getElementById('tqEmptyReset').addEventListener('click', () => {
                search.value = ''; prioritySel.value = ''; prioritySel.classList.remove('is-set');
                Object.assign(state, { q: '', priority: '', quick: null }); render();
            });

            // ── Server-side filters (same URL parameters as the classic screen)
            root.querySelectorAll('select[data-param]').forEach(sel => sel.addEventListener('change', () => {
                const url = new URL(window.location.href);
                sel.value ? url.searchParams.set(sel.dataset.param, sel.value) : url.searchParams.delete(sel.dataset.param);
                window.location.href = url.toString();
            }));
            const more = document.getElementById('tqMore');
            const moreToggle = document.getElementById('tqMoreToggle');
            moreToggle.addEventListener('click', () => { more.hidden = !more.hidden; moreToggle.setAttribute('aria-expanded', !more.hidden); });
            document.getElementById('tqMoreCancel').addEventListener('click', () => { more.hidden = true; moreToggle.setAttribute('aria-expanded', 'false'); });
            more.addEventListener('submit', e => {
                e.preventDefault();
                const url = new URL(window.location.href);
                ['start_date', 'end_date', 'completed_date_start', 'completed_date_end'].forEach(k => {
                    const v = more.elements[k].value;
                    v ? url.searchParams.set(k, v) : url.searchParams.delete(k);
                });
                more.elements['all-automated'].checked ? url.searchParams.set('all-automated', 1) : url.searchParams.delete('all-automated');
                window.location.href = url.toString();
            });

            // ── Search across all tasks (not just this list)
            const results = document.getElementById('tqSearchResults');
            let req;
            function globalSearch(q) {
                if (q.length < 2) { results.hidden = true; return; }
                if (req) req.abort();
                req = $.ajax({
                    url: "<?php echo e(route('tasks.search.index')); ?>", method: 'GET', data: { query: q },
                    success: function (r) {
                        if (!r.length) { results.hidden = true; return; }
                        const esc = s => $('<div>').text(s == null ? '' : s).html();
                        results.innerHTML = '<div class="tq-sr-head">All matching tasks</div>' + r.map(x =>
                            `<a href="#" data-open-task="${x.id}">
                                <span class="tq-sr-title">${esc(x.title)}</span>
                                <span class="tq-sr-meta">#${x.id} · <i class="bi bi-folder2"></i> ${esc(x.project_title)} · ${esc(x.status)}</span>
                            </a>`).join('');
                        results.hidden = false;
                    }
                });
            }
            results.addEventListener('click', e => {
                const a = e.target.closest('[data-open-task]');
                if (!a) return;
                e.preventDefault();
                results.hidden = true;
                openTask(a.dataset.openTask, a.querySelector('.tq-sr-title').textContent);
            });
            document.addEventListener('click', e => { if (!e.target.closest('.tq-search')) results.hidden = true; });

            // ── Opening a task
            const drawerEl = document.getElementById('offcanvasRight');
            let currentRow = null;

            function openTask(id, title) {
                rows.forEach(r => r.classList.toggle('is-open', r.dataset.id == id));
                document.getElementById('tqDrawerTitle').textContent = title ? '#' + id + ' · ' + title : 'Task #' + id;
                document.getElementById('tqDrawerOpen').href = "<?php echo e(url('tasks')); ?>/" + id;
                document.getElementById('tqDrawerCopy').dataset.copy = "<?php echo e(url('tasks')); ?>/" + id;
                loadTaskDetails(id);
                bootstrap.Offcanvas.getOrCreateInstance(drawerEl).show();
                const url = new URL(window.location.href);
                url.searchParams.set('task', id);
                history.replaceState(null, '', url);
            }

            drawerEl.addEventListener('hidden.bs.offcanvas', () => {
                rows.forEach(r => r.classList.remove('is-open'));
                const url = new URL(window.location.href);
                url.searchParams.delete('task');
                history.replaceState(null, '', url);
                if (currentRow && !currentRow.hidden) currentRow.focus({ preventScroll: true });
            });

            rowsWrap.addEventListener('click', e => {
                if (e.target.closest('[data-stop]')) return;
                const row = e.target.closest('.tq-row');
                if (!row) return;
                currentRow = row;
                openTask(row.dataset.id, row.querySelector('.tq-task-title').textContent.trim());
            });

            // ── Copy link
            document.addEventListener('click', e => {
                const b = e.target.closest('[data-copy]');
                if (!b) return;
                navigator.clipboard.writeText(b.dataset.copy).then(() => notify('success', 'Task link copied')).catch(() => notify('error', 'Could not copy the link'));
            });

            // ── Inline status change (same endpoint as the detail view)
            rowsWrap.addEventListener('click', e => {
                const item = e.target.closest('[data-set-status], [data-complete]');
                if (!item) return;
                e.preventDefault();
                const row = item.closest('.tq-row');
                if (item.hasAttribute('data-complete')) {
                    notify('info', 'Fill in the completion details to complete the task.');
                    currentRow = row;
                    openTask(row.dataset.id, row.querySelector('.tq-task-title').textContent.trim());
                    return;
                }
                const next = item.dataset.setStatus;
                if (next === row.dataset.status) return;
                const btn = row.querySelector('button.tq-status');
                btn.disabled = true;
                $.ajax({
                    url: "<?php echo e(route('tasks.updateStatus')); ?>", type: 'POST',
                    data: { _token: csrf, task_id: row.dataset.id, status: next },
                    success: function (r) {
                        const meta = statusMeta[next];
                        row.dataset.status = next;
                        btn.className = 'tq-status tq-st-' + meta.cls;
                        btn.querySelector('.label').textContent = meta.label;
                        row.querySelectorAll('[data-set-status]').forEach(a => a.classList.toggle('active', a.dataset.setStatus === next));
                        render();
                        notify('success', (r && r.message) || 'Status updated');
                    },
                    error: function () { notify('error', "Task status can't change."); },
                    complete: function () { btn.disabled = false; }
                });
            });

            // ── Keyboard
            function visibleRows() { return rows.filter(r => !r.hidden); }
            document.addEventListener('keydown', e => {
                const typing = /INPUT|TEXTAREA|SELECT/.test(e.target.tagName) || e.target.isContentEditable;
                const overlay = document.querySelector('.modal.show, .offcanvas.show');
                if (e.key === 'Escape' && document.activeElement === search) { search.value = ''; search.dispatchEvent(new Event('input')); search.blur(); return; }
                if (typing || overlay || e.metaKey || e.ctrlKey || e.altKey) return;
                if (e.key === '/') { e.preventDefault(); search.focus(); }
                else if (e.key === 'n' || e.key === 'N') { e.preventDefault(); bootstrap.Modal.getOrCreateInstance(document.getElementById('taskCreateModal')).show(); }
                else if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    const list = visibleRows();
                    if (!list.length) return;
                    e.preventDefault();
                    let i = list.indexOf(document.activeElement);
                    i = e.key === 'ArrowDown' ? Math.min(i + 1, list.length - 1) : Math.max(i - 1, 0);
                    list[i].focus();
                }
            });
            rowsWrap.addEventListener('keydown', e => {
                const row = e.target.closest('.tq-row');
                if (row && e.target === row && (e.key === 'Enter' || e.key === ' ')) {
                    e.preventDefault();
                    currentRow = row;
                    openTask(row.dataset.id, row.querySelector('.tq-task-title').textContent.trim());
                }
            });

            // ── Subtitle with open count for this scope
            const openCount = rows.filter(r => OPEN.includes(r.dataset.status)).length;
            document.getElementById('tqSubtitle').textContent = <?php echo json_encode($scopes[$scope]['label'], 15, 512) ?> + ' · ' + openCount + ' open';

            render();

            // ── Open a task from the URL (?task=ID, or /tasks/ID like the classic screen)
            const params = new URLSearchParams(window.location.search);
            const segments = window.location.pathname.split('/');
            const initial = params.get('task') || (segments[2] && /^\d+$/.test(segments[2]) ? segments[2] : null);
            if (initial) {
                const row = rows.find(r => r.dataset.id == initial);
                openTask(initial, row ? row.querySelector('.tq-task-title').textContent.trim() : '');
            }
        })();
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
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/tenxcrm/resources/views/tasks/board-new.blade.php ENDPATH**/ ?>