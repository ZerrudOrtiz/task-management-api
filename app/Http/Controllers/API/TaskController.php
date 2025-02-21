<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('title') && $request->title != null) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->has('status') && $request->status != null) {
            $query->where('status', $request->status);
        }
    
        if ($request->has('assigned_to') && $request->assigned_to != null) {
            $query->where('assigned_to', $request->assigned_to);
        }
    
        $perPage = $request->get('per_page', 5);
        
        $tasks = $query->paginate($perPage);
    
        return response()->json([
            'tasks' => $tasks->map(function ($task) {
                return [
                    'task_id' => $task->task_id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                    'assigned_to' => $task->user->user_id,
                    'name' => $task->user ? $task->user->name : 'Unassigned',
                ];
            }),
            'current_page' => $tasks->currentPage(),
            'total_pages' => $tasks->lastPage(),
            'total_tasks' => $tasks->total(),
            'per_page' => $tasks->perPage(),
            'next_page_url' => $tasks->nextPageUrl(),
            'prev_page_url' => $tasks->previousPageUrl(),
        ]);
    }

    public function store(TaskRequest $request)
    {

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => 1
        ],201);
    }

    public function update(Task $task, TaskRequest $request)
    {
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->assigned_to = $request->assigned_to;
        $task->save();

        return response()->json([
            'success' => 1
        ],201);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }
}
