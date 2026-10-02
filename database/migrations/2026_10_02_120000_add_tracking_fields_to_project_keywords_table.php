<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Link-building records already live in project_keywords (type = closing).
     * This only adds what the Offsite SEO dashboard needs on top of that:
     * who built the link, and three basic quality fields.
     * Each column is skipped if it already exists.
     */
    public function up(): void
    {
        Schema::table('project_keywords', function (Blueprint $table) {
            if (!Schema::hasColumn('project_keywords', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->index();
            }
            if (!Schema::hasColumn('project_keywords', 'rel')) {
                $table->string('rel', 12)->nullable();          // dofollow | nofollow
            }
            if (!Schema::hasColumn('project_keywords', 'dr')) {
                $table->unsignedTinyInteger('dr')->nullable();  // 0-100
            }
            if (!Schema::hasColumn('project_keywords', 'link_status')) {
                $table->string('link_status', 12)->nullable();  // live | pending | lost
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_keywords', function (Blueprint $table) {
            foreach (['created_by', 'rel', 'dr', 'link_status'] as $column) {
                if (Schema::hasColumn('project_keywords', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
