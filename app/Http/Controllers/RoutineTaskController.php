<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoutineTaskResource;
use App\Models\RoutineTask;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineTaskController extends Controller
{
    use ApiResponse;

    // Routine index method
    public function index()
    {
        // get active routine tasks with user id
        $userId = Auth::user()->id;
        $dayOfWeek = request()->query('day_of_week');
        $routineTasks = RoutineTask::where('user_id', $userId)
            ->whereNull('parent_id')
            ->whereNull('deactivated_at')
            ->when($dayOfWeek, function ($query) use ($dayOfWeek) {
                $query->whereJsonContains('repeat_days', $dayOfWeek);
            })
            ->get();

        return $this->success(
            RoutineTaskResource::collection($routineTasks),
            'Routine tasks retrieved successfully'
        );
    }


    // Routine show method
    public function show($id)
    {
        $routineTask = RoutineTask::with('subTasks')->findOrFail($id);

        return $this->success(
            new RoutineTaskResource($routineTask),
            'Routine task retrieved successfully'
        );
    }


    // Create routine task method
    public function store(Request $request)
    {
        // validation
        $validatedData = $this->validateRoutineTask($request);

        $validatedData['user_id'] = Auth::user()->id;
        // if repeat_days is not null, encode it to json
        $validatedData['repeat_days'] = $request->repeat_days ? json_encode($validatedData['repeat_days']) : null;
        $routineTask = RoutineTask::create($validatedData);

        return $this->success(
            new RoutineTaskResource($routineTask),
            'Routine task was created successfully',
            201
        );
    }


    // Update routine task method
    public function update(Request $request, $id)
    {
        // validation
        $validatedData = $this->validateRoutineTask($request);

        // deactivate old routine task
        $routineTask = RoutineTask::findOrFail($id);
        $routineTask->update(['deactivated_at' => now()]);

        // create new routine task with updated data
        $validatedData['user_id'] = Auth::user()->id;
        // if repeat_days is not null, encode it to json
        $validatedData['repeat_days'] = $request->repeat_days ? json_encode($validatedData['repeat_days']) : null;

        $routineTask = RoutineTask::create($validatedData);

        return $this->success(
            new RoutineTaskResource($routineTask),
            'Routine task was updated successfully',
        );
    }


    // Delete routine task method
    public function destroy($id)
    {
        $routineTask = RoutineTask::findOrFail($id);
        // deactivate routine task
        $routineTask->update(['deactivated_at' => now()]);

        return $this->success(
            new RoutineTaskResource($routineTask),
            'Routine task was deactivated successfully',
        );
    }


    // validation for routine task
    private function validateRoutineTask(Request $request)
    {
        return $request->validate([
            'parent_id' => 'nullable|exists:routine_tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'repeat_days' => 'required_without:parent_id|array',
            'repeat_days.*' => 'integer|between:0,6',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i|after:start_at',
        ]);
    }
}
