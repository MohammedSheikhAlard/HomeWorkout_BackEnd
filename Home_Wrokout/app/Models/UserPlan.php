<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPlan extends Model
{
    /** @use HasFactory<\Database\Factories\UserPlanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'status',
    ];

    public function user()
    {
        return $this->BelongsTo(User::class);
    }

    public function plan()
    {
        return $this->BelongsTo(Plan::class);
    }
}
