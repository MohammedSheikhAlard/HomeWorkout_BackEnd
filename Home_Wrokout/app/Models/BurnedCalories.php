<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BurnedCalories extends Model
{
    /** @use HasFactory<\Database\Factories\BurnedCaloriesFactory> */
    use HasFactory;

    protected $fillable =
    [
        'user_id',
        'total_calories_burned_in_day',
        'day_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
