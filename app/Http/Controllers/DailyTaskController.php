<?php

namespace App\Http\Controllers;

use App\Models\DailyTaskCompletion;
use App\Models\RoutineTask;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyTaskController extends Controller
{
    use ApiResponse;

    // Complete Task method
    public function completeTask(Request $request, $id)
    {
        // check if the task exists and belongs to the user
        $routineTask = RoutineTask::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        // create a new daily task completion record
        DailyTaskCompletion::create([
            'user_id' => Auth::user()->id,
            'routine_task_id' => $routineTask->id,
            'completed_at' => now(),
        ]);

        return $this->success(
            null,
            'Task marked as completed successfully'
        );
    }

    // Check is Task Completed method
    public function isTaskCompleted($id)
    {
        // check if the task exists and belongs to the user
        $routineTask = RoutineTask::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->firstOrFail();

        // check if the task is completed today
        $isCompleted = DailyTaskCompletion::where('user_id', Auth::user()->id)
            ->where('routine_task_id', $routineTask->id)
            ->whereDate('completed_at', now()->toDateString())
            ->exists();

        return $this->success(
            ['is_completed' => $isCompleted],
            'Task completion status retrieved successfully'
        );
    }
}
