<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TaskActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Task $task,
        public readonly string $action,
    ) {}

    public function handle(): void
    {
        Log::info('Task activity', [
            'action' => $this->action,
            'task_id' => $this->task->id,
            'tenant_id' => $this->task->tenant_id,
            'created_by' => $this->task->created_by,
        ]);
    }
}
