<?php

namespace App\Http\Controllers;

use App\Models\RoutineTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineTaskController extends Controller
{
    // Routine index method
    public function index()
    {
        // get routine task with user id
        $userId = Auth::user()->id;
        $dayOfWeek = request()->query('day_of_week');
        $routineTasks = RoutineTask::where('user_id', $userId)
            ->when($dayOfWeek, function ($query) use ($dayOfWeek) {
                return $query->where('day_of_week', $dayOfWeek);
            })
            ->get();

        return response()->json([
            'routine_tasks' => $routineTasks
        ], 200);
    }

    // Routine show method
    public function show($id)
    {
        $routineTask = RoutineTask::findOrFail($id);

        return response()->json([
            'routine_task' => $routineTask
        ], 200);
    }

    // Create routine task method
    public function store(Request $request)
    {
        // validation
        $validatedData = $this->validateRoutineTask($request);
        $validatedData['user_id'] = Auth::user()->id;

        // return response()->json([
        //     'message' => 'Validation successful',
        //     'validated_data' => $validatedData
        // ], 200);

        $routineTask = RoutineTask::create($validatedData);

        return response()->json([
            'message' => 'Routine task created successfully',
            'routine_task' => $routineTask
        ], 201);
    }


    // Update routine task method
    public function update(Request $request, $id)
    {
        // validation
        $validatedData = $this->validateRoutineTask($request);

        $routineTask = RoutineTask::findOrFail($id);
        $routineTask->update($validatedData);

        return response()->json([
            'message' => 'Routine task updated successfully',
            'routine_task' => $routineTask
        ], 200);
    }


    // Delete routine task method
    public function destroy($id)
    {
        $routineTask = RoutineTask::findOrFail($id);
        $routineTask->delete();

        return response()->json([
            'message' => 'Routine task deleted successfully'
        ], 200);
    }


    // validation for routine task
    protected function validateRoutineTask(Request $request)
    {
        return $request->validate([
            'parent_id' => 'nullable|exists:routine_tasks,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'day_of_week' => 'required|integer|between:0,6',
            'start_at' => 'required|date_format:H:i',
            'end_at' => 'required|date_format:H:i|after:start_at',
        ]);
    }
}
