<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;

    public function find(int $id): ?User;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getAll(): \Illuminate\Database\Eloquent\Collection;
}
