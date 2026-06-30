<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(private readonly TaskService $taskService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->list($request->only(['status', 'search', 'per_page']));

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());

        return response()->json(new TaskResource($task), 201);
    }

    public function show(Task $task): TaskResource
    {
        return new TaskResource($task->load('creator'));
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task = $this->taskService->update($task, $request->validated());

        return new TaskResource($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->delete($task);

        return response()->json(['message' => 'Task deleted.']);
    }
}
