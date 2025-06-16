<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanDay extends Model
{
    /** @use HasFactory<\Database\Factories\PlanDayFactory> */
    use HasFactory;

    protected $fillable = [
        'day_number',
        'total_calories',
        'is_rest_day',
        'plan_id',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function progress()
    {
        return $this->belongsToMany(User::class, 'user_plan_progress');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'plan_day_exercises');
    }
}
