<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\LeaveBalanceAdjustment;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\Leave\LeaveService;
use App\Support\Access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Leave policies: the annual allowance and leave year (stored on general_settings),
 * leave types, per-person balances and adjustments, and who is tracked in attendance.
 */
class LeavePolicyController extends Controller
{
    public function __construct(private LeaveService $leaves) {}

    public function index(Request $request)
    {
        $me = Auth::user();
        abort_unless(Access::manageLeavePolicies($me), 403);

        $year = (int) $request->input('year', $this->leaves->currentYear());
        $types = LeaveType::orderBy('sort')->orderBy('name')->get();
        $activeLimited = $types->where('is_active', true)->filter->isLimited()->values();

        $people = User::with('roles')->orderBy('name')->get()->filter(fn ($u) => $u->email !== 'system@10xdigital.ae')->values();
        $balances = $people->where('track_attendance', true)->mapWithKeys(fn ($u) => [$u->id => $this->leaves->summary($u, $year, $activeLimited)]);

        return view('leaves.policies', [
            'settings' => GeneralSetting::first(),
            'types' => $types,
            'limitedTypes' => $activeLimited,
            'people' => $people,
            'balances' => $balances,
            'year' => $year,
            'yearLabel' => $this->leaves->yearLabel($year),
            'currentYear' => $this->leaves->currentYear(),
            'recentAdjustments' => LeaveBalanceAdjustment::with(['balance.user:id,name', 'balance.type:id,name', 'admin:id,name'])->latest('created_at')->limit(15)->get(),
            'canManageAttendance' => Access::manageAttendance($me),
        ]);
    }

    public function updateSettings(Request $request)
    {
        abort_unless(Access::manageLeavePolicies(Auth::user()), 403);

        $data = $request->validate([
            'annual_leave_allowance' => 'required|integer|min:0|max:365',
            'leave_year_start_month' => 'required|integer|min:1|max:12',
        ]);

        $settings = GeneralSetting::firstOrCreate([]);
        $before = $settings->only(['annual_leave_allowance', 'leave_year_start_month']);
        $settings->update($data);

        $synced = DB::transaction(fn () => $this->leaves->syncAllowances());

        Log::info('Leave settings changed', ['by' => Auth::id(), 'before' => $before, 'after' => $data]);

        return response()->json([
            'success' => "Saved. Annual allowance is now {$data['annual_leave_allowance']} days; {$synced} balance(s) for the current leave year were updated. Earlier years keep their original allowance.",
        ]);
    }

    public function storeType(Request $request)
    {
        abort_unless(Access::manageLeavePolicies(Auth::user()), 403);
        $data = $this->validateType($request);
        $data['code'] = Str::slug($data['name'], '_');
        if (LeaveType::where('code', $data['code'])->exists()) {
            $data['code'] .= '_'.Str::lower(Str::random(4));
        }
        $data['sort'] = (int) LeaveType::max('sort') + 1;

        $type = LeaveType::create($data);

        return response()->json(['success' => "{$type->name} added."]);
    }

    public function updateType(Request $request, LeaveType $type)
    {
        abort_unless(Access::manageLeavePolicies(Auth::user()), 403);
        $data = $this->validateType($request, $type);

        DB::transaction(function () use ($type, $data) {
            $allowanceChanged = $type->allowance_source !== $data['allowance_source'] || (float) $type->days_per_year !== (float) ($data['days_per_year'] ?? 0);
            $type->update($data);
            if ($allowanceChanged) {
                $this->leaves->syncAllowances($type->fresh());
            }
        });

        return response()->json(['success' => "{$type->name} updated. Changes apply to the current leave year onwards."]);
    }

    public function adjust(Request $request)
    {
        $admin = Auth::user();
        abort_unless(Access::manageLeavePolicies($admin), 403);

        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'year' => 'required|integer|min:2000|max:2100',
            'days' => 'required|numeric|between:-365,365',
            'reason' => 'required|string|min:3|max:255',
        ]);

        $b = $this->leaves->adjust(User::findOrFail($data['user_id']), LeaveType::findOrFail($data['leave_type_id']), (int) $data['year'], round((float) $data['days'] * 2) / 2, $data['reason'], $admin);

        return response()->json(['success' => 'Balance adjusted. New entitlement: '.rtrim(rtrim(number_format($b->entitlement(), 1), '0'), '.').' days.']);
    }

    public function tracking(Request $request, User $user)
    {
        $me = Auth::user();
        abort_unless(Access::manageAttendance($me) || Access::manageLeavePolicies($me), 403);
        $data = $request->validate(['track_attendance' => 'required|boolean']);

        $user->forceFill(['track_attendance' => (bool) $data['track_attendance']])->save();
        Log::info('Attendance tracking changed', ['by' => $me->id, 'user' => $user->id, 'track' => $user->track_attendance]);

        return response()->json(['success' => $user->name.($user->track_attendance ? ' is now tracked in attendance.' : ' is no longer tracked (no absences, not in reports).')]);
    }

    private function validateType(Request $request, ?LeaveType $type = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60', Rule::unique('leave_types', 'name')->ignore($type?->id)],
            'color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'allowance_source' => ['required', Rule::in(array_keys(LeaveType::SOURCES))],
            'days_per_year' => 'nullable|required_if:allowance_source,fixed|numeric|min:0|max:365',
            'carry_forward_max' => 'nullable|numeric|min:0|max:365',
            'is_paid' => 'boolean',
            'allow_negative' => 'boolean',
            'requires_reason' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Annual Leave must stay on the company allowance so the setting keeps working.
        if ($type && $type->code === 'annual' && $data['allowance_source'] !== 'annual') {
            abort(response()->json(['message' => 'Annual Leave always uses the company annual allowance.', 'errors' => ['allowance_source' => ['Annual Leave always uses the company annual allowance.']]], 422));
        }

        return [
            'name' => $data['name'],
            'color' => $data['color'],
            'allowance_source' => $data['allowance_source'],
            'days_per_year' => $data['allowance_source'] === 'fixed' ? $data['days_per_year'] : null,
            'carry_forward_max' => $data['allowance_source'] === 'none' ? 0 : (float) ($data['carry_forward_max'] ?? 0),
            'is_paid' => $request->boolean('is_paid'),
            'allow_negative' => $request->boolean('allow_negative'),
            'requires_reason' => $request->boolean('requires_reason'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
