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
}
