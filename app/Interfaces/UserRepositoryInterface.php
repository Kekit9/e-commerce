<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function find(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getAll(): Collection;
    public function attemptLogin(array $credentials): bool;
}
