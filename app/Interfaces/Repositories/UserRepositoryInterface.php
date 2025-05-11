<?php

namespace App\Interfaces\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

interface UserRepositoryInterface
{
    public function getAll(): Collection;
    public function getDatatableQuery(): Builder;
    public function findById(int $id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
    public function listForSelect2();
}
