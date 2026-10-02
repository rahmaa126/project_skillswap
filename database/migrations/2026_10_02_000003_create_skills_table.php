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
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->string('name', 100)->unique('uq_skills_name');
            $table->text('description')->nullable();
            $table->integer('min_score')->default(70);
            $table->boolean('is_active')->default(true);
            $table->dateTime('created_at')->useCurrent();

            $table->foreign('category_id', 'fk_skills_category')->references('id')->on('skill_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
