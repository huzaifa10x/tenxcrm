<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 30)->unique();
            $table->string('color', 9)->default('#4f7a06');
            // 'annual'  = uses general_settings.annual_leave_allowance
            // 'fixed'   = uses days_per_year below
            // 'none'    = no limit (tracked, never blocks)
            $table->string('allowance_source', 10)->default('fixed');
            $table->decimal('days_per_year', 5, 1)->nullable();
            $table->decimal('carry_forward_max', 5, 1)->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('allow_negative')->default(false);
            $table->boolean('requires_reason')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        // Per person, per leave type, per leave year. The allowance is copied in when the
        // year's balance is first created, so later policy changes never rewrite past years.
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->decimal('allowance', 5, 1)->nullable();      // null = unlimited
            $table->decimal('carried_forward', 5, 1)->default(0);
            $table->decimal('adjustment', 5, 1)->default(0);       // manual +/- by admins
            $table->timestamps();
            $table->unique(['user_id', 'leave_type_id', 'year']);
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days', 5, 1);                         // working days counted
            // Working days per leave year, fixed at submission: {"2026": 3, "2027": 2}.
            // Balances read this, so later holiday/working-day changes never rewrite history.
            $table->json('year_days')->nullable();
            $table->text('reason')->nullable();
            $table->string('status', 12)->default('pending');      // pending|approved|rejected|cancelled
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_comment')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });

        Schema::create('leave_request_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 20);                          // submitted|approved|rejected|cancelled|revoked
            $table->text('comment')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('leave_balance_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_balance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // admin who adjusted
            $table->decimal('days', 5, 1);
            $table->string('reason');
            $table->timestamp('created_at')->useCurrent();
        });

        $now = now();
        DB::table('leave_types')->insert([
            ['name' => 'Annual Leave', 'code' => 'annual', 'color' => '#4f7a06', 'allowance_source' => 'annual', 'days_per_year' => null, 'carry_forward_max' => 0, 'is_paid' => true, 'allow_negative' => false, 'requires_reason' => false, 'is_active' => true, 'sort' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sick Leave', 'code' => 'sick', 'color' => '#c2410c', 'allowance_source' => 'none', 'days_per_year' => null, 'carry_forward_max' => 0, 'is_paid' => true, 'allow_negative' => false, 'requires_reason' => true, 'is_active' => true, 'sort' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Unpaid Leave', 'code' => 'unpaid', 'color' => '#6b776f', 'allowance_source' => 'none', 'days_per_year' => null, 'carry_forward_max' => 0, 'is_paid' => false, 'allow_negative' => false, 'requires_reason' => true, 'is_active' => true, 'sort' => 3, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Permissions (granted to the ADMIN role; admins can grant them to others in Roles).
        foreach (['can manage attendance', 'can approve leaves', 'can manage leave policies'] as $name) {
            $id = DB::table('permissions')->where('name', $name)->value('id')
                ?? DB::table('permissions')->insertGetId(['name' => $name, 'created_at' => $now, 'updated_at' => $now]);

            $adminRole = DB::table('roles')->where('name', 'ADMIN')->value('id');
            if ($adminRole && ! DB::table('permission_role')->where(['permission_id' => $id, 'role_id' => $adminRole])->exists()) {
                DB::table('permission_role')->insert(['permission_id' => $id, 'role_id' => $adminRole, 'created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_balance_adjustments');
        Schema::dropIfExists('leave_request_events');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_balances');
        Schema::dropIfExists('leave_types');
        DB::table('permissions')->whereIn('name', ['can manage attendance', 'can approve leaves', 'can manage leave policies'])->delete();
    }
};
