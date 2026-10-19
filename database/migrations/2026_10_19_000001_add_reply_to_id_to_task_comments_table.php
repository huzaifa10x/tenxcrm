<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Lets a comment quote-reply to another comment on the same task. */
    public function up(): void
    {
        if (Schema::hasColumn('task_comments', 'reply_to_id')) {
            return;
        }

        Schema::table('task_comments', function (Blueprint $table) {
            $table->foreignId('reply_to_id')->nullable()->after('user_id')
                ->constrained('task_comments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('task_comments', 'reply_to_id')) {
            return;
        }

        Schema::table('task_comments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reply_to_id');
        });
    }
};
