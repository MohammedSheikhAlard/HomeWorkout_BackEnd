<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDayExercise extends Model
{
    /** @use HasFactory<\Database\Factories\PlanDayExerciseFactory> */
    use HasFactory;

    protected $fillable = [
        'plan_day_id',
        'exercies_level_id',
        'exercies_order'
    ];

    public function planDay()
    {
        return $this->belongsTo(PlanDay::class);
    }

    public function excercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function exerciseLevel()
    {
        return $this->belongsTo(ExerciseLevel::class, 'exercies_level_id');
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class, 'exercies_level_id');
    }

    protected static function booted()
    {
        static::created(function ($planDayExercise) {
            $planDayExercise->planDay->updateTotalCalories();
        });

        static::updated(function ($planDayExercise) {
            $planDayExercise->planDay->updateTotalCalories();
        });

        static::deleted(function ($planDayExercise) {
            $planDayExercise->planDay->updateTotalCalories();
        });
    }
}
