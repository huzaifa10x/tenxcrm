<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->enum('category', ['PLATINUM', 'GOLD', 'SILVER']);
            $table->foreignId('industry_id')->constrained('industries')->onDelete('cascade');
            $table->enum('status', ['Current', 'Paused', 'WFF', 'Expired', 'Debt Collection', 'Cancelled']);
            $table->foreignId('sales_manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('account_manager_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tech_manager_id')->constrained('users')->onDelete('cascade');
            $table->date('contract_start_date');
            $table->integer('contract_term');
            $table->float('contract_value');
            $table->float('free_months');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->text('handover_notes');
            $table->enum('project_type', ['SEO', 'Google Ads', 'Social Media', 'Web Development', 'Hosting', 'Other']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
