<?php

namespace App\Interfaces\Services;

use App\Models\Task;

interface TaskServiceInterface
{
    public function store(array $data): Task;
    public function update(Task $task, array $data): bool;
    public function destroy(Task $task): bool;
    public function markAsCompleted(Task $task): bool;
}
