<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * Attendance & leave settings live on the existing general_settings row; a few audit
 * fields are added to attendances. Existing data is left untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // ISO weekdays that are working days (1 = Monday … 7 = Sunday).
            $table->json('working_days')->nullable()->after('holidays');
            $table->unsignedSmallInteger('annual_leave_allowance')->default(12)->after('working_days');
            $table->unsignedTinyInteger('leave_year_start_month')->default(1)->after('annual_leave_allowance');
            $table->unsignedSmallInteger('early_punch_in_minutes')->default(15)->after('leave_year_start_month');
        });

        Schema::table('attendances', function (Blueprint $table) {
            // True when the system closed a missed punch-out at office closing time.
            $table->boolean('auto_closed')->default(false)->after('status');
            $table->foreignId('edited_by')->nullable()->after('auto_closed');
            $table->timestamp('edited_at')->nullable()->after('edited_by');
            $table->index(['date', 'status']);
        });

        Schema::table('users', function (Blueprint $table) {
            // Untick for people who should not appear in attendance (system accounts, ex-staff).
            $table->boolean('track_attendance')->default(true)->after('img');
        });

        // The system account never punches in.
        DB::table('users')->where('email', 'system@10xdigital.ae')->update(['track_attendance' => false]);

        // One record per person per day. Only added when the existing data allows it,
        // so this never fails or deletes anything on a database that already has duplicates.
        $duplicates = DB::table('attendances')
            ->select('user_id', 'date')
            ->groupBy('user_id', 'date')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        if ($duplicates === 0) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->unique(['user_id', 'date'], 'attendances_user_date_unique');
            });
        } else {
            Log::warning("attendances has {$duplicates} duplicate user/date pairs; unique index not added. Remove the duplicates, then run: php artisan attendance:add-unique-index");
        }
    }

    public function down(): void
    {
        if (collect(Schema::getIndexes('attendances'))->contains(fn ($i) => $i['name'] === 'attendances_user_date_unique')) {
            Schema::table('attendances', fn (Blueprint $table) => $table->dropUnique('attendances_user_date_unique'));
        }
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['date', 'status']);
            $table->dropColumn(['auto_closed', 'edited_by', 'edited_at']);
        });
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['working_days', 'annual_leave_allowance', 'leave_year_start_month', 'early_punch_in_minutes']);
        });
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('track_attendance'));
    }
};
