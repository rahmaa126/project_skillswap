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
        Schema::create('user_skill_scores', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users', 'id', 'fk_scores_user')->cascadeOnDelete();
            $table->unsignedInteger('skill_id');
            $table->integer('score')->default(0);
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['user_id', 'skill_id']);
            $table->index(['skill_id', 'score'], 'idx_scores_skill_score');
            $table->foreign('skill_id', 'fk_scores_skill')->references('id')->on('skills')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skill_scores');
    }
};
