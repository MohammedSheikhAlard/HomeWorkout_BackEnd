<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tall',
        'weight',
        'gender',
        'BMI',
        'target_calories',
        'date_of_birth',
        'reminder',
        'level_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'user_plans');
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }


    public function planDay()
    {
        return $this->belongsToMany(PlanDay::class, 'user_plan_progress');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function level()
    {
        return $this->hasOne(Level::class);
    }

    public function burned_calories()
    {
        return $this->hasMany(BurnedCalories::class);
    }
}
