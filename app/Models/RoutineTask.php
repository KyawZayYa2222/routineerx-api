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
        'repeat_days',
        'start_at',
        'end_at',
        'deactivated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subTasks()
    {
        return $this->hasMany(RoutineTask::class, 'parent_id');
    }
}
