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
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->string('slug')->unique();
        $table->enum('category', ['berita', 'program_kerja', 'zona_integritas', 'profil_spi']);
        $table->longText('content');
        $table->string('featured_image')->nullable();
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });

    Schema::create('activity_schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->string('location')->nullable();
        $table->timestamps();
    });

    Schema::create('surveys', function (Blueprint $table) {
        $table->id();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });

    Schema::create('survey_questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
        $table->text('question_text');
        $table->enum('question_type', ['rating_5', 'text', 'boolean'])->default('rating_5');
        $table->integer('order_num')->default(0);
        $table->timestamps();
    });

    Schema::create('survey_responses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
        $table->string('respondent_name')->nullable();
        $table->string('respondent_work_unit')->nullable();
        $table->string('ip_address', 45)->nullable();
        $table->timestamps();
    });

    Schema::create('survey_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('response_id')->constrained('survey_responses')->cascadeOnDelete();
        $table->foreignId('question_id')->constrained('survey_questions')->cascadeOnDelete();
        $table->text('answer_value');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_and_survey_tables');
    }
};
