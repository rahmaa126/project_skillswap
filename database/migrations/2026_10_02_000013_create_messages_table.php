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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('swap_sessions', 'id', 'fk_messages_session')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users', 'id', 'fk_messages_sender')->cascadeOnDelete();
            $table->enum('type', ['text', 'image', 'file', 'system'])->default('text');
            $table->text('content');
            $table->dateTime('read_at')->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->index(['session_id', 'created_at'], 'idx_messages_session_time');
            $table->index('sender_id', 'idx_messages_sender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
