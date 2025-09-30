<?php

namespace App\Services;

use App\Models\Task;
use App\Events\TaskCreated;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Notification;

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
        $task = $this->tasks->createForUser($userId, $data);
        TaskCreated::dispatch($task);
        Notification::create([
            'user_id' => $userId,
            'task_id' => $task->id,
            'type' => 'task.created',
            'title' => 'Task created',
            'message' => $task->title,
        ]);
        return $task;
    }

    public function updateTask(int $userId, int $taskId, array $data): ?Task
    {
        $task = $this->tasks->updateForUser($userId, $taskId, $data);
        if ($task) {
            Notification::create([
                'user_id' => $userId,
                'task_id' => $task->id,
                'type' => 'task.updated',
                'title' => 'Task updated',
                'message' => $task->title,
            ]);
        }
        return $task;
    }

    public function deleteTask(int $userId, int $taskId): bool
    {
        return $this->tasks->deleteForUser($userId, $taskId);
    }
}


