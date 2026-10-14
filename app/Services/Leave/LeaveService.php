<?php

namespace App\Services\Leave;

use App\Models\Attendance;
use App\Models\GeneralSetting;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceAdjustment;
use App\Models\LeaveRequest;
use App\Models\LeaveRequestEvent;
use App\Models\LeaveType;
use App\Models\User;
use App\Services\Attendance\AttendancePolicy;
use App\Support\Access;
use App\Support\InAppNotification;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Leave balances and the request workflow.
 *
 * Leave year: starts on the 1st of general_settings.leave_year_start_month (default January) and
 * is identified by the calendar year it starts in. Each person gets a balance row per leave type
 * per leave year, created the first time it's needed, with the allowance COPIED from the policy
 * at that moment. Changing the policy updates the current year's balances only (past years stay
 * as they were). Unused days carry forward up to the type's carry_forward_max (0 = reset).
 *
 * Days: only working days count (weekends and public holidays inside a range are free). The
 * split per leave year is stored on the request, so a 29 Dec – 3 Jan request draws from both years.
 */
class LeaveService
{
    /** How far back an employee may apply (sick leave filed after the fact, etc.). */
    public const MAX_BACKDATE_DAYS = 60;

    /** Read per call: holidays / working days may change while a long-lived instance exists. */
    private function policy(): AttendancePolicy
    {
        return AttendancePolicy::current();
    }

    /* ----------------------------------------------------------- leave years */

    public function startMonth(): int
    {
        $m = (int) (GeneralSetting::first()->leave_year_start_month ?? 1);

        return $m >= 1 && $m <= 12 ? $m : 1;
    }

    public function yearOf(Carbon|string $date): int
    {
        $d = Carbon::parse($date);

        return $d->month >= $this->startMonth() ? $d->year : $d->year - 1;
    }

    public function currentYear(): int
    {
        return $this->yearOf(Carbon::today());
    }

    /** @return array{0:Carbon,1:Carbon} */
    public function yearRange(int $year): array
    {
        $start = Carbon::create($year, $this->startMonth(), 1)->startOfDay();

        return [$start, $start->copy()->addYear()->subDay()];
    }

    public function yearLabel(int $year): string
    {
        if ($this->startMonth() === 1) {
            return (string) $year;
        }
        [$s, $e] = $this->yearRange($year);

        return $s->format('M Y').' – '.$e->format('M Y');
    }

    /** Working dates (Y-m-d) between two dates, inclusive. */
    public function workingDates(Carbon|string $from, Carbon|string $to): array
    {
        $dates = [];
        foreach (CarbonPeriod::create(Carbon::parse($from), Carbon::parse($to)) as $d) {
            if ($this->policy()->isWorkingDay($d)) {
                $dates[] = $d->toDateString();
            }
        }

        return $dates;
    }

    /** @return array<string,float> leave year => days */
    public function daysByYear(Carbon|string $from, Carbon|string $to): array
    {
        $out = [];
        foreach ($this->workingDates($from, $to) as $d) {
            $y = (string) $this->yearOf($d);
            $out[$y] = ($out[$y] ?? 0) + 1;
        }

        return $out;
    }

    /* -------------------------------------------------------------- balances */

    /** The person's balance row for a type and year, created with today's policy if missing. */
    public function balance(User $user, LeaveType $type, int $year): LeaveBalance
    {
        $existing = LeaveBalance::where(['user_id' => $user->id, 'leave_type_id' => $type->id, 'year' => $year])->first();
        if ($existing) {
            return $existing;
        }

        $carry = 0.0;
        if ($type->isLimited() && $type->carry_forward_max > 0) {
            $prev = LeaveBalance::where(['user_id' => $user->id, 'leave_type_id' => $type->id, 'year' => $year - 1])->first();
            if ($prev && $prev->entitlement() !== null) {
                $left = $prev->entitlement() - $this->used($user, $type, $year - 1);
                $carry = min(max(0, $left), (float) $type->carry_forward_max);
            }
        }

        try {
            return LeaveBalance::create([
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'year' => $year,
                'allowance' => $type->currentAllowance(),
                'carried_forward' => $carry,
                'adjustment' => 0,
            ]);
        } catch (QueryException) {
            // Created concurrently.
            return LeaveBalance::where(['user_id' => $user->id, 'leave_type_id' => $type->id, 'year' => $year])->firstOrFail();
        }
    }

    /** Days used in a leave year (approved, or with $statuses e.g. ['pending']). */
    public function used(User $user, LeaveType $type, int $year, array $statuses = ['approved']): float
    {
        [$s, $e] = $this->yearRange($year);

        return (float) LeaveRequest::where('user_id', $user->id)
            ->where('leave_type_id', $type->id)
            ->whereIn('status', $statuses)
            ->overlapping($s->toDateString(), $e->toDateString())
            ->get(['id', 'year_days'])
            ->sum(fn ($r) => $r->daysInYear($year));
    }

    /**
     * Balance summary per active leave type.
     *
     * @return Collection<int, array{type:LeaveType, allowance:?float, carried:float, adjustment:float, entitlement:?float, used:float, pending:float, remaining:?float, available:?float}>
     */
    public function summary(User $user, ?int $year = null, ?Collection $types = null): Collection
    {
        $year ??= $this->currentYear();
        $types ??= LeaveType::active()->get();

        return $types->map(function (LeaveType $type) use ($user, $year) {
            $b = $this->balance($user, $type, $year);
            $used = $this->used($user, $type, $year);
            $pending = $this->used($user, $type, $year, ['pending']);
            $ent = $b->entitlement();

            return [
                'type' => $type,
                'balance' => $b,
                'allowance' => $b->allowance,
                'carried' => $b->carried_forward,
                'adjustment' => $b->adjustment,
                'entitlement' => $ent,
                'used' => $used,
                'pending' => $pending,
                'remaining' => $ent === null ? null : $ent - $used,
                'available' => $ent === null ? null : $ent - $used - $pending,
            ];
        })->values();
    }

    /* ------------------------------------------------------------- workflow */

    /** What a request would cost, without saving. */
    public function preview(User $user, LeaveType $type, string $from, string $to): array
    {
        $byYear = $this->daysByYear($from, $to);
        $years = [];
        foreach ($byYear as $y => $days) {
            $s = $this->summary($user, (int) $y, collect([$type]))->first();
            $years[] = ['year' => (int) $y, 'label' => $this->yearLabel((int) $y), 'days' => $days, 'available' => $s['available']];
        }

        return [
            'days' => array_sum($byYear),
            'years' => $years,
            'overlap' => $this->overlapFor($user, $from, $to)?->only(['id', 'start_date', 'end_date', 'status']),
        ];
    }

    public function apply(User $user, array $data): LeaveRequest
    {
        $type = LeaveType::where('id', $data['leave_type_id'] ?? 0)->where('is_active', true)->first();
        if (! $type) {
            throw ValidationException::withMessages(['leave_type_id' => 'Choose a valid leave type.']);
        }

        $from = Carbon::parse($data['start_date'])->startOfDay();
        $to = Carbon::parse($data['end_date'])->startOfDay();
        $reason = trim((string) ($data['reason'] ?? ''));

        if ($to->lt($from)) {
            throw ValidationException::withMessages(['end_date' => 'The end date must be on or after the start date.']);
        }
        if ($from->lt(Carbon::today()->subDays(self::MAX_BACKDATE_DAYS))) {
            throw ValidationException::withMessages(['start_date' => 'Leave can be applied at most '.self::MAX_BACKDATE_DAYS.' days back.']);
        }
        if ($to->gt(Carbon::today()->addYear())) {
            throw ValidationException::withMessages(['end_date' => 'Leave can be applied at most one year ahead.']);
        }
        if ($from->diffInDays($to) > 366) {
            throw ValidationException::withMessages(['end_date' => 'A single request cannot be longer than a year.']);
        }
        if ($type->requires_reason && $reason === '') {
            throw ValidationException::withMessages(['reason' => "A reason is required for {$type->name}."]);
        }

        $byYear = $this->daysByYear($from, $to);
        $days = array_sum($byYear);
        if ($days <= 0) {
            throw ValidationException::withMessages(['end_date' => 'The selected dates are all weekends or holidays — no leave is needed.']);
        }

        $request = DB::transaction(function () use ($user, $type, $from, $to, $reason, $byYear, $days) {
            // Serialise this person's requests so two submissions can't both pass the checks.
            User::whereKey($user->id)->lockForUpdate()->first();

            if ($overlap = $this->overlapFor($user, $from->toDateString(), $to->toDateString())) {
                throw ValidationException::withMessages(['start_date' => sprintf(
                    'These dates overlap your %s request for %s – %s.',
                    $overlap->status,
                    $overlap->start_date->format('d M Y'),
                    $overlap->end_date->format('d M Y')
                )]);
            }

            $this->assertBalance($user, $type, $byYear);

            $req = LeaveRequest::create([
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'start_date' => $from->toDateString(),
                'end_date' => $to->toDateString(),
                'days' => $days,
                'year_days' => $byYear,
                'reason' => $reason ?: null,
                'status' => 'pending',
            ]);
            $this->log($req, $user, 'submitted', null, ['days' => $days, 'year_days' => $byYear]);

            return $req;
        });

        $request->load('type', 'user');
        foreach ($this->approvers()->where('id', '!=', $user->id) as $approver) {
            InAppNotification::send($approver, 'Leave request', "{$user->name} requested {$this->fmt($days)} of {$type->name} ({$this->range($request)}).", route('leaves.admin', ['status' => 'pending']));
        }

        return $request;
    }

    public function approve(LeaveRequest $request, User $approver, ?string $comment = null): LeaveRequest
    {
        $this->assertCanReview($request, $approver);

        $request = DB::transaction(function () use ($request, $approver, $comment) {
            $req = LeaveRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            if ($req->status !== 'pending') {
                throw ValidationException::withMessages(['status' => "This request is already {$req->status}."]);
            }
            User::whereKey($req->user_id)->lockForUpdate()->first();

            // Re-check: approved days + this request must still fit (other pending requests don't block).
            $this->assertBalance($req->user, $req->type, $req->year_days ?? [], ['approved'], 'approve');

            $req->update(['status' => 'approved', 'reviewed_by' => $approver->id, 'reviewed_at' => now(), 'review_comment' => $comment ?: null]);
            $this->log($req, $approver, 'approved', $comment);

            // Days already marked absent become leave (punched days are left alone).
            Attendance::where('user_id', $req->user_id)
                ->whereBetween('date', [$req->start_date->toDateString(), $req->end_date->toDateString()])
                ->whereNull('punch_in')
                ->where('status', 'absent')
                ->update(['status' => 'leave']);

            return $req;
        });

        InAppNotification::send($request->user, 'Leave approved', "Your {$request->type->name} for {$this->range($request)} was approved.".($comment ? " Comment: {$comment}" : ''), route('leaves.index'));

        return $request->fresh(['type', 'user', 'reviewer']);
    }

    public function reject(LeaveRequest $request, User $approver, ?string $comment = null): LeaveRequest
    {
        $this->assertCanReview($request, $approver);

        DB::transaction(function () use ($request, $approver, $comment) {
            $req = LeaveRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            if ($req->status !== 'pending') {
                throw ValidationException::withMessages(['status' => "This request is already {$req->status}."]);
            }
            $req->update(['status' => 'rejected', 'reviewed_by' => $approver->id, 'reviewed_at' => now(), 'review_comment' => $comment ?: null]);
            $this->log($req, $approver, 'rejected', $comment);
        });

        InAppNotification::send($request->user, 'Leave rejected', "Your {$request->type->name} for {$this->range($request)} was rejected.".($comment ? " Comment: {$comment}" : ''), route('leaves.index'));

        return $request->fresh(['type', 'user', 'reviewer']);
    }

    /** Employee withdraws their own pending request. */
    public function cancel(LeaveRequest $request, User $user): LeaveRequest
    {
        if ((int) $request->user_id !== (int) $user->id) {
            abort(403, 'You can only cancel your own requests.');
        }

        DB::transaction(function () use ($request, $user) {
            $req = LeaveRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            if ($req->status !== 'pending') {
                throw ValidationException::withMessages(['status' => 'Only pending requests can be cancelled. Ask an approver to revoke an approved leave.']);
            }
            $req->update(['status' => 'cancelled']);
            $this->log($req, $user, 'cancelled');
        });

        return $request->fresh();
    }

    /** Approver cancels an approved leave; the days return to the balance. */
    public function revoke(LeaveRequest $request, User $approver, ?string $comment = null): LeaveRequest
    {
        $this->assertCanReview($request, $approver);

        DB::transaction(function () use ($request, $approver, $comment) {
            $req = LeaveRequest::whereKey($request->id)->lockForUpdate()->firstOrFail();
            if ($req->status !== 'approved') {
                throw ValidationException::withMessages(['status' => 'Only approved requests can be revoked.']);
            }
            $req->update(['status' => 'cancelled', 'reviewed_by' => $approver->id, 'reviewed_at' => now(), 'review_comment' => $comment ?: $req->review_comment]);
            $this->log($req, $approver, 'revoked', $comment);

            // Leave rows for past days go back to absent; nothing is deleted.
            Attendance::where('user_id', $req->user_id)
                ->whereBetween('date', [$req->start_date->toDateString(), $req->end_date->toDateString()])
                ->whereNull('punch_in')
                ->where('status', 'leave')
                ->update(['status' => 'absent']);
        });

        InAppNotification::send($request->user, 'Leave revoked', "Your {$request->type->name} for {$this->range($request)} was revoked.".($comment ? " Comment: {$comment}" : ''), route('leaves.index'));

        return $request->fresh(['type', 'user', 'reviewer']);
    }

    /** Manual +/- days on a person's balance, with a reason (audited). */
    public function adjust(User $user, LeaveType $type, int $year, float $days, string $reason, User $admin): LeaveBalance
    {
        if ($days == 0) {
            throw ValidationException::withMessages(['days' => 'Enter a non-zero number of days.']);
        }

        return DB::transaction(function () use ($user, $type, $year, $days, $reason, $admin) {
            $b = $this->balance($user, $type, $year);
            $b = LeaveBalance::whereKey($b->id)->lockForUpdate()->first();
            if ($b->allowance === null) {
                throw ValidationException::withMessages(['days' => "{$type->name} has no limit, so there is nothing to adjust."]);
            }
            $b->update(['adjustment' => $b->adjustment + $days]);
            LeaveBalanceAdjustment::create(['leave_balance_id' => $b->id, 'user_id' => $admin->id, 'days' => $days, 'reason' => $reason, 'created_at' => now()]);

            return $b;
        });
    }

    /**
     * After the annual allowance or a type's allowance changes: update the CURRENT leave year's
     * balances (and later years already created). Earlier years keep the allowance they had.
     */
    public function syncAllowances(?LeaveType $only = null): int
    {
        $year = $this->currentYear();
        $n = 0;
        $types = $only ? collect([$only]) : LeaveType::all();
        foreach ($types as $type) {
            $n += LeaveBalance::where('leave_type_id', $type->id)
                ->where('year', '>=', $year)
                ->update(['allowance' => $type->currentAllowance()]);
        }

        return $n;
    }

    /* --------------------------------------------------------------- helpers */

    public function overlapFor(User $user, string $from, string $to, ?int $ignoreId = null): ?LeaveRequest
    {
        return LeaveRequest::where('user_id', $user->id)
            ->active()
            ->overlapping($from, $to)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->first();
    }

    /** Users who can approve leave. */
    public function approvers(): Collection
    {
        return User::with('roles.permissions')->get()->filter(fn ($u) => Access::approveLeaves($u))->values();
    }

    /**
     * @param  array<string,float>  $byYear
     */
    private function assertBalance(User $user, LeaveType $type, array $byYear, array $counted = ['approved', 'pending'], string $mode = 'apply'): void
    {
        if (! $type->isLimited() || $type->allow_negative) {
            return;
        }
        foreach ($byYear as $year => $days) {
            $b = $this->balance($user, $type, (int) $year);
            $ent = $b->entitlement();
            if ($ent === null) {
                continue;
            }
            $taken = $this->used($user, $type, (int) $year, $counted);
            $left = $ent - $taken;
            if ($days > $left + 0.0001) {
                $label = $this->yearLabel((int) $year);
                $msg = $mode === 'approve'
                    ? "Not enough {$type->name} balance to approve: {$this->fmt($days)} requested in {$label}, {$this->fmt(max(0, $left))} left."
                    : "Not enough {$type->name} balance: you asked for {$this->fmt($days)} in {$label} but only {$this->fmt(max(0, $left))} available"
                        .($counted === ['approved', 'pending'] ? ' (pending requests included).' : '.');
                throw ValidationException::withMessages(['leave_type_id' => $msg]);
            }
        }
    }

    private function assertCanReview(LeaveRequest $request, User $approver): void
    {
        if (! Access::approveLeaves($approver)) {
            abort(403, 'You are not allowed to review leave requests.');
        }
        if ((int) $request->user_id === (int) $approver->id && ! $approver->hasRole('ADMIN')) {
            abort(403, 'You cannot review your own leave request.');
        }
    }

    private function log(LeaveRequest $req, ?User $by, string $action, ?string $comment = null, ?array $meta = null): void
    {
        LeaveRequestEvent::create([
            'leave_request_id' => $req->id,
            'user_id' => $by?->id,
            'action' => $action,
            'comment' => $comment ?: null,
            'meta' => $meta,
            'created_at' => now(),
        ]);
    }

    public function fmt(float $days): string
    {
        $n = rtrim(rtrim(number_format($days, 1), '0'), '.');

        return $n.' '.($days == 1 ? 'day' : 'days');
    }

    private function range(LeaveRequest $r): string
    {
        return $r->start_date->equalTo($r->end_date)
            ? $r->start_date->format('d M Y')
            : $r->start_date->format('d M').' – '.$r->end_date->format('d M Y');
    }
}
