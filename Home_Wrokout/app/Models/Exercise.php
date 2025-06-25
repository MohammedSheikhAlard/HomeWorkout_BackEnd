<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exercise extends Model
{
    /** @use HasFactory<\Database\Factories\ExerciseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'category_id',
        'admin_id',
    ];

    protected function levels()
    {
        return $this->belongsToMany(Level::class, 'exercies_levels');
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
