<?php

namespace App\Repositories;

use App\Interfaces\Repositories\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskRepository implements TaskRepositoryInterface
{
    public function getDatatableQuery(Request $request)
    {
        $user = auth()->user();
        return Task::with('users')
            ->when($request->title, fn($q) => $q->where('title', 'like', '%' . $request->title . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->user_id, function ($q) use ($request) {
                $q->whereHas('users', fn($query) => $query->where('users.id', $request->user_id));
            }, function ($q) use ($user) {
                $q->whereHas('users', fn($query) => $query->where('users.id', $user->id));
            });
    }

     public function create(array $data): Task
    {
            $data['user_id'] = auth()->id();
            $task = Task::create($data);
            $task->users()->sync([auth()->id()] ?? []);
            return $task;
    }

    public function update(Task $task, array $data, bool $syncUsers = true): bool
    {
        $task->update($data);
        if ($syncUsers) {
            $task->users()->sync($data['user_ids'] ?? []);
        }
        return true;
    }

    public function findById(int $id): Task
    {
        return Task::with('users')->findOrFail($id);
    }
}
