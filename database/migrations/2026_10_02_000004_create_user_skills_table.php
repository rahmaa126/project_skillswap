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
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id', 'fk_user_skills_user')->cascadeOnDelete();
            $table->unsignedInteger('skill_id');
            $table->enum('type', ['offered', 'wanted']);
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['user_id', 'skill_id', 'type'], 'uq_user_skill_type');
            $table->index(['skill_id', 'type'], 'idx_user_skills_skill');
            $table->foreign('skill_id', 'fk_user_skills_skill')->references('id')->on('skills')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
