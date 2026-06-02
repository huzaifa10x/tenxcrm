<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the original Pipedrive record ID to each CRM table so an import can
 * link rows across the deal / person / note sheets, and so re-importing the
 * same export updates existing rows instead of duplicating them.
 */
return new class extends Migration
{
    private array $tables = ['crm_deals', 'crm_persons', 'crm_organizations', 'pipedrive_notes'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (! Schema::hasColumn($table, 'pipedrive_id')) {
                    $t->unsignedBigInteger('pipedrive_id')->nullable()->after('id')->index();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                if (Schema::hasColumn($table, 'pipedrive_id')) {
                    $t->dropColumn('pipedrive_id');
                }
            });
        }
    }
};