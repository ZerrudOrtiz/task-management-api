<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getAllTasks(Request $request);
    public function createTask(array $data);
    public function updateTask($task, array $data);
    public function deleteTask($task);
}
