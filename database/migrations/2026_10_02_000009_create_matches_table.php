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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_a_id')->constrained('users', 'id', 'fk_matches_user_a')->cascadeOnDelete();
            $table->foreignId('user_b_id')->constrained('users', 'id', 'fk_matches_user_b')->cascadeOnDelete();
            $table->unsignedInteger('skill_a_id');
            $table->unsignedInteger('skill_b_id');
            $table->decimal('match_score', 5, 2)->default(0);
            $table->enum('status', ['suggested', 'accepted', 'rejected', 'expired'])->default('suggested');
            $table->enum('match_source', ['rule_based', 'ai_semantic'])->default('rule_based');
            $table->json('score_breakdown')->nullable();
            $table->text('ai_reason')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['user_a_id', 'user_b_id', 'skill_a_id', 'skill_b_id'], 'uq_match_pair');
            $table->index(['user_a_id', 'user_b_id'], 'idx_matches_users');
            $table->index('user_b_id', 'idx_matches_b');
            $table->foreign('skill_a_id', 'fk_matches_skill_a')->references('id')->on('skills');
            $table->foreign('skill_b_id', 'fk_matches_skill_b')->references('id')->on('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
