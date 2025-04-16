<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAllTasks(Request $request)
    {
        $query = Task::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $query->orderBy('order', 'asc');

        $perPage = $request->get('per_page', 5);

        return $query->paginate($perPage);
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask($task, array $data)
    {
        return $task->update($data);
    }

    public function deleteTask($task)
    {
        return $task->delete();
    }
}