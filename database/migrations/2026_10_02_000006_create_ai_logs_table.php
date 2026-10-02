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
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('feature', ['matching', 'question_generation']);
            $table->foreignId('user_id')->nullable()->constrained('users', 'id', 'fk_ai_logs_user')->nullOnDelete();
            $table->unsignedInteger('skill_id')->nullable();
            $table->string('model', 100);
            $table->unsignedInteger('prompt_tokens')->default(0);
            $table->unsignedInteger('completion_tokens')->default(0);
            $table->enum('status', ['success', 'failed'])->default('success');
            $table->text('error_message')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['feature', 'created_at'], 'idx_ai_logs_feature_time');
            $table->index('user_id', 'idx_ai_logs_user');
            $table->foreign('skill_id', 'fk_ai_logs_skill')->references('id')->on('skills')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
