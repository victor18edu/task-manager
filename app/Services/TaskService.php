<?php

namespace App\Services;

use App\Interfaces\Services\TaskServiceInterface;
use App\Interfaces\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskService implements TaskServiceInterface
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function store(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            return $this->taskRepository->create($data);
        });
    }

    public function update(Task $task, array $data): bool
    {
        return DB::transaction(function () use ($task, $data) {
            return $this->taskRepository->update($task, $data);
        });
    }


    public function destroy(Task $task): bool
    {
        return DB::transaction(function () use ($task) {
            return $task->delete();
        });
    }

    public function markAsCompleted(Task $task): bool
    {
        return DB::transaction(function () use ($task) {
            return $this->taskRepository->update($task, ['status' => 'completed'], false);
        });
    }
}
