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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('swap_sessions', 'id', 'fk_reviews_session')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users', 'id', 'fk_reviews_reviewer')->cascadeOnDelete();
            $table->foreignId('reviewee_id')->constrained('users', 'id', 'fk_reviews_reviewee')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->dateTime('created_at')->useCurrent();

            $table->unique(['session_id', 'reviewer_id'], 'uq_review_per_session');
            $table->index('reviewee_id', 'idx_reviews_reviewee');
        });

        DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
