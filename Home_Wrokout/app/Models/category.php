<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
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
