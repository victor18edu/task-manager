<?php

namespace App\Interfaces\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function store(array $data): User;
    public function update(User $user, array $data): bool;
    public function destroy(User $user): array;
}
