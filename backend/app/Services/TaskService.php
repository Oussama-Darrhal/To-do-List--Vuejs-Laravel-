<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(private readonly TaskRepositoryInterface $tasks)
    {
    }

    public function listUserTasks(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->tasks->paginateForUser($userId, $perPage);
    }

    public function getUserTask(int $userId, int $taskId): ?Task
    {
        return $this->tasks->findForUser($userId, $taskId);
    }

    public function createTask(int $userId, array $data): Task
    {
        return $this->tasks->createForUser($userId, $data);
    }

    public function updateTask(int $userId, int $taskId, array $data): ?Task
    {
        return $this->tasks->updateForUser($userId, $taskId, $data);
    }

    public function deleteTask(int $userId, int $taskId): bool
    {
        return $this->tasks->deleteForUser($userId, $taskId);
    }
}


