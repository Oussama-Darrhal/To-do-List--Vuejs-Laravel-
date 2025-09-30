<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function findForUser(int $userId, int $taskId): ?Task;

    public function createForUser(int $userId, array $data): Task;

    public function updateForUser(int $userId, int $taskId, array $data): ?Task;

    public function deleteForUser(int $userId, int $taskId): bool;
}


