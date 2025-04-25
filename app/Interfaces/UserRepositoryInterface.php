<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Laravel\Sanctum\NewAccessToken;

interface UserRepositoryInterface
{
    /**
     * Create a new user record
     *
     * @param array<string, mixed> $data User data including name, email, password etc.
     *
     * @return User Newly created User model instance
     *
     * @throws QueryException If creation fails
     */
    public function create(array $data): User;

    /**
     * Attempt to authenticate a user
     *
     * @param array<string, string> $credentials Authentication credentials (email, password)
     *
     * @return array{user: User, token: NewAccessToken}
     *
     * @throws AuthenticationException If authentication fails
     */
    public function attemptLogin(array $credentials): array;
}
