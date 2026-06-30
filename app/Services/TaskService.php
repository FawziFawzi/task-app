<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Jobs\TaskActivityJob;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    public function list(array $filters): LengthAwarePaginator
    {
        $filters['page'] = request()->query('page', 1);
        $cacheKey = 'list:' . md5(json_encode($filters));

        return Cache::tags($this->cacheTags())->remember($cacheKey, 300, function () use ($filters) {
            return Task::query()
                ->with('creator')
                ->when(isset($filters['status']), fn ($q) => $q->where('status', $filters['status']))
                ->when(isset($filters['search']), fn ($q) => $q->where('title', 'like', '%' . $filters['search'] . '%'))
                ->paginate($filters['per_page'] ?? 15);
        });
    }

    public function create(array $data): Task
    {
        $task = Task::create([
            'tenant_id' => auth()->user()->tenant_id,
            'created_by' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? TaskStatus::Todo,
            'due_date' => $data['due_date'] ?? null,
        ]);

        $this->clearCache();
        TaskActivityJob::dispatch($task, 'created');

        return $task->load('creator');
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        $this->clearCache();
        TaskActivityJob::dispatch($task, 'updated');

        return $task->load('creator');
    }

    public function delete(Task $task): void
    {
        TaskActivityJob::dispatch($task, 'deleted');
        $task->delete();
        $this->clearCache();
    }

    private function cacheTags(): array
    {
        return ['tasks', 'tenant:' . auth()->user()->tenant_id];
    }

    private function clearCache(): void
    {
        Cache::tags($this->cacheTags())->flush();
    }
}
