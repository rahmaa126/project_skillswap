<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('battle_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('skill_id');
            $table->text('question');
            $table->json('options');
            $table->smallInteger('correct_option');
            $table->integer('points')->default(10);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->text('explanation')->nullable();
            $table->enum('source', ['manual', 'ai'])->default('manual');
            $table->enum('status', ['draft', 'approved', 'rejected'])->default('approved');
            $table->string('ai_model', 100)->nullable();
            $table->foreignId('generation_log_id')->nullable()->constrained('ai_logs', 'id', 'fk_questions_ai_log')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users', 'id', 'fk_questions_reviewed')->nullOnDelete();
            $table->dateTime('reviewed_at')->nullable();
            $table->char('question_hash', 64)->storedAs('SHA2(question, 256)');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id', 'fk_questions_created_by')->nullOnDelete();
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['skill_id', 'question_hash'], 'uq_question_per_skill');
            $table->index(['skill_id', 'status', 'difficulty'], 'idx_questions_pick');
            $table->foreign('skill_id', 'fk_questions_skill')->references('id')->on('skills')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE battle_questions ADD CONSTRAINT chk_correct_option CHECK (correct_option >= 0)');
        DB::statement("ALTER TABLE battle_questions ADD CONSTRAINT chk_options_array CHECK (JSON_TYPE(options) = 'ARRAY' AND JSON_LENGTH(options) >= 2)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battle_questions');
    }
};
