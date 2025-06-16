<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseLevel extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseLevelFactory> */
    use HasFactory;

    protected $fillable = [
        'level_id',
        'exercies_id',
        'calories',
        'number_of_rips',
    ];

    public function exercies()
    {
        return $this->belongsTo(Exercise::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
