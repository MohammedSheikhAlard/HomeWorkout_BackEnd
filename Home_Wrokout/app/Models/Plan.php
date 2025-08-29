<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'number_of_day_to_train',
        'admin_id',
        'level_id',
    ];

    public function admin()
    {
        return $this->belongsto(Admin::class, 'admin_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function days()
    {
        return $this->hasMany(PlanDay::class, 'plan_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_plans');
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function planDays()
    {
        return $this->hasMany(PlanDay::class);
    }
}
