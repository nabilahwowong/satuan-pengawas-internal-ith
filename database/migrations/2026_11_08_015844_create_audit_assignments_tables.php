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
    Schema::create('audit_assignments', function (Blueprint $table) {
        $table->id();
        $table->string('assignment_number', 100)->unique();
        $table->string('title');
        $table->text('scope')->nullable();
        $table->foreignId('work_unit_id')->constrained('work_units')->cascadeOnDelete();
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('status', [
            'draft',
            'ongoing',
            'draft_lhp',
            'lhp_review',
            'lhp_approved',
            'in_monitoring',
            'completed'
        ])->default('draft');
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('audit_assignment_members', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained('audit_assignments')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->enum('role_in_team', ['ketua_tim', 'anggota'])->default('anggota');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_assignments_tables');
    }
};
