<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService)
    {
    }

    public function index(Request $request)
    {
        $userId = auth('api')->id();
        $tasks = $this->taskService->listUserTasks((int)$userId);
        return response()->json($tasks);
    }

    public function show(Request $request, int $id)
    {
        $userId = auth('api')->id();
        $task = $this->taskService->getUserTask((int)$userId, $id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $userId = (int) auth('api')->id();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);
        $task = $this->taskService->createTask($userId, $data);
        // Broadcasting of creation event will be added later
        return response()->json($task, 201);
    }

    public function update(Request $request, int $id)
    {
        $userId = (int) auth('api')->id();
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_completed' => ['sometimes', 'boolean'],
            'due_at' => ['nullable', 'date'],
        ]);
        $task = $this->taskService->updateTask($userId, $id, $data);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json($task);
    }

    public function destroy(Request $request, int $id)
    {
        $userId = (int) auth('api')->id();
        $deleted = $this->taskService->deleteTask($userId, $id);
        if (!$deleted) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        return response()->json(null, 204);
    }
}


