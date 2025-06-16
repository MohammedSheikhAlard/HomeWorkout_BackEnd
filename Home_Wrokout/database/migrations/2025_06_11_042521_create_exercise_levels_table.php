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
        Schema::create('exercise_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->references('id')->on('levels');
            $table->foreignId('exercies_id')->references('id')->on('exercises');
            $table->integer('calories');
            $table->integer('number_of_rips');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_levels');
    }
};
