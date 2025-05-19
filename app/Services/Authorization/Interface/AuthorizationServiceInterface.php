<?php

declare(strict_types=1);

namespace App\Services\Authorization\Interface;

use Illuminate\Auth\AuthenticationException;

interface AuthorizationServiceInterface
{
    /**
     * Attempt to authenticate a user
     *
     * @param array<string, mixed> $credentials Authentication credentials
     *
     * @return array<string, mixed>
     *
     * @throws AuthenticationException
     */
    public function attemptLogin(array $credentials): array;
}
