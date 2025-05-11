<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Interfaces\Repositories\TaskRepositoryInterface;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\TaskServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected TaskRepositoryInterface $taskRepository,
        protected UserRepositoryInterface $userRepository,
        protected TaskServiceInterface $taskService
    ) {}

    public function index()
    {
        return view('pages.tasks.index');
    }

    public function datatable(Request $request)
    {
        $query = $this->taskRepository->getDatatableQuery($request);

        return DataTables::of($query)
            ->addColumn('status_label', fn($task) => $task->status === 'completed' ? 'Concluída' : 'Pendente')
            ->addColumn('actions', function ($task) {
                $completeButton = $task->status === 'completed'
                    ? "<button class='btn btn-sm btn-secondary' disabled><i class='fa fa-check'></i> Concluída</button>"
                    : "<button class='btn btn-sm btn-success mark-complete' data-id='{$task->id}'><i class='fa fa-check'></i> Concluir</button>";

                return "
                    {$completeButton}
                    <button class='btn btn-sm btn-warning btn-edit-task' data-id='{$task->id}'>Editar</button>
                    <button class='btn btn-sm btn-danger' onclick=\"confirmDelete('/tasks/{$task->id}')\">Excluir</button>
                ";
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function listUsers(): JsonResponse
    {
        $users = $this->userRepository->listForSelect2();

        return response()->json($users);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $this->taskService->store($request->validated());

        return $this->success(null, 'Tarefa criada com sucesso!', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $task = $this->taskRepository->findById($id);

        return $this->success($task, '');
    }

    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskRepository->findById($id);

        $this->taskService->update($task, $request->validated());

        return $this->success(null, 'Tarefa atualizada com sucesso!', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $task = $this->taskRepository->findById($id);

        $this->taskService->destroy($task);

        return $this->success(null, 'Tarefa excluida com sucesso!', 201);
    }

    public function markAsCompleted(int $id): JsonResponse
    {
        $task = $this->taskRepository->findById($id);
        $this->taskService->markAsCompleted($task);

        return $this->success(null, 'Tarefa marcada como concluída!');
    }
}
