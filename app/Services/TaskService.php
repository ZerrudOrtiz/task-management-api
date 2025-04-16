<?php

namespace App\Services;

use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function listTasks(Request $request)
    {
        return $this->taskRepository->getAllTasks($request);
    }

    public function create(array $data)
    {
        return $this->taskRepository->createTask($data);
    }

    public function update(Task $task, array $data)
    {
        return $this->taskRepository->updateTask($task, $data);
    }

    public function delete(Task $task)
    {
        return $this->taskRepository->deleteTask($task);
    }
}
