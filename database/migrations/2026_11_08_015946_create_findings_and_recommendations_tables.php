<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('audit_findings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained('audit_assignments')->cascadeOnDelete();
        $table->string('finding_number', 50);
        $table->string('title');
        $table->text('condition');
        $table->text('criteria');
        $table->text('cause');
        $table->text('effect');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('finding_responses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('finding_id')->constrained('audit_findings')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->text('response');
        $table->enum('status', ['submitted', 'accepted', 'need_clarification'])->default('submitted');
        $table->timestamps();
    });

    Schema::create('recommendations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('finding_id')->constrained('audit_findings')->cascadeOnDelete();
        $table->string('rec_code', 50)->nullable();
        $table->text('recommendation_text');
        $table->date('target_date')->nullable();
        $table->enum('status', ['open', 'in_progress', 'under_review', 'closed'])->default('open');
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings_and_recommendations_tables');
    }
};
