<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTaskCompletion extends Model
{
    protected $fillable = [
        'user_id',
        'routine_task_id',
        'completed_at',
    ];

    public function routineTask()
    {
        return $this->belongsTo(RoutineTask::class);
    }
}
