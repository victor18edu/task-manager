<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\UserServiceInterface;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserServiceInterface $userService
    ) {}

    public function index()
    {
        return view('pages.users.index');
    }

    public function datatable()
    {
        $query = $this->userRepository->getDatatableQuery();

        return DataTables::of($query)
            ->addColumn('status_label', fn($user) => $user->status ? 'Ativo' : 'Inativo')
            ->addColumn('actions', fn($user) => "
                <button class='btn btn-sm btn-warning btn-edit-user text-white' data-id='{$user->id}' data-bs-toggle='tooltip' data-bs-title='Editar' data-bs-placement='top'><i class='fa fa-edit'></i></button>
                <button class='btn btn-sm btn-danger' onclick=\"confirmDelete('/users/{$user->id}')\" data-bs-toggle='tooltip' data-bs-title='Excluir' data-bs-placement='top'><i class='fa fa-trash'></i></button>
            ")
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->userService->store($request->validated());

        return $this->success(null, 'Usuário criado com sucesso!', 201);
    }

    public function edit(int $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);

        return $this->success($user, '');
    }

    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);

        $this->userService->update($user, $request->validated());

        return $this->success(null, 'Usuário atualizado com sucesso!', 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = $this->userRepository->findById($id);
        $result = $this->userService->destroy($user);

        return response()->json($result, $result['success'] ? 201 : 400);
    }
}
