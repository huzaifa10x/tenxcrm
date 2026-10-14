<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\Role;
use App\Models\User;
use App\Services\Leave\LeaveService;
use App\Support\Access;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    public function __construct(private LeaveService $leaves) {}

    /* ------------------------------------------------------------- employee */

    public function index(Request $request)
    {
        $me = Auth::user();
        $year = (int) $request->input('year', $this->leaves->currentYear());
        $status = $request->input('status');

        $requests = LeaveRequest::where('user_id', $me->id)
            ->with(['type', 'reviewer:id,name'])
            ->when(in_array($status, LeaveRequest::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->orderByDesc('start_date')
            ->paginate(15)
            ->withQueryString();

        $counts = LeaveRequest::where('user_id', $me->id)->selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        return view('leaves.index', [
            'summary' => $this->leaves->summary($me, $year),
            'year' => $year,
            'yearLabel' => $this->leaves->yearLabel($year),
            'yearRange' => $this->leaves->yearRange($year),
            'years' => $this->yearOptions(),
            'requests' => $requests,
            'counts' => $counts,
            'status' => $status,
            'types' => LeaveType::active()->get(),
            'upcoming' => LeaveRequest::where('user_id', $me->id)->where('status', 'approved')->whereDate('end_date', '>=', today())->with('type')->orderBy('start_date')->limit(5)->get(),
            'canApprove' => Access::approveLeaves($me),
        ]);
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        return response()->json($this->leaves->preview(Auth::user(), LeaveType::findOrFail($data['leave_type_id']), $data['start_date'], $data['end_date']));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
        ], [
            'end_date.after_or_equal' => 'The end date must be on or after the start date.',
        ]);

        $leave = $this->leaves->apply(Auth::user(), $data);

        $msg = 'Leave request submitted for '.$this->leaves->fmt($leave->days).'. You will be notified when it is reviewed.';

        return $request->expectsJson()
            ? response()->json(['success' => $msg, 'id' => $leave->id])
            : redirect()->route('leaves.index')->with('success', $msg);
    }

    public function cancel(Request $request, LeaveRequest $leave)
    {
        $this->leaves->cancel($leave, Auth::user());

        return $this->done($request, 'Leave request cancelled.');
    }

    /** Request details + timeline (owner or approvers). */
    public function show(LeaveRequest $leave)
    {
        $me = Auth::user();
        abort_unless((int) $leave->user_id === (int) $me->id || Access::approveLeaves($me), 403);

        $leave->load(['type', 'user.roles', 'reviewer:id,name', 'events.user:id,name']);

        $years = collect($leave->year_days ?? [])->map(function ($days, $y) use ($leave) {
            $s = $this->leaves->summary($leave->user, (int) $y, collect([$leave->type]))->first();

            return [
                'year' => $this->leaves->yearLabel((int) $y),
                'days' => $days,
                'entitlement' => $s['entitlement'],
                'used' => $s['used'],
                'pending' => $s['pending'],
                'remaining' => $s['remaining'],
            ];
        })->values();

        $overlapsTeam = LeaveRequest::where('status', 'approved')
            ->where('user_id', '!=', $leave->user_id)
            ->overlapping($leave->start_date->toDateString(), $leave->end_date->toDateString())
            ->with('user:id,name')
            ->get()
            ->map(fn ($r) => $r->user?->name.' ('.$r->start_date->format('d M').' – '.$r->end_date->format('d M').')');

        return response()->json([
            'id' => $leave->id,
            'employee' => $leave->user->name,
            'department' => $leave->user->departmentNames(),
            'type' => $leave->type->name,
            'type_color' => $leave->type->color,
            'limited' => $leave->type->isLimited(),
            'start' => $leave->start_date->format('D, d M Y'),
            'end' => $leave->end_date->format('D, d M Y'),
            'days' => $leave->days,
            'reason' => $leave->reason,
            'status' => $leave->status,
            'review_comment' => $leave->review_comment,
            'reviewer' => $leave->reviewer?->name,
            'balance' => $years,
            'team_overlap' => $overlapsTeam,
            'events' => $leave->events->map(fn ($e) => [
                'action' => $e->action,
                'by' => $e->user?->name,
                'comment' => $e->comment,
                'at' => $e->created_at?->format('d M Y, h:i A'),
            ]),
            'can_review' => Access::approveLeaves($me) && ((int) $leave->user_id !== (int) $me->id || $me->hasRole('ADMIN')),
            'can_cancel' => (int) $leave->user_id === (int) $me->id && $leave->status === 'pending',
        ]);
    }

    /* ---------------------------------------------------------------- admin */

    public function admin(Request $request)
    {
        $me = Auth::user();
        abort_unless(Access::approveLeaves($me), 403);

        $data = $request->validate([
            'status' => ['nullable', Rule::in(array_merge(LeaveRequest::STATUSES, ['all']))],
            'user_id' => 'nullable|integer',
            'department' => 'nullable|integer',
            'leave_type_id' => 'nullable|integer',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
            'q' => 'nullable|string|max:100',
        ]);
        $status = $data['status'] ?? 'pending';

        $requests = LeaveRequest::with(['type', 'user.roles', 'reviewer:id,name'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->when($data['user_id'] ?? null, fn ($q, $id) => $q->where('user_id', $id))
            ->when($data['leave_type_id'] ?? null, fn ($q, $id) => $q->where('leave_type_id', $id))
            ->when($data['department'] ?? null, fn ($q, $id) => $q->whereHas('user.roles', fn ($r) => $r->where('roles.id', $id)))
            ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('end_date', '>=', $d))
            ->when($data['to'] ?? null, fn ($q, $d) => $q->whereDate('start_date', '<=', $d))
            ->when(trim($data['q'] ?? ''), fn ($q, $s) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$s}%")))
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy($status === 'pending' ? 'start_date' : 'updated_at', $status === 'pending' ? 'asc' : 'desc')
            ->paginate(20)
            ->withQueryString();

        $counts = LeaveRequest::selectRaw('status, count(*) as n')->groupBy('status')->pluck('n', 'status');

        $today = Carbon::today();
        $upcoming = LeaveRequest::where('status', 'approved')
            ->whereDate('end_date', '>=', $today)
            ->whereDate('start_date', '<=', $today->copy()->addDays(30))
            ->with(['user:id,name', 'type:id,name,color'])
            ->orderBy('start_date')
            ->get();

        return view('leaves.admin', [
            'requests' => $requests,
            'counts' => $counts,
            'status' => $status,
            'filters' => $data,
            'upcoming' => $upcoming,
            'employees' => User::tracked()->orderBy('name')->get(['id', 'name']),
            'departments' => Role::orderBy('name')->get(['id', 'name']),
            'types' => LeaveType::orderBy('sort')->get(),
            'canPolicies' => Access::manageLeavePolicies($me),
        ]);
    }

    public function approve(Request $request, LeaveRequest $leave)
    {
        $data = $request->validate(['comment' => 'nullable|string|max:1000']);
        $this->leaves->approve($leave, Auth::user(), $data['comment'] ?? null);

        return $this->done($request, 'Leave approved. '.$leave->user->name.' has been notified.');
    }

    public function reject(Request $request, LeaveRequest $leave)
    {
        $data = $request->validate(['comment' => 'nullable|string|max:1000']);
        $this->leaves->reject($leave, Auth::user(), $data['comment'] ?? null);

        return $this->done($request, 'Leave rejected. '.$leave->user->name.' has been notified.');
    }

    public function revoke(Request $request, LeaveRequest $leave)
    {
        $data = $request->validate(['comment' => 'nullable|string|max:1000']);
        $this->leaves->revoke($leave, Auth::user(), $data['comment'] ?? null);

        return $this->done($request, 'Approved leave revoked; the days are back in the balance.');
    }

    /* -------------------------------------------------------------- helpers */

    private function done(Request $request, string $message)
    {
        return $request->expectsJson()
            ? response()->json(['success' => $message])
            : back()->with('success', $message);
    }

    private function yearOptions(): array
    {
        $current = $this->leaves->currentYear();
        $first = (int) (LeaveRequest::min('start_date') ? $this->leaves->yearOf(LeaveRequest::min('start_date')) : $current);
        $years = [];
        for ($y = $current + 1; $y >= min($first, $current - 1); $y--) {
            $years[$y] = $this->leaves->yearLabel($y);
        }

        return $years;
    }
}
