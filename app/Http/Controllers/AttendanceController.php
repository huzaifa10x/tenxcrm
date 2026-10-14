<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GeneralSetting;
use App\Models\LeaveRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\Attendance\AttendancePolicy;
use App\Services\Attendance\AttendanceReport;
use App\Services\Attendance\AttendanceService;
use App\Support\Access;
use App\View\Components\Dashboards\PunchInOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    /* ------------------------------------------------------------- punching
     * Used by the dashboard widget (web) and the 10X One mobile app (API). The JSON shape
     * ({success|error, state, ...} with 403 on refusal) is unchanged. */

    public function punchIn(Request $request, AttendanceService $service)
    {
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $lat = $request->filled('latitude') ? (float) $request->latitude : null;
        $lng = $request->filled('longitude') ? (float) $request->longitude : null;
        $result = $service->punchIn(Auth::user(), $lat, $lng);

        if (! $result['ok']) {
            return response()->json(['error' => $result['message']], 403);
        }

        $a = $result['attendance'];
        $punchedAt = $service->policy()->at($a->dateString(), $a->punch_in);

        return response()->json([
            'success' => $result['message'],
            'state' => 'working',
            'punch_in' => $punchedAt->toIso8601String(),
            'punch_in_label' => $punchedAt->format('h:i A'),
            'status' => $a->status,
        ]);
    }

    public function punchOut(AttendanceService $service)
    {
        $result = $service->punchOut(Auth::user());

        if (! $result['ok']) {
            return response()->json(['error' => $result['message']], 403);
        }

        $a = $result['attendance'];

        return response()->json([
            'success' => $result['message'],
            'state' => 'done',
            'punch_out_label' => Carbon::parse($a->punch_out)->format('h:i A'),
            'total_hours' => PunchInOut::formatDuration($a->total_hours),
            'status' => $a->status,
        ]);
    }

    /* ------------------------------------------------------------ main page */

    public function index(Request $request, AttendanceService $service, AttendanceReport $report)
    {
        $service->closeThrough();

        $me = Auth::user();
        $canManage = Access::manageAttendance($me);
        $policy = $service->policy();

        $data = $request->validate([
            'month' => 'nullable|date_format:Y-m',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'user_id' => 'nullable|integer',
            'department' => 'nullable|integer',
            'status' => 'nullable|string|max:20',
            'q' => 'nullable|string|max:100',
        ]);

        [$from, $to, $month] = $this->range($data);

        // Employees only ever see themselves, whatever the query string says.
        $userId = $canManage ? ($data['user_id'] ?? null) : $me->id;

        if ($userId) {
            $employee = User::with('roles')->findOrFail($userId);
            if (! Access::viewAttendanceOf($me, (int) $employee->id)) {
                abort(403);
            }

            $result = $report->forUser(
                $employee,
                Attendance::where('user_id', $employee->id)->whereBetween('date', [$from->toDateString(), $to->toDateString()])->withCount('notes')->get()->keyBy(fn ($r) => $r->dateString()),
                LeaveRequest::where('user_id', $employee->id)->where('status', 'approved')->overlapping($from->toDateString(), $to->toDateString())->with('type')->get(),
                $from,
                $to
            );

            // History: past days and today only, newest first.
            $days = collect($result['days'])->reject(fn ($d) => $d['date'] > now()->toDateString() || $d['status'] === 'not_joined')->reverse()->values();
            if ($status = $data['status'] ?? null) {
                $days = $days->filter(fn ($d) => $status === 'missed' ? $d['missed_out'] : ($status === 'late' ? $d['late_minutes'] > 0 && $d['punch_in'] : $d['status'] === $status))->values();
            }
            if ($q = trim($data['q'] ?? '')) {
                $days = $days->filter(fn ($d) => str_contains(strtolower(Carbon::parse($d['date'])->format('Y-m-d D d M l')), strtolower($q)))->values();
            }

            // Six-month trend for the chart.
            $trendFrom = $from->copy()->startOfMonth()->subMonths(5);
            $trendRecords = Attendance::where('user_id', $employee->id)->whereBetween('date', [$trendFrom->toDateString(), $to->toDateString()])->get()->keyBy(fn ($r) => $r->dateString());
            $trendLeaves = LeaveRequest::where('user_id', $employee->id)->where('status', 'approved')->overlapping($trendFrom->toDateString(), $to->toDateString())->get();
            $trend = [];
            for ($m = $trendFrom->copy(); $m->lte($to); $m->addMonth()) {
                $mEnd = $m->copy()->endOfMonth()->min($to);
                $s = $report->forUser($employee, $trendRecords, $trendLeaves, $m->copy(), $mEnd)['summary'];
                $trend[] = ['label' => $m->format('M y'), 'pct' => $s['attendance_pct'], 'late' => $s['late'], 'absent' => $s['absent'], 'avg' => $s['avg_minutes'] ? round($s['avg_minutes'] / 60, 1) : 0];
            }

            return view('attendance.employee', [
                'employee' => $employee,
                'summary' => $result['summary'],
                'days' => $days,
                'calendar' => $result['days'],
                'trend' => $trend,
                'today' => $service->current($employee),
                'todayLeave' => $service->approvedLeaveOn($employee, Carbon::today()->toDateString()),
                'from' => $from, 'to' => $to, 'month' => $month,
                'filters' => $data,
                'policy' => $policy,
                'canManage' => $canManage,
                'isSelf' => (int) $employee->id === (int) $me->id,
                'employees' => $canManage ? User::tracked()->orderBy('name')->get(['id', 'name']) : collect(),
            ]);
        }

        // Team view (managers only).
        $users = $this->teamQuery($data)->get();
        $built = $report->build($users, $from, $to);

        $rows = $built['rows'];
        if (($data['status'] ?? null) === 'issues') {
            $rows = $rows->filter(fn ($r) => $r['summary']['absent'] || $r['summary']['missed_punch_outs'] || $r['summary']['late'])->values();
        }

        return view('attendance.team', [
            'rows' => $rows,
            'totals' => $built['totals'],
            'trend' => $built['trend'],
            'todayBoard' => $this->todayBoard($users, $service),
            'from' => $from, 'to' => $to, 'month' => $month,
            'filters' => $data,
            'policy' => $policy,
            'departments' => Role::orderBy('name')->get(['id', 'name']),
            'employees' => User::tracked()->orderBy('name')->get(['id', 'name']),
            'pendingLeaves' => Access::approveLeaves($me) ? LeaveRequest::where('status', 'pending')->count() : null,
        ]);
    }

    /** CSV of the team summary (or one person's days) for the selected range. */
    public function export(Request $request, AttendanceReport $report): StreamedResponse
    {
        $me = Auth::user();
        $data = $request->validate([
            'month' => 'nullable|date_format:Y-m', 'from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from',
            'user_id' => 'nullable|integer', 'department' => 'nullable|integer', 'q' => 'nullable|string|max:100',
        ]);
        [$from, $to] = $this->range($data);

        if (! Access::manageAttendance($me)) {
            $data['user_id'] = $me->id;
        }

        if (! empty($data['user_id'])) {
            $u = User::findOrFail($data['user_id']);
            $built = $report->build(collect([$u]), $from, $to);
            $name = 'attendance-'.str($u->name)->slug().'-'.$from->format('Ymd').'-'.$to->format('Ymd').'.csv';

            return response()->streamDownload(function () use ($built) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Date', 'Day', 'Status', 'Punch in', 'Punch out', 'Worked', 'Late (min)', 'Early out (min)', 'Missed punch-out', 'Leave']);
                foreach ($built['rows'][0]['days'] as $d) {
                    fputcsv($out, [$d['date'], Carbon::parse($d['date'])->format('D'), AttendanceReport::statusLabel($d['status']), $d['punch_in'], $d['punch_out'], AttendancePolicy::formatMinutes($d['worked']), $d['late_minutes'], $d['early_minutes'], $d['missed_out'] ? 'yes' : '', $d['leave']]);
                }
                fclose($out);
            }, $name, ['Content-Type' => 'text/csv']);
        }

        $built = $report->build($this->teamQuery($data)->get(), $from, $to);

        return response()->streamDownload(function () use ($built) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Employee', 'Department', 'Working days', 'Present', 'Absent', 'Late', 'Late minutes', 'Half days', 'Early outs', 'Leave days', 'Missed punch-outs', 'Avg hours', 'Attendance %']);
            foreach ($built['rows'] as $r) {
                $s = $r['summary'];
                fputcsv($out, [$r['user']->name, $r['user']->departmentNames(), $s['working_days'], $s['present'], $s['absent'], $s['late'], $s['late_minutes'], $s['half_days'], $s['early_outs'], $s['leave_days'], $s['missed_punch_outs'], $s['avg_minutes'] ? round($s['avg_minutes'] / 60, 2) : '', $s['attendance_pct']]);
            }
            fclose($out);
        }, 'attendance-team-'.$from->format('Ymd').'-'.$to->format('Ymd').'.csv', ['Content-Type' => 'text/csv']);
    }

    /* ---------------------------------------------------- admin corrections */

    public function show(Attendance $attendance)
    {
        if (! Access::viewAttendanceOf(Auth::user(), (int) $attendance->user_id)) {
            abort(403);
        }

        $policy = AttendancePolicy::current();

        return response()->json([
            'record' => [
                'id' => $attendance->id,
                'date' => $attendance->dateString(),
                'date_label' => Carbon::parse($attendance->dateString())->format('l, d M Y'),
                'user' => $attendance->user?->only(['id', 'name']),
                'punch_in' => $attendance->punch_in ? substr($attendance->punch_in, 0, 5) : null,
                'punch_out' => $attendance->punch_out ? substr($attendance->punch_out, 0, 5) : null,
                'status' => $attendance->status,
                'worked' => AttendancePolicy::formatMinutes($policy->workedMinutes($attendance)),
                'late_minutes' => $policy->lateMinutes($attendance),
                'early_minutes' => $policy->earlyOutMinutes($attendance),
                'auto_closed' => (bool) $attendance->auto_closed,
                'location' => $attendance->location,
                'edited_by' => $attendance->editor?->name,
                'edited_at' => $attendance->edited_at?->format('d M Y, h:i A'),
            ],
            'notes' => $attendance->notes()->with('user:id,name')->latest()->get()->map(fn ($n) => [
                'note' => $n->note,
                'user' => $n->user?->name,
                'at' => $n->created_at?->format('d M Y, h:i A'),
            ]),
            'can_edit' => Access::manageAttendance(Auth::user()),
            'can_note' => Access::manageAttendance(Auth::user()) || ((int) $attendance->user_id === (int) Auth::id()
                && (Carbon::parse($attendance->dateString())->isToday() || (Carbon::parse($attendance->dateString())->isYesterday() && $attendance->punch_in && ! $attendance->punch_out))),
        ]);
    }

    public function update(Request $request, Attendance $attendance, AttendanceService $service)
    {
        abort_unless(Access::manageAttendance(Auth::user()), 403);
        $data = $this->validateCorrection($request);

        $record = $service->correct($attendance, $data, Auth::user(), $data['reason']);

        return response()->json(['success' => 'Attendance updated.', 'record' => $record]);
    }

    public function store(Request $request, AttendanceService $service)
    {
        abort_unless(Access::manageAttendance(Auth::user()), 403);
        $data = $this->validateCorrection($request, true);

        $employee = User::findOrFail($data['user_id']);
        $record = $service->createFor($employee, $data['date'], $data, Auth::user(), $data['reason']);

        return response()->json(['success' => 'Attendance saved.', 'record' => $record]);
    }

    private function validateCorrection(Request $request, bool $creating = false): array
    {
        $rules = [
            'punch_in' => 'nullable|date_format:H:i',
            'punch_out' => 'nullable|date_format:H:i',
            'status' => ['nullable', Rule::in(Attendance::STATUSES)],
            'reason' => 'required|string|min:3|max:255',
        ];
        if ($creating) {
            $rules['user_id'] = 'required|integer|exists:users,id';
            $rules['date'] = 'required|date|before_or_equal:today';
        }
        $data = $request->validate($rules, ['reason.required' => 'Give a short reason for the change (kept in the record history).']);

        if (! empty($data['punch_out']) && empty($data['punch_in'])) {
            abort(response()->json(['message' => 'Add a punch-in time before a punch-out time.', 'errors' => ['punch_out' => ['Add a punch-in time first.']]], 422));
        }

        return $data;
    }

    /* ------------------------------------------------- legacy JSON endpoint
     * GET /api/attendance/monthly (mobile app) and the old /admin/attendance URLs. JSON callers
     * get the same shape as before; browsers go to the new page (?view=classic keeps the old grid). */

    public function monthlyAttendance(Request $request)
    {
        $authUser = Auth::user();

        if (! $request->expectsJson() && $request->input('view') !== 'classic') {
            return redirect()->route('attendance.index', $request->only(['month', 'user_id']));
        }

        $month = preg_match('/^\d{4}-\d{2}$/', (string) $request->input('month')) ? $request->input('month') : now()->format('Y-m');
        $canSeeAll = Access::manageAttendance($authUser);
        $userId = $canSeeAll ? $request->input('user_id') : $authUser->id;

        $generalSettings = GeneralSetting::first();
        $policy = AttendancePolicy::current();
        $officeStartTime = $policy->open;
        $halfDayThreshold = $policy->halfDayAfter;
        $breakEndTime = $generalSettings->break_end_time ?? '14:00:00';
        $earlyOutThreshold = $policy->earlyOutBefore;
        $lateThreshold = Carbon::createFromFormat('H:i:s', $officeStartTime)->addMinutes($policy->graceMinutes)->format('H:i:s');

        $late_deduction_threshold = $generalSettings->late_deduction_threshold ?? 3;
        $half_day_deduction_threshold = $generalSettings->half_day_deduction_threshold ?? 2;

        $monthCarbon = Carbon::parse($month.'-01');
        $totalDays = $monthCarbon->daysInMonth;
        $holidays = $policy->holidays;

        $usersQuery = User::query();
        if (! $canSeeAll) {
            $usersQuery->where('id', $authUser->id);
        } elseif ($userId) {
            $usersQuery->where('id', $userId);
        } else {
            $usersQuery->tracked();
        }

        $users = $usersQuery
            ->with(['attendances' => fn ($q) => $q->whereYear('date', $monthCarbon->year)->whereMonth('date', $monthCarbon->month)])
            ->get();

        $totalPresent = $totalAbsent = $totalLate = $totalHalfDay = $totalEarlyOut = $deductions = 0;

        if ($userId && ($user = $users->first())) {
            $lateCount = $halfDayCount = 0;

            for ($day = 1; $day <= $totalDays; $day++) {
                $carbonDate = $monthCarbon->copy()->day($day);
                $date = $carbonDate->toDateString();

                if (! $policy->isWorkingDay($carbonDate)) {
                    continue;
                }

                $attendance = $user->attendances->first(fn ($a) => $a->dateString() === $date);

                if ($attendance) {
                    switch ($attendance->status) {
                        case 'present':
                            $totalPresent++;
                            break;
                        case 'late':
                            $totalLate++;
                            if (++$lateCount == $late_deduction_threshold) {
                                $deductions++;
                                $lateCount = 0;
                            }
                            break;
                        case 'half_day':
                            $totalHalfDay++;
                            if (++$halfDayCount == $half_day_deduction_threshold) {
                                $deductions++;
                                $halfDayCount = 0;
                            }
                            break;
                        case 'early_out':
                            $totalEarlyOut++;
                            break;
                        case 'absent':
                            $totalAbsent++;
                            $lateCount = $halfDayCount = 0;
                            break;
                    }
                } elseif ($carbonDate->isPast() && ! $carbonDate->isToday() && $carbonDate->gte(Carbon::parse($user->created_at)->startOfDay())) {
                    $totalAbsent++;
                    $lateCount = $halfDayCount = 0;
                }
            }
        }

        $todayAttendanceId = Attendance::where('user_id', $authUser->id)->whereDate('date', today())->value('id');

        if ($request->expectsJson()) {
            return response()->json([
                'users' => $users,
                'month' => $month,
                'summary' => [
                    'present' => $totalPresent,
                    'absent' => $totalAbsent,
                    'late' => $totalLate,
                    'half_day' => $totalHalfDay,
                    'early_out' => $totalEarlyOut,
                    'deductions' => $deductions,
                ],
            ]);
        }

        return view('attendance.index', compact('users', 'breakEndTime', 'earlyOutThreshold', 'halfDayThreshold', 'lateThreshold', 'month', 'totalDays', 'userId', 'totalPresent', 'totalAbsent', 'totalLate', 'totalHalfDay', 'totalEarlyOut', 'deductions', 'holidays', 'todayAttendanceId'));
    }

    /* -------------------------------------------------------------- helpers */

    /** @return array{0:Carbon,1:Carbon,2:string} from, to (capped to the range), month key */
    private function range(array $data): array
    {
        if (! empty($data['from']) && ! empty($data['to'])) {
            $from = Carbon::parse($data['from'])->startOfDay();
            $to = Carbon::parse($data['to'])->startOfDay();
            if ($from->diffInDays($to) > 366) {
                $to = $from->copy()->addDays(366);
            }

            return [$from, $to, $from->format('Y-m')];
        }

        $month = $data['month'] ?? now()->format('Y-m');
        $from = Carbon::parse($month.'-01')->startOfDay();

        return [$from, $from->copy()->endOfMonth()->startOfDay(), $month];
    }

    private function teamQuery(array $data)
    {
        return User::tracked()
            ->with('roles')
            ->when($data['user_id'] ?? null, fn ($q, $id) => $q->where('id', $id))
            ->when($data['department'] ?? null, fn ($q, $id) => $q->whereHas('roles', fn ($r) => $r->where('roles.id', $id)))
            ->when(trim($data['q'] ?? ''), fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%")))
            ->orderBy('name');
    }

    /** Who is in / late / on leave / not in yet, today. */
    private function todayBoard($users, AttendanceService $service): array
    {
        $today = Carbon::today()->toDateString();
        $records = Attendance::whereIn('user_id', $users->pluck('id'))->whereDate('date', $today)->get()->keyBy('user_id');
        $onLeave = LeaveRequest::whereIn('user_id', $users->pluck('id'))->where('status', 'approved')->overlapping($today, $today)->with('type:id,name')->get()->keyBy('user_id');
        $working = $service->policy()->isWorkingDay($today);

        $board = ['in' => [], 'late' => [], 'out' => [], 'leave' => [], 'missing' => []];
        foreach ($users as $u) {
            $r = $records->get($u->id);
            if ($onLeave->has($u->id) && ! $r?->punch_in) {
                $board['leave'][] = ['user' => $u, 'note' => $onLeave[$u->id]->type?->name];
            } elseif ($r && $r->punch_in && $r->punch_out) {
                $board['out'][] = ['user' => $u, 'note' => Carbon::parse($r->punch_out)->format('h:i A')];
            } elseif ($r && $r->punch_in) {
                $key = in_array($r->status, ['late', 'half_day'], true) ? 'late' : 'in';
                $board[$key][] = ['user' => $u, 'note' => Carbon::parse($r->punch_in)->format('h:i A')];
            } elseif ($working) {
                $board['missing'][] = ['user' => $u, 'note' => null];
            }
        }

        return ['working' => $working, 'groups' => $board];
    }
}
