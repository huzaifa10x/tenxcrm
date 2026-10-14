<?php

namespace Tests\Feature;

use App\Models\GeneralSetting;
use App\Models\LeaveType;
use App\Models\Role;
use App\Models\User;
use App\Services\Attendance\AttendancePolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Runs inside a transaction that is rolled back after each test, on the *_test database
 * (a schema copy of the app database; see tests/README.md).
 *
 * Shared fixtures: company settings (Mon–Fri, 09:00–18:00, 15 min grace, half day after 12:00,
 * early out before 17:30), an ADMIN and two employees.
 */
abstract class AttendanceLeaveTestCase extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;

    protected User $alice;

    protected User $bob;

    protected function setUp(): void
    {
        parent::setUp();

        $this->at('2026-10-12 08:00'); // Monday

        GeneralSetting::query()->delete();
        GeneralSetting::create([
            'office_open_time' => '09:00:00',
            'office_close_time' => '18:00:00',
            'late_threshold' => 15,
            'half_day_threshold' => '12:00:00',
            'early_out_threshold' => '17:30:00',
            'break_end_time' => '14:00:00',
            'late_deduction_threshold' => 3,
            'half_day_deduction_threshold' => 2,
            'holidays' => [],
            'working_days' => [1, 2, 3, 4, 5],
            'annual_leave_allowance' => 12,
            'leave_year_start_month' => 1,
            'early_punch_in_minutes' => 15,
        ]);
        AttendancePolicy::refresh();

        // Reference data normally inserted by the leave migration.
        foreach ([
            ['name' => 'Annual Leave', 'code' => 'annual', 'allowance_source' => 'annual', 'requires_reason' => false, 'is_paid' => true, 'sort' => 1],
            ['name' => 'Sick Leave', 'code' => 'sick', 'allowance_source' => 'none', 'requires_reason' => true, 'is_paid' => true, 'sort' => 2],
            ['name' => 'Unpaid Leave', 'code' => 'unpaid', 'allowance_source' => 'none', 'requires_reason' => true, 'is_paid' => false, 'sort' => 3],
        ] as $t) {
            LeaveType::updateOrCreate(['code' => $t['code']], $t + ['is_active' => true, 'allow_negative' => false, 'carry_forward_max' => 0, 'days_per_year' => null]);
        }
        foreach (['can manage attendance', 'can approve leaves', 'can manage leave policies'] as $perm) {
            DB::table('permissions')->updateOrInsert(['name' => $perm]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'ADMIN']);
        foreach (['can manage attendance', 'can approve leaves', 'can manage leave policies'] as $perm) {
            $pid = DB::table('permissions')->where('name', $perm)->value('id');
            DB::table('permission_role')->updateOrInsert(['permission_id' => $pid, 'role_id' => $adminRole->id]);
        }
        Role::firstOrCreate(['name' => 'DEVELOPMENT']);

        $this->admin = $this->person('Admin User', 'ADMIN', '2025-01-01');
        $this->alice = $this->person('Alice Employee', 'DEVELOPMENT', '2025-01-01');
        $this->bob = $this->person('Bob Employee', 'DEVELOPMENT', '2025-01-01');
    }

    protected function person(string $name, string $role, string $joined): User
    {
        $u = User::factory()->create(['name' => $name]);
        $u->forceFill(['created_at' => $joined])->save();
        $u->roles()->attach(Role::where('name', $role)->value('id'));

        return $u->fresh();
    }

    /** Move the clock. */
    protected function at(string $datetime): void
    {
        Carbon::setTestNow(Carbon::parse($datetime, 'Asia/Dubai'));
    }

    protected function settings(array $changes): void
    {
        GeneralSetting::first()->update($changes);
        AttendancePolicy::refresh();
    }

    protected function annual(): LeaveType
    {
        return LeaveType::where('code', 'annual')->firstOrFail();
    }

    protected function sick(): LeaveType
    {
        return LeaveType::where('code', 'sick')->firstOrFail();
    }

    protected function grant(User $user, string $permission): void
    {
        $role = Role::firstOrCreate(['name' => 'ROLE '.$permission]);
        $pid = DB::table('permissions')->where('name', $permission)->value('id');
        DB::table('permission_role')->updateOrInsert(['permission_id' => $pid, 'role_id' => $role->id]);
        $user->roles()->attach($role->id);
        $user->unsetRelation('roles');
    }
}
