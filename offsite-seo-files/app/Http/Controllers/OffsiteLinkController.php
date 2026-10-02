<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectKeyword;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Data entry for link-building records.
 * Records are the same project_keywords (type = closing) rows that the task page
 * "Add KW/URLs" form creates - this screen only adds a place to review and maintain them.
 *
 * ADMIN / SEO ADMIN: manage every Offsite SEO employee's links.
 * OFFSITE: manage their own links only.
 */
class OffsiteLinkController extends Controller
{
    public const STATUSES = ['live' => 'Live', 'pending' => 'Pending', 'lost' => 'Lost'];
    public const RELS = ['dofollow' => 'Dofollow', 'nofollow' => 'Nofollow'];

    public function index(Request $request)
    {
        $isManager = $this->isManager();
        [$from, $to, $range] = OffsiteSeoController::resolveRange($request);

        $team = OffsiteSeoController::offsiteUsers();
        $names = $team->pluck('name', 'id');

        if (!$isManager) {
            $userIds = [auth()->id()];
        } elseif ($request->filled('user_id') && $names->has((int) $request->input('user_id'))) {
            $userIds = [(int) $request->input('user_id')];
        } else {
            $userIds = $team->pluck('id')->all();
        }

        $links = ProjectKeyword::query()
            ->select('project_keywords.*', DB::raw('COALESCE(project_keywords.created_by, tasks.assigned_to) as owner_id'))
            ->join('tasks', 'tasks.id', '=', 'project_keywords.task_id')
            ->where('project_keywords.type', 'closing')
            ->where('tasks.title', '!=', 'Add KW Mapping') // keyword mapping rows, not links
            ->whereIn(DB::raw('COALESCE(project_keywords.created_by, tasks.assigned_to)'), $userIds ?: [0])
            ->whereBetween('project_keywords.created_at', [$from, $to])
            ->when($request->filled('project_id'), function ($query) use ($request) {
                $query->where('project_keywords.project_id', $request->input('project_id'));
            })
            ->when($request->filled('link_status'), function ($query) use ($request) {
                $request->input('link_status') === 'none'
                    ? $query->whereNull('project_keywords.link_status')
                    : $query->where('project_keywords.link_status', $request->input('link_status'));
            })
            ->with(['project:id,title', 'task:id,title,type', 'task.offsiteTask'])
            ->orderByDesc('project_keywords.created_at')
            ->paginate(50)
            ->withQueryString();

        $projects = Project::whereIn('id', ProjectKeyword::where('type', 'closing')->distinct()->pluck('project_id'))
            ->orderBy('title')->get(['id', 'title']);

        return view('admin.offsite-seo.links', [
            'links'     => $links,
            'team'      => $team,
            'names'     => $names,
            'projects'  => $projects,
            'isManager' => $isManager,
            'from'      => $from,
            'to'        => $to,
            'range'     => $range,
            'ranges'    => OffsiteSeoController::RANGES,
            'statuses'  => self::STATUSES,
            'rels'      => self::RELS,
        ]);
    }

    /** Tasks an employee can log a link against (open, or completed in the last 60 days). */
    public function tasks(Request $request)
    {
        $userId = $this->isManager() ? (int) $request->input('user_id') : auth()->id();

        $tasks = Task::with(['project:id,title', 'offsiteTask'])
            ->where('assigned_to', $userId)
            ->whereNotNull('project_id')
            ->where(function ($query) {
                $query->whereIn('status', OffsiteSeoController::OPEN_STATUSES)
                    ->orWhere(function ($q) {
                        $q->where('status', 'Completed')->where('completed_date', '>=', Carbon::now()->subDays(60));
                    });
            })
            ->orderByDesc('id')
            ->limit(300)
            ->get(['id', 'title', 'project_id', 'type', 'status']);

        return response()->json($tasks->map(function ($task) {
            return [
                'id'    => $task->id,
                'label' => '#' . $task->id . ' ' . $task->title
                    . ' — ' . ($task->project->title ?? 'No project')
                    . ' (' . ($task->offsiteTask->sub_type ?? $task->type) . ', ' . $task->status . ')',
            ];
        }));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        [$ownerId, $task] = $this->resolveOwnerAndTask($request, $data);

        $link = new ProjectKeyword($this->attributes($data, $task, $ownerId));
        $this->applyDate($link, $data['link_date'] ?? null);
        $link->save();

        return redirect()->back()->with('success', 'Link added successfully!');
    }

    public function update(Request $request, ProjectKeyword $link)
    {
        $this->authorizeLink($link);
        $data = $this->validated($request);
        [$ownerId, $task] = $this->resolveOwnerAndTask($request, $data);

        $link->fill($this->attributes($data, $task, $ownerId));
        $this->applyDate($link, $data['link_date'] ?? null);
        $link->save();

        return redirect()->back()->with('success', 'Link updated successfully!');
    }

    public function updateStatus(Request $request, ProjectKeyword $link)
    {
        $this->authorizeLink($link);

        $request->validate(['link_status' => 'required|in:' . implode(',', array_keys(self::STATUSES))]);
        $link->update(['link_status' => $request->input('link_status')]);

        return response()->json(['message' => 'Link status updated successfully']);
    }

    public function destroy(ProjectKeyword $link)
    {
        $this->authorizeLink($link);
        $link->delete();

        return redirect()->back()->with('success', 'Link deleted successfully!');
    }

    /* ───────────────────────── Helpers ───────────────────────── */

    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id'         => 'nullable|integer|exists:users,id',
            'task_id'         => 'required|integer|exists:tasks,id',
            'keyword'         => 'required|string|max:255',
            'url'             => 'required|url|max:255',
            'destination_url' => 'nullable|string|max:2000',
            'rel'             => 'nullable|in:' . implode(',', array_keys(self::RELS)),
            'dr'              => 'nullable|integer|min:0|max:100',
            'link_status'     => 'required|in:' . implode(',', array_keys(self::STATUSES)),
            'link_date'       => 'nullable|date|before_or_equal:today',
        ]);
    }

    private function resolveOwnerAndTask(Request $request, array $data): array
    {
        $ownerId = $this->isManager() ? (int) ($data['user_id'] ?? 0) : auth()->id();

        if (!OffsiteSeoController::offsiteUsers()->contains('id', $ownerId)) {
            throw ValidationException::withMessages(['user_id' => 'Please select an Offsite SEO employee.']);
        }

        $task = Task::findOrFail($data['task_id']);

        if ((int) $task->assigned_to !== $ownerId) {
            throw ValidationException::withMessages(['task_id' => 'The selected task is not assigned to this employee.']);
        }
        if (!$task->project_id) {
            throw ValidationException::withMessages(['task_id' => 'The selected task has no project.']);
        }

        return [$ownerId, $task];
    }

    private function attributes(array $data, Task $task, int $ownerId): array
    {
        return [
            'project_id'      => $task->project_id,
            'task_id'         => $task->id,
            'type'            => 'closing',
            'keyword'         => $data['keyword'],
            'url'             => $data['url'],
            'destination_url' => $data['destination_url'] ?? '-',
            'rel'             => $data['rel'] ?? null,
            'dr'              => $data['dr'] ?? null,
            'link_status'     => $data['link_status'],
            'created_by'      => $ownerId,
        ];
    }

    /** Keeps the time of day so ordering stays stable when only the date is changed. */
    private function applyDate(ProjectKeyword $link, ?string $date): void
    {
        if (!$date) {
            return;
        }

        $current = $link->created_at ?? Carbon::now();
        if ($current->toDateString() !== Carbon::parse($date)->toDateString()) {
            $link->created_at = Carbon::parse($date)->setTimeFrom($current);
        }
    }

    private function authorizeLink(ProjectKeyword $link): void
    {
        if ($this->isManager()) {
            return;
        }

        $ownerId = $link->created_by ?? optional($link->task)->assigned_to;

        if ((int) $ownerId !== (int) auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /** Aborts unless the user is a manager or an Offsite SEO employee. */
    private function isManager(): bool
    {
        $user = auth()->user();

        if ($user->hasRole('ADMIN') || $user->hasRole('SEO ADMIN')) {
            return true;
        }
        if (!$user->hasRole(OffsiteSeoController::ROLE)) {
            abort(403, 'Unauthorized action.');
        }

        return false;
    }
}
