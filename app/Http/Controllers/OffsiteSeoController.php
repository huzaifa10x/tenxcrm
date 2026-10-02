<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OffsiteSeoController extends Controller
{
    public const ROLE = 'OFFSITE';
    public const OPEN_STATUSES = ['Todo', 'In Progress', 'Review'];
    public const RANGES = [
        'today'       => 'Today',
        'last_7_days' => 'Last 7 Days',
        'this_month'  => 'This Month',
        'last_month'  => 'Last Month',
        'custom'      => 'Custom Range',
    ];

    public function index(Request $request)
    {
        $this->authorizeManager();

        return view('admin.offsite-seo.index', $this->build($request));
    }

    public function show(Request $request, User $user)
    {
        $this->authorizeManager();

        if (!$user->hasRole(self::ROLE)) {
            abort(403, 'User is not an Offsite SEO employee');
        }

        return view('admin.offsite-seo.index', $this->build($request, $user));
    }

    /* ───────────────────────── Shared helpers ───────────────────────── */

    /** Only users holding the OFFSITE role. */
    public static function offsiteUsers(): Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->where('name', self::ROLE);
        })->orderBy('name')->get(['id', 'name', 'email']);
    }

    /** @return array{0: Carbon, 1: Carbon, 2: string} */
    public static function resolveRange(Request $request): array
    {
        $now = Carbon::now();
        $range = $request->input('range', 'this_month');

        if ($range === 'today') {
            return [$now->copy()->startOfDay(), $now->copy()->endOfDay(), $range];
        }
        if ($range === 'last_7_days') {
            return [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay(), $range];
        }
        if ($range === 'last_month') {
            $start = $now->copy()->startOfMonth()->subMonth();
            return [$start, $start->copy()->endOfMonth(), $range];
        }
        if ($range === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            try {
                $start = Carbon::parse($request->input('start_date'))->startOfDay();
                $end = Carbon::parse($request->input('end_date'))->endOfDay();
                if ($start->gt($end)) {
                    [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
                }
                return [$start, $end, $range];
            } catch (\Throwable $e) {
                // invalid dates: fall back to this month
            }
        }

        return [$now->copy()->startOfMonth(), $now->copy()->endOfDay(), 'this_month'];
    }

    /**
     * Links built in the period by the given employees.
     * Source 1: project_keywords (type = closing) - credited to created_by, else the task's assignee.
     * Source 2: partner_links - credited to the task's assignee.
     */
    public static function linkRows(array $userIds, Carbon $from, Carbon $to): Collection
    {
        if (empty($userIds)) {
            return collect();
        }

        $range = [$from->toDateTimeString(), $to->toDateTimeString()];

        $links = DB::table('project_keywords as l')
            ->join('tasks as t', 't.id', '=', 'l.task_id')
            ->leftJoin('offsite_tasks as o', 'o.task_id', '=', 't.id')
            ->leftJoin('projects as p', 'p.id', '=', 'l.project_id')
            ->where('l.type', 'closing')
            ->where('t.title', '!=', 'Add KW Mapping') // keyword mapping rows, not links
            ->whereBetween('l.created_at', $range)
            ->whereIn(DB::raw('COALESCE(l.created_by, t.assigned_to)'), $userIds)
            ->select(
                'l.id',
                'l.task_id',
                'l.project_id',
                'p.title as project',
                'l.keyword',
                'l.url',
                'l.destination_url',
                'l.rel',
                'l.dr',
                'l.link_status',
                'l.created_at',
                DB::raw('COALESCE(l.created_by, t.assigned_to) as user_id'),
                DB::raw("COALESCE(o.sub_type, t.type) as link_type")
            )
            ->get();

        $partner = DB::table('partner_links as l')
            ->join('tasks as t', 't.id', '=', 'l.task_id')
            ->leftJoin('projects as p', 'p.id', '=', 'l.project_id')
            ->whereBetween('l.created_at', $range)
            ->whereIn('t.assigned_to', $userIds)
            ->select(
                'l.id',
                'l.task_id',
                'l.project_id',
                'p.title as project',
                'l.keyword',
                'l.backlink as url',
                'l.url as destination_url',
                DB::raw('NULL as rel'),
                DB::raw('NULL as dr'),
                DB::raw('NULL as link_status'),
                'l.created_at',
                't.assigned_to as user_id',
                DB::raw("'PARTNER LINKS' as link_type")
            )
            ->get();

        // Referring domain + duplicate flag (same link URL logged again for the same project in the period)
        $seen = [];

        return $links->concat($partner)->sortBy('created_at')->values()->map(function ($link) use (&$seen) {
            $host = parse_url(trim((string) $link->url), PHP_URL_HOST);
            $link->domain = $host ? preg_replace('/^www\./i', '', strtolower($host)) : null;
            $key = strtolower(rtrim(trim((string) $link->url), '/')) . '|' . $link->project_id;
            $link->is_duplicate = isset($seen[$key]);
            $seen[$key] = true;

            return $link;
        });
    }

    /* ───────────────────────── Dashboard data ───────────────────────── */

    private function build(Request $request, ?User $member = null): array
    {
        [$from, $to, $range] = self::resolveRange($request);

        $team = self::offsiteUsers();
        $names = $team->pluck('name', 'id');

        if ($member) {
            $userIds = [$member->id];
        } elseif ($request->filled('user_id') && $names->has((int) $request->input('user_id'))) {
            $userIds = [(int) $request->input('user_id')];
        } else {
            $userIds = $team->pluck('id')->all();
        }

        $columns = ['id', 'title', 'project_id', 'assigned_to', 'status', 'due_date', 'completed_date', 'created_at'];

        // Tasks assigned in the period
        $assigned = Task::visibleTasks()->whereIn('assigned_to', $userIds)
            ->whereBetween('created_at', [$from, $to])
            ->get($columns);

        // Tasks completed in the period
        $completed = Task::visibleTasks()->whereIn('assigned_to', $userIds)
            ->where('status', 'Completed')
            ->whereBetween('completed_date', [$from, $to])
            ->get($columns);

        // Tasks assigned up to the end of the period that are still open
        $open = Task::visibleTasks()->with('project:id,title')->whereIn('assigned_to', $userIds)
            ->whereIn('status', self::OPEN_STATUSES)
            ->where('created_at', '<=', $to)
            ->get($columns);

        $today = Carbon::today();
        $overdue = $open->filter(function ($task) use ($today) {
            return $task->due_date && Carbon::parse($task->due_date)->startOfDay()->lt($today);
        })->map(function ($task) use ($today, $names) {
            $task->days_overdue = (int) floor(Carbon::parse($task->due_date)->startOfDay()->diffInDays($today, false));
            $task->assignee_name = $names[$task->assigned_to] ?? '—';
            return $task;
        })->sortByDesc('days_overdue')->values();

        $links = self::linkRows($userIds, $from, $to);
        $workDays = $this->workingDays($from, $to);

        $stats = $this->summarize($assigned, $completed, $open, $overdue, $links, $workDays);

        // Per employee (team page only)
        $employees = collect();
        if (!$member) {
            $a = $assigned->groupBy('assigned_to');
            $c = $completed->groupBy('assigned_to');
            $o = $open->groupBy('assigned_to');
            $d = $overdue->groupBy('assigned_to');
            $l = $links->groupBy('user_id');

            $employees = $team->whereIn('id', $userIds)->map(function ($user) use ($a, $c, $o, $d, $l, $workDays) {
                return ['id' => $user->id, 'name' => $user->name, 'email' => $user->email] + $this->summarize(
                    $a->get($user->id, collect()),
                    $c->get($user->id, collect()),
                    $o->get($user->id, collect()),
                    $d->get($user->id, collect()),
                    $l->get($user->id, collect()),
                    $workDays
                );
            })->sortByDesc('links')->values();
        }

        // Daily / monthly productivity
        $group = $request->input('group');
        if (!in_array($group, ['day', 'month'])) {
            $group = $from->diffInDays($to) > 62 ? 'month' : 'day';
        }

        $rated = $links->whereNotNull('dr');
        $quality = [
            'domains'    => $stats['domains'],
            'duplicates' => $stats['duplicates'],
            'dofollow'   => $links->where('rel', 'dofollow')->count(),
            'nofollow'   => $links->where('rel', 'nofollow')->count(),
            'avg_dr'     => $rated->count() ? round($rated->avg('dr'), 1) : null,
            'dr_count'   => $rated->count(),
            'live'       => $links->where('link_status', 'live')->count(),
            'lost'       => $links->where('link_status', 'lost')->count(),
        ];

        return [
            'member'      => $member,
            'team'        => $team,
            'names'       => $names,
            'from'        => $from,
            'to'          => $to,
            'range'       => $range,
            'ranges'      => self::RANGES,
            'group'       => $group,
            'selectedUser' => (!$member && count($userIds) === 1 && $request->filled('user_id')) ? $userIds[0] : null,
            'query'       => array_filter([
                'range'      => $range,
                'start_date' => $range === 'custom' ? $from->toDateString() : null,
                'end_date'   => $range === 'custom' ? $to->toDateString() : null,
            ]),
            'workDays'    => $workDays,
            'stats'       => $stats,
            'quality'     => $quality,
            'employees'   => $employees,
            'series'      => $this->series($completed, $links, $from, $to, $group),
            'overdue'     => $overdue->take(10),
            'recentLinks' => $member ? $links->sortByDesc('created_at')->take(15)->values() : collect(),
        ];
    }

    private function summarize(Collection $assigned, Collection $completed, Collection $open, Collection $overdue, Collection $links, int $workDays): array
    {
        $manual = $links->where('link_type', 'MANUAL LINKS')->count();

        return [
            'total'         => $assigned->count(),
            'completed'     => $completed->count(),
            'pending'       => $open->count(),
            'overdue'       => $overdue->count(),
            'links'         => $links->count(),
            'manual'        => $manual,
            'links_per_day' => $workDays > 0 ? round($links->count() / $workDays, 1) : 0,
            'domains'       => $links->pluck('domain')->filter()->unique()->count(),
            'duplicates'    => $links->where('is_duplicate', true)->count(),
        ];
    }

    private function series(Collection $completed, Collection $links, Carbon $from, Carbon $to, string $group): array
    {
        $format = $group === 'month' ? 'Y-m' : 'Y-m-d';
        $rows = [];
        $end = $to->copy()->min(Carbon::now()->endOfDay());

        for ($day = $from->copy()->startOfDay(); $day->lte($end); $day->addDay()) {
            $rows[$day->format($format)] ??= ['completed' => 0, 'links' => 0, 'manual' => 0];
        }

        foreach ($completed as $task) {
            $key = Carbon::parse($task->completed_date)->format($format);
            if (isset($rows[$key])) {
                $rows[$key]['completed']++;
            }
        }

        foreach ($links as $link) {
            $key = Carbon::parse($link->created_at)->format($format);
            if (isset($rows[$key])) {
                $rows[$key]['links']++;
                if ($link->link_type === 'MANUAL LINKS') {
                    $rows[$key]['manual']++;
                }
            }
        }

        return $rows;
    }

    /** Mon-Fri days of the period that have already started. */
    private function workingDays(Carbon $from, Carbon $to): int
    {
        $end = $to->copy()->min(Carbon::now()->endOfDay());
        $count = 0;

        for ($day = $from->copy()->startOfDay(); $day->lte($end); $day->addDay()) {
            if (!$day->isWeekend()) {
                $count++;
            }
        }

        return $count;
    }

    private function authorizeManager(): void
    {
        $user = auth()->user();

        if (!$user->hasRole('ADMIN') && !$user->hasRole('SEO ADMIN')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
