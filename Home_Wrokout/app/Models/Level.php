<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    /** @use HasFactory<\Database\Factories\LevelFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function exercise()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_levels');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'level_id');
    }

    public function plans()
    {
        return $this->hasMany(Plan::class, 'level_id');
    }

    public function exerciseLevels()
    {
        return $this->hasMany(ExerciseLevel::class);
    }
}
