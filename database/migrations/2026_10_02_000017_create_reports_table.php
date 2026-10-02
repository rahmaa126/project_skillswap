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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users', 'id', 'fk_reports_reporter')->cascadeOnDelete();
            $table->foreignId('reported_id')->constrained('users', 'id', 'fk_reports_reported')->cascadeOnDelete();
            $table->foreignId('session_id')->nullable()->constrained('swap_sessions', 'id', 'fk_reports_session')->nullOnDelete();
            $table->text('reason');
            $table->enum('status', ['open', 'resolved', 'dismissed'])->default('open');
            $table->foreignId('handled_by')->nullable()->constrained('users', 'id', 'fk_reports_handler')->nullOnDelete();
            $table->dateTime('created_at')->useCurrent();

            $table->index('status', 'idx_reports_status');
            $table->index('reporter_id', 'idx_reports_reporter');
            $table->index('reported_id', 'idx_reports_reported');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
