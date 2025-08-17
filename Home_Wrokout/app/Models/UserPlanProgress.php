<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlanProgress extends Model
{
    /** @use HasFactory<\Database\Factories\UserPlanProgressFactory> */
    use HasFactory;

    protected $fillable = [
        'user_plan_id',
        'plan_day_id',
        'is_trained',
        'date',
    ];

    public function planDay()
    {
        return $this->belongsTo(PlanDay::class);
    }

    public function user()
    {
        return $this->BelongsTo(User::class);
    }
}
