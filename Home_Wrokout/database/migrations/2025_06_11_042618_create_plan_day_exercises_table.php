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
        Schema::create('plan_day_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_day_id')->references('id')->on('plan_days');
            $table->foreignId('exercies_level_id')->references('id')->on('exercise_levels');
            $table->integer('exercies_order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_day_exercises');
    }
};
