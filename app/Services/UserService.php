<?php

namespace App\Services;

use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Interfaces\Services\UserServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            $data['is_admin'] = false;

            return $this->userRepository->create($data);
        });
    }

    public function update(User $user, array $data): bool
    {
        return DB::transaction(function () use ($user, $data) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            return $this->userRepository->update($user, $data);
        });
    }

    public function destroy(User $user): array
    {
        if ($user->tasks()->exists()) {
            return [
                'success' => false,
                'message' => 'Não é possível excluir um usuário com tarefas vinculadas.'
            ];
        }

        return DB::transaction(function () use ($user) {
            $this->userRepository->delete($user);

            return [
                'success' => true,
                'message' => 'Usuário excluído com sucesso!'
            ];
        });
    }
}
