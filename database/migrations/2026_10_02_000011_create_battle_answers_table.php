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
        Schema::create('battle_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battle_id')->constrained('battles', 'id', 'fk_answers_battle')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('battle_questions', 'id', 'fk_answers_question')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id', 'fk_answers_user')->cascadeOnDelete();
            $table->smallInteger('chosen_option')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points_earned')->default(0);
            $table->dateTime('answered_at')->useCurrent();

            $table->unique(['battle_id', 'question_id', 'user_id'], 'uq_battle_question_user');
            $table->index('question_id', 'idx_answers_question');
            $table->index('user_id', 'idx_answers_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battle_answers');
    }
};
