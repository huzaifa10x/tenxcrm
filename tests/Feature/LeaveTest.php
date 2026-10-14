<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\Leave\LeaveService;

class LeaveTest extends AttendanceLeaveTestCase
{
    private function apply($user, array $data)
    {
        return $this->actingAs($user)->postJson(route('leaves.store'), $data + ['leave_type_id' => $this->annual()->id, 'reason' => 'Family trip']);
    }

    private function balance($user, ?int $year = 2026, ?LeaveType $type = null): array
    {
        return app(LeaveService::class)->summary($user, $year, collect([$type ?? $this->annual()]))->first();
    }

    public function test_only_working_days_are_counted(): void
    {
        // Fri 16 – Tue 20 Oct = Fri, Mon, Tue = 3 days; add a holiday on Monday → 2.
        $this->apply($this->alice, ['start_date' => '2026-10-16', 'end_date' => '2026-10-20'])->assertOk();
        $this->assertEquals(3, LeaveRequest::first()->days);

        $this->settings(['holidays' => ['2026-10-26']]);
        $this->apply($this->alice, ['start_date' => '2026-10-23', 'end_date' => '2026-10-27'])->assertOk();
        $this->assertEquals(2, LeaveRequest::latest('id')->first()->days);

        // Weekend only → refused.
        $this->apply($this->alice, ['start_date' => '2026-10-31', 'end_date' => '2026-11-01'])->assertStatus(422)->assertJsonValidationErrors('end_date');
    }

    public function test_invalid_ranges_are_refused(): void
    {
        $this->apply($this->alice, ['start_date' => '2026-10-20', 'end_date' => '2026-10-19'])->assertStatus(422)->assertJsonValidationErrors('end_date');
        $this->apply($this->alice, ['start_date' => '2026-01-05', 'end_date' => '2026-01-06'])->assertStatus(422)->assertJsonValidationErrors('start_date'); // > 60 days back
        $this->apply($this->alice, ['start_date' => '2028-01-05', 'end_date' => '2028-01-06'])->assertStatus(422); // > 1 year ahead
        $this->actingAs($this->alice)->postJson(route('leaves.store'), ['leave_type_id' => $this->sick()->id, 'start_date' => '2026-10-20', 'end_date' => '2026-10-20'])
            ->assertStatus(422)->assertJsonValidationErrors('reason');
    }

    public function test_overlapping_requests_are_refused(): void
    {
        $this->apply($this->alice, ['start_date' => '2026-10-19', 'end_date' => '2026-10-21'])->assertOk();
        $this->apply($this->alice, ['start_date' => '2026-10-21', 'end_date' => '2026-10-22'])->assertStatus(422)->assertJsonValidationErrors('start_date');
        // Any type overlaps.
        $this->actingAs($this->alice)->postJson(route('leaves.store'), ['leave_type_id' => $this->sick()->id, 'start_date' => '2026-10-20', 'end_date' => '2026-10-20', 'reason' => 'flu'])->assertStatus(422);
        // Someone else on the same dates is fine.
        $this->apply($this->bob, ['start_date' => '2026-10-19', 'end_date' => '2026-10-21'])->assertOk();

        // After cancelling, the dates are free again.
        $this->actingAs($this->alice)->postJson(route('leaves.cancel', LeaveRequest::where('user_id', $this->alice->id)->first()))->assertOk();
        $this->apply($this->alice, ['start_date' => '2026-10-21', 'end_date' => '2026-10-22'])->assertOk();
    }

    public function test_default_12_day_allowance_and_insufficient_balance(): void
    {
        $b = $this->balance($this->alice);
        $this->assertEquals(12, $b['entitlement']);
        $this->assertEquals(12, $b['remaining']);

        // Mon 2 Nov – Tue 17 Nov = 12 working days → OK, then 1 more → refused.
        $this->apply($this->alice, ['start_date' => '2026-11-02', 'end_date' => '2026-11-17'])->assertOk();
        $this->assertEquals(12, LeaveRequest::first()->days);
        $this->apply($this->alice, ['start_date' => '2026-11-20', 'end_date' => '2026-11-20'])->assertStatus(422)->assertJsonValidationErrors('leave_type_id');

        $b = $this->balance($this->alice);
        $this->assertEquals(0, $b['used']);
        $this->assertEquals(12, $b['pending']);
        $this->assertEquals(0, $b['available']);
    }

    public function test_allowance_changed_to_24_without_code_changes(): void
    {
        $this->actingAs($this->admin)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => 24, 'leave_year_start_month' => 1])->assertOk();

        $this->assertEquals(24, $this->balance($this->alice)['entitlement']);
        // 13 days now fits.
        $this->apply($this->alice, ['start_date' => '2026-11-02', 'end_date' => '2026-11-18'])->assertOk();
        $this->assertEquals(13, LeaveRequest::first()->days);
        $this->assertEquals(11, $this->balance($this->alice)['available']);

        // Invalid values are rejected.
        $this->actingAs($this->admin)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => -1, 'leave_year_start_month' => 1])->assertStatus(422);
        $this->actingAs($this->admin)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => 'abc', 'leave_year_start_month' => 13])->assertStatus(422);
    }

    public function test_policy_change_does_not_rewrite_past_years(): void
    {
        $svc = app(LeaveService::class);
        $svc->balance($this->alice, $this->annual(), 2025); // created with 12
        $this->actingAs($this->admin)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => 24, 'leave_year_start_month' => 1])->assertOk();

        $this->assertEquals(12, LeaveBalance::where(['user_id' => $this->alice->id, 'year' => 2025])->value('allowance'));
        $this->assertEquals(24, $this->balance($this->alice, 2026)['allowance']);
    }

    public function test_approval_deducts_once_and_marks_attendance(): void
    {
        $this->at('2026-10-14 08:00');
        Attendance::create(['user_id' => $this->alice->id, 'date' => '2026-10-13', 'status' => 'absent']);
        $this->apply($this->alice, ['start_date' => '2026-10-13', 'end_date' => '2026-10-15'])->assertOk();
        $leave = LeaveRequest::first();

        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave), ['comment' => 'Enjoy'])->assertOk();
        $leave->refresh();
        $this->assertSame('approved', $leave->status);
        $this->assertSame($this->admin->id, $leave->reviewed_by);
        $this->assertSame('Enjoy', $leave->review_comment);
        $this->assertSame('leave', Attendance::where('user_id', $this->alice->id)->value('status'));

        $b = $this->balance($this->alice);
        $this->assertEquals(3, $b['used']);
        $this->assertEquals(0, $b['pending']);
        $this->assertEquals(9, $b['remaining']);

        // Approving again is refused and doesn't double count.
        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave))->assertStatus(422);
        $this->assertEquals(3, $this->balance($this->alice)['used']);

        // Audit trail + notification.
        $this->assertSame(['submitted', 'approved'], $leave->events()->pluck('action')->all());
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $this->alice->id]);

        // Revoking returns the days and turns leave rows back into absences.
        $this->actingAs($this->admin)->postJson(route('leaves.revoke', $leave), ['comment' => 'Needed'])->assertOk();
        $this->assertEquals(12, $this->balance($this->alice)['remaining']);
        $this->assertSame('absent', Attendance::where('user_id', $this->alice->id)->value('status'));
    }

    public function test_rejection_and_cancellation(): void
    {
        $this->apply($this->alice, ['start_date' => '2026-10-19', 'end_date' => '2026-10-20'])->assertOk();
        $leave = LeaveRequest::first();

        // Employees can't cancel someone else's request or review anything.
        $this->actingAs($this->bob)->postJson(route('leaves.cancel', $leave))->assertStatus(403);
        $this->actingAs($this->bob)->postJson(route('leaves.approve', $leave))->assertStatus(403);
        $this->actingAs($this->bob)->getJson(route('leaves.show', $leave))->assertStatus(403);
        $this->actingAs($this->bob)->get(route('leaves.admin'))->assertStatus(403);
        $this->actingAs($this->bob)->get(route('leaves.policies'))->assertStatus(403);
        $this->actingAs($this->bob)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => 99, 'leave_year_start_month' => 1])->assertStatus(403);

        $this->actingAs($this->admin)->postJson(route('leaves.reject', $leave), ['comment' => 'Release week'])->assertOk();
        $this->assertSame('rejected', $leave->fresh()->status);
        $this->assertEquals(12, $this->balance($this->alice)['available']);

        // A rejected request can't be cancelled or approved.
        $this->actingAs($this->alice)->postJson(route('leaves.cancel', $leave))->assertStatus(422);
        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave))->assertStatus(422);

        // Own pending request can be cancelled, releasing the held days.
        $this->apply($this->alice, ['start_date' => '2026-10-26', 'end_date' => '2026-10-27'])->assertOk();
        $this->assertEquals(10, $this->balance($this->alice)['available']);
        $this->actingAs($this->alice)->postJson(route('leaves.cancel', LeaveRequest::latest('id')->first()))->assertOk();
        $this->assertEquals(12, $this->balance($this->alice)['available']);
    }

    public function test_approvers_by_permission_cannot_approve_their_own_leave(): void
    {
        $this->grant($this->bob, 'can approve leaves');
        $this->apply($this->bob, ['start_date' => '2026-10-19', 'end_date' => '2026-10-19'])->assertOk();
        $this->apply($this->alice, ['start_date' => '2026-10-19', 'end_date' => '2026-10-19'])->assertOk();
        $bobs = LeaveRequest::where('user_id', $this->bob->id)->first();
        $alices = LeaveRequest::where('user_id', $this->alice->id)->first();

        $this->actingAs($this->bob->fresh())->postJson(route('leaves.approve', $bobs))->assertStatus(403);
        $this->actingAs($this->bob->fresh())->postJson(route('leaves.approve', $alices))->assertOk();
        $this->actingAs($this->bob->fresh())->get(route('leaves.admin'))->assertOk();
    }

    public function test_leave_across_year_end_is_split(): void
    {
        $this->at('2026-12-01 10:00');
        // Mon 28 Dec 2026 – Tue 5 Jan 2027: 28,29,30,31 Dec + 1,4,5 Jan = 4 + 3.
        $this->apply($this->alice, ['start_date' => '2026-12-28', 'end_date' => '2027-01-05'])->assertOk();
        $leave = LeaveRequest::first();
        $this->assertEquals(7, $leave->days);
        $this->assertEquals(['2026' => 4, '2027' => 3], $leave->year_days);

        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave))->assertOk();
        $this->assertEquals(4, $this->balance($this->alice, 2026)['used']);
        $this->assertEquals(3, $this->balance($this->alice, 2027)['used']);
        $this->assertEquals(9, $this->balance($this->alice, 2027)['remaining']);
    }

    public function test_year_end_balance_check_is_per_year(): void
    {
        $this->at('2026-12-01 10:00');
        // Use up 2026 (12 days: 2–17 Dec).
        $this->apply($this->alice, ['start_date' => '2026-12-02', 'end_date' => '2026-12-17'])->assertOk();
        // 2026 is full, so even 1 day there fails, while January alone is fine.
        $this->apply($this->alice, ['start_date' => '2026-12-31', 'end_date' => '2027-01-04'])->assertStatus(422);
        $this->apply($this->alice, ['start_date' => '2027-01-04', 'end_date' => '2027-01-05'])->assertOk();
    }

    public function test_leave_year_can_start_in_april(): void
    {
        $this->settings(['leave_year_start_month' => 4]);
        $svc = app(LeaveService::class);
        $this->assertSame(2026, $svc->yearOf('2026-04-01'));
        $this->assertSame(2025, $svc->yearOf('2026-03-31'));
        $this->assertSame('Apr 2026 – Mar 2027', $svc->yearLabel(2026));
    }

    public function test_unlimited_and_negative_allowed_types(): void
    {
        // Sick leave has no limit.
        $this->actingAs($this->alice)->postJson(route('leaves.store'), ['leave_type_id' => $this->sick()->id, 'start_date' => '2026-11-02', 'end_date' => '2026-11-30', 'reason' => 'Surgery recovery'])->assertOk();

        // A capped type that allows going over.
        $type = LeaveType::create(['name' => 'Study', 'code' => 'study', 'allowance_source' => 'fixed', 'days_per_year' => 2, 'allow_negative' => true, 'requires_reason' => false, 'is_active' => true]);
        $this->actingAs($this->bob)->postJson(route('leaves.store'), ['leave_type_id' => $type->id, 'start_date' => '2026-11-02', 'end_date' => '2026-11-04'])->assertOk();
        $this->assertEquals(-1, $this->balance($this->bob, 2026, $type)['available']);

        // Inactive types can't be used.
        $type->update(['is_active' => false]);
        $this->actingAs($this->bob)->postJson(route('leaves.store'), ['leave_type_id' => $type->id, 'start_date' => '2026-11-10', 'end_date' => '2026-11-10'])->assertStatus(422);
    }

    public function test_carry_forward_is_capped(): void
    {
        $this->annual()->update(['carry_forward_max' => 5]);
        $svc = app(LeaveService::class);
        $svc->balance($this->alice, $this->annual()->fresh(), 2025); // 12 days, none used
        $this->assertEquals(5, $svc->balance($this->alice, $this->annual()->fresh(), 2026)->carried_forward);
        $this->assertEquals(17, $this->balance($this->alice)['entitlement']);
    }

    public function test_approval_rechecks_balance_and_adjustments_are_audited(): void
    {
        $this->apply($this->alice, ['start_date' => '2026-11-02', 'end_date' => '2026-11-13'])->assertOk(); // 10 days
        // Admin lowers the allowance afterwards → approval must refuse.
        $this->actingAs($this->admin)->postJson(route('leaves.policies.settings'), ['annual_leave_allowance' => 8, 'leave_year_start_month' => 1])->assertOk();
        $leave = LeaveRequest::first();
        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave))->assertStatus(422);

        // Manual +2 adjustment fixes it.
        $this->actingAs($this->admin)->postJson(route('leaves.balances.adjust'), ['user_id' => $this->alice->id, 'leave_type_id' => $this->annual()->id, 'year' => 2026, 'days' => 2, 'reason' => 'Overtime'])->assertOk();
        $this->assertDatabaseHas('leave_balance_adjustments', ['user_id' => $this->admin->id, 'reason' => 'Overtime']);
        $this->actingAs($this->admin)->postJson(route('leaves.approve', $leave))->assertOk();
        $this->assertEquals(0, $this->balance($this->alice)['remaining']);
    }

    public function test_pages_render_and_detail_is_scoped(): void
    {
        $this->apply($this->alice, ['start_date' => '2026-10-19', 'end_date' => '2026-10-19'])->assertOk();
        $leave = LeaveRequest::first();

        $this->actingAs($this->alice)->get(route('leaves.index'))->assertOk()->assertSee('Annual Leave')->assertSee('12 days left', false);
        $this->actingAs($this->alice)->getJson(route('leaves.show', $leave))->assertOk()->assertJson(['can_cancel' => true, 'can_review' => false]);
        $this->actingAs($this->admin)->get(route('leaves.admin'))->assertOk()->assertSee('Alice Employee');
        $this->actingAs($this->admin)->get(route('leaves.policies'))->assertOk()->assertSee('Annual leave allowance');
        $this->actingAs($this->admin)->getJson(route('leaves.show', $leave))->assertOk()->assertJson(['can_review' => true]);
        $this->actingAs($this->alice)->postJson(route('leaves.preview'), ['leave_type_id' => $this->annual()->id, 'start_date' => '2026-10-19', 'end_date' => '2026-10-23'])
            ->assertOk()->assertJson(['days' => 5])->assertJsonPath('overlap.id', $leave->id);
    }
}
