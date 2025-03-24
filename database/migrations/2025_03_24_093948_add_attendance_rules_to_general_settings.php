<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->time('half_day_threshold')->nullable()->after('office_open_time'); // Late after this = Half Day
            $table->time('break_end_time')->nullable()->after('half_day_threshold'); // Break end time
            $table->time('early_out_threshold')->nullable()->after('break_end_time'); // Punch out before this = Early Out
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            //
        });
    }
};
