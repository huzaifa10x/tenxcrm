<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\AttendanceNote;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Services\Attendance\AttendancePolicy;
use App\Services\Attendance\AttendanceReport;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;

class AttendanceTest extends AttendanceLeaveTestCase
{
    /* ----------------------------------------------------------- punch in */

    public function test_on_time_punch_in_is_present_and_duplicate_is_refused(): void
    {
        $this->at('2026-10-12 09:05');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'), ['latitude' => 25.2, 'longitude' => 55.3])
            ->assertOk()->assertJson(['state' => 'working', 'status' => 'present']);

        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))
            ->assertStatus(403)->assertJson(['error' => 'Already punched in!']);

        $this->assertSame(1, Attendance::where('user_id', $this->alice->id)->count());
        $this->assertSame('25.2,55.3', Attendance::first()->location);
    }

    public function test_missing_coordinates_store_no_location(): void
    {
        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();
        $this->assertNull(Attendance::first()->location);
    }

    public function test_late_and_half_day_use_company_settings(): void
    {
        $this->at('2026-10-12 09:16');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertJson(['status' => 'late']);
        $a = Attendance::where('user_id', $this->alice->id)->first();
        $this->assertSame(16, AttendancePolicy::current()->lateMinutes($a));

        $this->at('2026-10-12 12:01');
        $this->actingAs($this->bob)->postJson(route('attendance.punchIn'))->assertJson(['status' => 'half_day']);

        // Inside the grace period = on time.
        $this->at('2026-10-13 09:15');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertJson(['status' => 'present']);

        // Changing the grace period changes the outcome.
        $this->settings(['late_threshold' => 30]);
        $this->at('2026-10-14 09:25');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertJson(['status' => 'present']);
    }

    public function test_early_punch_in_window(): void
    {
        $this->at('2026-10-12 08:40');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertStatus(403);

        $this->at('2026-10-12 08:50');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();
        $this->assertSame('09:00:00', Attendance::first()->punch_in);
    }

    public function test_weekends_holidays_and_custom_working_days(): void
    {
        $this->at('2026-10-17 09:00'); // Saturday
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertStatus(403);

        $this->settings(['holidays' => ['2026-10-13']]);
        $this->at('2026-10-13 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertStatus(403)->assertJson(['error' => 'Today is a holiday!']);

        // Six-day week: Saturday becomes a working day.
        $this->settings(['working_days' => [1, 2, 3, 4, 5, 6], 'holidays' => null]);
        $this->at('2026-10-17 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();
    }

    public function test_null_holidays_setting_does_not_crash(): void
    {
        $this->settings(['holidays' => null]);
        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();
    }

    public function test_cannot_punch_in_on_approved_leave(): void
    {
        LeaveRequest::create(['user_id' => $this->alice->id, 'leave_type_id' => $this->annual()->id, 'start_date' => '2026-10-12', 'end_date' => '2026-10-12', 'days' => 1, 'year_days' => ['2026' => 1], 'status' => 'approved']);
        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertStatus(403)->assertJson(['error' => 'You are on approved leave today.']);
    }

    public function test_punch_in_fills_an_existing_absent_row(): void
    {
        Attendance::create(['user_id' => $this->alice->id, 'date' => '2026-10-12', 'status' => 'absent']);
        $this->at('2026-10-12 09:30');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();
        $this->assertSame(1, Attendance::count());
        $this->assertSame('late', Attendance::first()->status);
    }

    /* ---------------------------------------------------------- punch out */

    public function test_invalid_punch_outs_are_refused(): void
    {
        $this->at('2026-10-12 18:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertStatus(403)->assertJson(['error' => "You haven't punched in today."]);

        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));
        $this->at('2026-10-12 18:05');
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertOk()->assertJson(['state' => 'done', 'total_hours' => '9h 05m']);
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertStatus(403);
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertStatus(403);
    }

    public function test_early_out_keeps_late_status_visible(): void
    {
        $this->at('2026-10-12 09:40');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));
        $this->at('2026-10-12 16:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertJson(['status' => 'late']);
        $a = Attendance::first();
        $this->assertSame(90, AttendancePolicy::current()->earlyOutMinutes($a));

        $this->at('2026-10-13 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));
        $this->at('2026-10-13 17:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertJson(['status' => 'early_out']);
    }

    public function test_overnight_shift_punch_out_next_morning(): void
    {
        $this->at('2026-10-12 17:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();

        $this->at('2026-10-13 02:30');
        $this->actingAs($this->alice)->postJson(route('attendance.punchOut'))->assertOk();

        $a = Attendance::first();
        $this->assertSame('2026-10-12', $a->dateString());
        $this->assertSame('09:30:00', $a->total_hours);
        $this->assertSame(570, AttendancePolicy::current()->workedMinutes($a));
        $this->assertSame(0, AttendancePolicy::current()->earlyOutMinutes($a));
    }

    public function test_forgotten_punch_out_is_closed_when_next_day_starts(): void
    {
        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));

        // Before the next punch-in window (08:45) the record is still an open overnight shift.
        $this->at('2026-10-13 08:30');
        $this->assertNotNull(app(AttendanceService::class)->openRecord($this->alice, now()));

        // From 08:45 it's a missed punch-out: punching in today closes it at office closing time.
        $this->at('2026-10-13 08:55');
        $this->assertNull(app(AttendanceService::class)->openRecord($this->alice, now()));
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'))->assertOk();

        $monday = Attendance::where('user_id', $this->alice->id)->whereDate('date', '2026-10-12')->first();
        $this->assertTrue($monday->auto_closed);
        $this->assertSame('18:00:00', $monday->punch_out);
        $this->assertSame(2, Attendance::where('user_id', $this->alice->id)->count());
    }

    /* --------------------------------------------------------- daily close */

    public function test_close_day_handles_missed_punch_outs_absences_and_leave(): void
    {
        $system = User::factory()->create(['email' => 'system@10xdigital.ae']);
        $system->forceFill(['track_attendance' => false])->save();
        $carol = $this->person('Carol', 'DEVELOPMENT', '2025-01-01');
        LeaveRequest::create(['user_id' => $carol->id, 'leave_type_id' => $this->annual()->id, 'start_date' => '2026-10-12', 'end_date' => '2026-10-12', 'days' => 1, 'year_days' => ['2026' => 1], 'status' => 'approved']);

        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));

        // Same evening: nothing is closed yet (overnight work possible).
        $this->at('2026-10-12 23:00');
        $this->assertSame(0, app(AttendanceService::class)->closeThrough());

        // Next day after noon: Monday is closed.
        $this->at('2026-10-13 12:30');
        app(AttendanceService::class)->closeThrough();

        $alice = Attendance::where('user_id', $this->alice->id)->first();
        $this->assertSame('18:00:00', $alice->punch_out);
        $this->assertTrue($alice->auto_closed);
        $this->assertSame('09:00:00', $alice->total_hours);

        $this->assertSame('absent', Attendance::where('user_id', $this->bob->id)->value('status'));
        $this->assertSame('leave', Attendance::where('user_id', $carol->id)->value('status'));
        $this->assertSame(0, Attendance::where('user_id', $system->id)->count());

        // Idempotent.
        $before = Attendance::count();
        app(AttendanceService::class)->closeDay('2026-10-12');
        $this->assertSame($before, Attendance::count());
    }

    public function test_close_skips_weekends_and_people_who_had_not_joined(): void
    {
        $newbie = $this->person('Newbie', 'DEVELOPMENT', '2026-10-20');
        app(AttendanceService::class)->closeDay('2026-10-17'); // Saturday
        app(AttendanceService::class)->closeDay('2026-10-16'); // Friday, before Newbie joined
        $this->assertSame(0, Attendance::where('date', '2026-10-17')->count());
        $this->assertSame(0, Attendance::where('user_id', $newbie->id)->count());
        $this->assertSame(3, Attendance::where('date', '2026-10-16')->count());
    }

    public function test_dashboard_no_longer_closes_todays_open_records(): void
    {
        $this->at('2026-10-12 09:00');
        $this->actingAs($this->alice)->postJson(route('attendance.punchIn'));
        $this->at('2026-10-12 19:00');
        app(AttendanceService::class)->closeThrough();
        $this->assertNull(Attendance::first()->punch_out, 'Still working after closing time must stay open.');
    }

    /* -------------------------------------------------------------- report */

    public function test_monthly_report_numbers(): void
    {
        $this->settings(['holidays' => ['2026-10-07']]); // a Wednesday
        $rec = fn ($date, $in, $out, $status) => Attendance::create(['user_id' => $this->alice->id, 'date' => $date, 'punch_in' => $in, 'punch_out' => $out, 'status' => $status,
            'total_hours' => $in && $out ? AttendanceService::duration(Carbon::parse("$date $in"), Carbon::parse("$date $out")) : null]);

        $rec('2026-10-01', '09:00:00', '18:00:00', 'present');
        $rec('2026-10-02', '09:30:00', '18:00:00', 'late');      // 30 min late
        $rec('2026-10-05', '12:30:00', '18:00:00', 'half_day');
        $rec('2026-10-06', '09:00:00', '17:00:00', 'early_out'); // 30 min early
        Attendance::create(['user_id' => $this->alice->id, 'date' => '2026-10-08', 'punch_in' => '09:00:00', 'punch_out' => '18:00:00', 'total_hours' => '09:00:00', 'status' => 'present', 'auto_closed' => true]);
        LeaveRequest::create(['user_id' => $this->alice->id, 'leave_type_id' => $this->annual()->id, 'start_date' => '2026-10-09', 'end_date' => '2026-10-09', 'days' => 1, 'year_days' => ['2026' => 1], 'status' => 'approved']);
        // 2026-10-12 (Mon): no record → absent. Today is 2026-10-13 morning.
        $this->at('2026-10-13 08:00');

        $r = app(AttendanceReport::class)->build(collect([$this->alice]), Carbon::parse('2026-10-01'), Carbon::parse('2026-10-31'));
        $s = $r['rows'][0]['summary'];

        $this->assertSame(21, $r['totals']['working_days']); // 22 weekdays − 1 holiday
        $this->assertSame(21, $s['working_days']);
        $this->assertSame(7, $s['elapsed_days']);            // 1,2,5,6,8,9,12
        $this->assertSame(5, $s['present']);
        $this->assertSame(1, $s['absent']);
        $this->assertSame(1, $s['late']);
        $this->assertSame(30, $s['late_minutes']);
        $this->assertSame(1, $s['half_days']);
        $this->assertSame(1, $s['early_outs']);
        $this->assertSame(1, $s['missed_punch_outs']);
        $this->assertSame(1, $s['leave_days']);
        $this->assertSame(1, $s['holidays']);
        // (5 − 0.5) / (7 − 1) = 75%
        $this->assertEquals(75.0, $s['attendance_pct']);
        // avg over complete days (not auto-closed): 540 + 510 + 330 + 480 = 1860 / 4
        $this->assertSame(465, $s['avg_minutes']);
    }

    /* --------------------------------------------------------- permissions */

    public function test_employee_only_sees_own_attendance(): void
    {
        Attendance::create(['user_id' => $this->bob->id, 'date' => '2026-10-12', 'punch_in' => '09:00:00', 'status' => 'present']);
        $this->at('2026-10-12 10:00');

        $this->actingAs($this->alice)->get(route('attendance.index', ['user_id' => $this->bob->id]))
            ->assertOk()->assertSee('My attendance')->assertDontSee('Bob Employee');

        $bobRecord = Attendance::where('user_id', $this->bob->id)->first();
        $this->actingAs($this->alice)->getJson(route('attendance.records.show', $bobRecord))->assertStatus(403);
        $this->actingAs($this->alice)->getJson(route('attendance.notes.index', $bobRecord->id))->assertStatus(403);
        $this->actingAs($this->alice)->postJson(route('attendance.notes.store', $bobRecord->id), ['note' => 'x'])->assertStatus(403);
        $this->actingAs($this->alice)->putJson(route('attendance.records.update', $bobRecord), ['reason' => 'hack', 'punch_in' => '08:00'])->assertStatus(403);
        $this->actingAs($this->alice)->getJson(route('admin.attendance.filter'))->assertStatus(403);
        $this->actingAs($this->alice)->get(route('attendance.performance'))->assertStatus(403);
        $this->actingAs($this->alice)->postJson(route('settings.update'), [])->assertStatus(403);

        // Mobile API: employees get only themselves, and valid JSON (no debug output).
        $res = $this->actingAs($this->alice)->getJson(route('attendance.monthly', ['user_id' => $this->bob->id, 'month' => '2026-10']))->assertOk();
        $this->assertSame([$this->alice->id], collect($res->json('users'))->pluck('id')->all());
    }

    public function test_admin_sees_team_and_can_correct_with_audit_note(): void
    {
        $this->at('2026-10-13 10:00');
        $a = Attendance::create(['user_id' => $this->bob->id, 'date' => '2026-10-12', 'status' => 'absent']);

        $this->actingAs($this->admin)->get(route('attendance.index'))->assertOk()->assertSee('Team attendance')->assertSee('Bob Employee');
        $this->actingAs($this->admin)->get(route('attendance.index', ['user_id' => $this->bob->id]))->assertOk()->assertSee('Bob Employee');
        $this->actingAs($this->admin)->get(route('attendance.performance'))->assertOk()->assertSee('Alice Employee');

        $this->actingAs($this->admin)->putJson(route('attendance.records.update', $a), ['punch_in' => '09:20', 'punch_out' => '18:00', 'reason' => 'Forgot phone'])
            ->assertOk();
        $a->refresh();
        $this->assertSame('late', $a->status);
        $this->assertSame('08:40:00', $a->total_hours);
        $this->assertSame($this->admin->id, $a->edited_by);
        $this->assertStringContainsString('Forgot phone', AttendanceNote::where('attendance_id', $a->id)->value('note'));

        // Reason is mandatory.
        $this->actingAs($this->admin)->putJson(route('attendance.records.update', $a), ['punch_in' => '09:00'])->assertStatus(422);

        // A permission (not only the ADMIN role) grants management.
        $this->grant($this->alice, 'can manage attendance');
        $this->actingAs($this->alice->fresh())->get(route('attendance.index'))->assertOk()->assertSee('Team attendance');
    }

    public function test_admin_can_add_a_record_for_a_missing_day(): void
    {
        $this->at('2026-10-13 10:00');
        $this->actingAs($this->admin)->postJson(route('attendance.records.store'), ['user_id' => $this->bob->id, 'date' => '2026-10-12', 'punch_in' => '09:00', 'punch_out' => '17:45', 'reason' => 'Paper sign-in'])->assertOk();
        $this->assertSame('present', Attendance::where('user_id', $this->bob->id)->value('status'));

        $this->actingAs($this->admin)->postJson(route('attendance.records.store'), ['user_id' => $this->bob->id, 'date' => '2026-12-01', 'reason' => 'future'])->assertStatus(422);
    }

    public function test_pages_render(): void
    {
        $this->at('2026-10-12 10:00');
        $this->actingAs($this->alice)->get(route('attendance.index'))->assertOk();
        $this->actingAs($this->alice)->get(route('admin.attendance'))->assertRedirect();
        $this->actingAs($this->admin)->get(route('attendance.monthly', ['view' => 'classic']))->assertOk();
        $this->actingAs($this->admin)->get(route('attendance.export'))->assertOk();
        $this->actingAs($this->admin)->get(route('settings.index'))->assertOk()->assertSee('Working days');
        $this->actingAs($this->alice)->get(route('settings.index'))->assertOk()->assertDontSee('Working days');

        // Dashboard widgets.
        $this->actingAs($this->admin);
        $this->blade('<x-dashboards.admin-dashboard />')->assertSee('Attendance performance');
        $this->blade('<x-dashboards.punch-in-out />')->assertSee('Punch');
    }

    public function test_settings_validation_and_save(): void
    {
        $payload = ['office_open_time' => '09:00', 'office_close_time' => '18:00', 'late_threshold' => 10, 'late_deduction_threshold' => 3, 'half_day_deduction_threshold' => 2,
            'half_day_threshold' => '12:30', 'break_end_time' => '14:00', 'early_out_threshold' => '17:30', 'working_days' => [1, 2, 3, 4, 5, 6], 'holidays' => '2026-12-02, 2026-12-03'];

        $this->actingAs($this->admin)->postJson(route('settings.update'), ['holidays' => 'bad-date'] + $payload)->assertStatus(422);
        $this->actingAs($this->admin)->postJson(route('settings.update'), ['office_close_time' => '08:00'] + $payload)->assertStatus(422);
        $this->actingAs($this->admin)->postJson(route('settings.update'), $payload)->assertOk();

        $p = AttendancePolicy::refresh();
        $this->assertSame([1, 2, 3, 4, 5, 6], $p->workingDays);
        $this->assertSame(['2026-12-02', '2026-12-03'], $p->holidays);
        $this->assertSame(10, $p->graceMinutes);
    }
}
