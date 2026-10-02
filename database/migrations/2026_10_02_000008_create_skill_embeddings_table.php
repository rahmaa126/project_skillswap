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
        Schema::create('skill_embeddings', function (Blueprint $table) {
            $table->unsignedInteger('skill_id');
            $table->string('model', 100);
            $table->json('embedding');
            $table->char('content_hash', 64);
            $table->dateTime('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['skill_id', 'model']);
            $table->foreign('skill_id', 'fk_skill_embeddings_skill')->references('id')->on('skills')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_embeddings');
    }
};
