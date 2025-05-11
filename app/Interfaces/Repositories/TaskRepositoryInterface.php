<?php

namespace App\Interfaces\Repositories;

use Illuminate\Http\Request;
use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getDatatableQuery(Request $request);
    public function findById(int $id): Task;
    public function create(array $data): Task;
    public function update(Task $task, array $data, bool $syncUsers = true): bool;
}
