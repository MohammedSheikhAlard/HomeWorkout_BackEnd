<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'password',
    ];

    public function createdExercises()
    {
        return $this->hasMany(exercise::class, 'admin_id');
    }

    public function createdCategories()
    {
        return $this->hasMany(category::class, 'admin_id');
    }

    public function createdPlans()
    {
        return $this->hasMany(Plan::class, 'admin_id');
    }
}
