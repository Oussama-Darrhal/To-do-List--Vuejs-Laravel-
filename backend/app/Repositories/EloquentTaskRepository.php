<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Task::query()
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findForUser(int $userId, int $taskId): ?Task
    {
        return Task::query()
            ->where('user_id', $userId)
            ->where('id', $taskId)
            ->first();
    }

    public function createForUser(int $userId, array $data): Task
    {
        $data['user_id'] = $userId;
        return Task::create($data);
    }

    public function updateForUser(int $userId, int $taskId, array $data): ?Task
    {
        $task = $this->findForUser($userId, $taskId);
        if (!$task) {
            return null;
        }
        $task->fill($data);
        $task->save();
        return $task;
    }

    public function deleteForUser(int $userId, int $taskId): bool
    {
        $task = $this->findForUser($userId, $taskId);
        if (!$task) {
            return false;
        }
        return (bool) $task->delete();
    }
}


