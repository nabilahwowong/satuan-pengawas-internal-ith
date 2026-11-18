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
    Schema::create('document_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('assignment_id')->constrained('audit_assignments')->cascadeOnDelete();
        $table->string('document_name');
        $table->text('description')->nullable();
        $table->date('deadline');
        $table->enum('status', ['requested', 'fulfilled', 'revision_needed'])->default('requested');
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('document_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('document_request_id')->constrained('document_requests')->cascadeOnDelete();
        $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
        $table->string('file_path');
        $table->string('file_name');
        $table->text('remarks')->nullable();
        $table->enum('verification_status', ['pending', 'accepted', 'rejected'])->default('pending');
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_requests_tables');
    }
};
