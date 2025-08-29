<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ExerciseLevel extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseLevelFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level_id',
        'exercise_id',
        'calories',
        'number_of_rips',
        'timer',
    ];

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function planDayExercises()
    {
        return $this->hasMany(PlanDayExercise::class, 'exercies_level_id');
    }
}
