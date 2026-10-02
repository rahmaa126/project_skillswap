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
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('skill_id');
            $table->foreignId('match_id')->nullable()->constrained('matches', 'id', 'fk_battles_match')->nullOnDelete();
            $table->foreignId('player1_id')->constrained('users', 'id', 'fk_battles_p1')->cascadeOnDelete();
            $table->foreignId('player2_id')->constrained('users', 'id', 'fk_battles_p2')->cascadeOnDelete();
            $table->foreignId('winner_id')->nullable()->constrained('users', 'id', 'fk_battles_winner')->nullOnDelete();
            $table->enum('status', ['pending', 'ongoing', 'finished', 'cancelled'])->default('pending');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['player1_id', 'player2_id'], 'idx_battles_players');
            $table->index('player2_id', 'idx_battles_player2');
            $table->foreign('skill_id', 'fk_battles_skill')->references('id')->on('skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};
