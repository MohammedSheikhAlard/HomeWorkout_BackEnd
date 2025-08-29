<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'admin_id',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
