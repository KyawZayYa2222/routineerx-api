<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class RoutineTask extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'description',
        'day_of_week',
        'start_at',
        'end_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
