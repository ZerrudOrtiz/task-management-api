<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $tasks = $this->taskService->listTasks($request);

        return response()->json([
            'tasks' => $tasks->map(function ($task) {
                return [
                    'task_id' => $task->task_id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'order' => $task->order,
                    'user_id' => $task->user->user_id,
                    'name' => $task->user ? $task->user->name : 'Unassigned',
                    'attachments' => $task->user->attachments,
                    'visible' => $task->visible,
                    'created_at' => $task->created_at->setTimezone('Asia/Manila')->format('Y-m-d h:i A'),
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
        $this->taskService->create($request->validated());

        return response()->json(['success' => 1], 201);
    }

    public function update(Task $task, TaskRequest $request)
    {
        $this->taskService->update($task, $request->validated());

        return response()->json(['success' => 1], 201);
    }

    public function destroy(Task $task)
    {
        $this->taskService->delete($task);

        return response()->json(['success' => 1], 200);
    }

    public function getUsers()
    {
        return response()->json(User::all());
    }

    public function toggleStatus(Task $task)
    {
        $task->visible = $task->visible == 1 ? 0 : 1;

        $task->save();

        return response()->json(['success' => 1], 200);
    }
}