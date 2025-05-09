<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

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

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('sort_title')) {
            $direction = strtolower($request->get('sort_title')) === 'desc' ? 'desc' : 'asc';
            $query->orderBy('title', $direction);
        }

        if ($request->filled('sort_created_date')) {
            $direction = strtolower($request->get('sort_created_date')) === 'desc' ? 'desc' : 'asc';
            $query->orderBy('created_at', $direction);
        }

        // $query->where('user_id', Auth::id());

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