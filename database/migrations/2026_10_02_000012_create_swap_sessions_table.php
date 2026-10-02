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
        Schema::create('swap_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->nullable()->constrained('matches', 'id', 'fk_swap_match')->nullOnDelete();
            $table->foreignId('requester_id')->constrained('users', 'id', 'fk_swap_requester')->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained('users', 'id', 'fk_swap_partner')->cascadeOnDelete();
            $table->unsignedInteger('requester_skill_id');
            $table->unsignedInteger('partner_skill_id');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'ongoing', 'completed', 'cancelled'])->default('pending');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['requester_id', 'status'], 'idx_swap_requester');
            $table->index(['partner_id', 'status'], 'idx_swap_partner');
            $table->foreign('requester_skill_id', 'fk_swap_requester_skill')->references('id')->on('skills');
            $table->foreign('partner_skill_id', 'fk_swap_partner_skill')->references('id')->on('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('swap_sessions');
    }
};
