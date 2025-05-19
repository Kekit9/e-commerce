<?php

declare(strict_types=1);

namespace App\Services\Authorization;

use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Services\Authorization\Interface\AuthorizationServiceInterface;
use Illuminate\Auth\AuthenticationException;

class AuthorizationService implements AuthorizationServiceInterface
{
    /**
     * Create new service instance
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Attempt to authenticate a user
     *
     * @param array<string, string> $credentials Array containing login credentials (typically 'email' and 'password')
     *
     * @return array<string, mixed>
     *
     * @throws AuthenticationException When authentication fails
     *
     * @see UserRepositoryInterface::attemptLogin()
     */
    public function attemptLogin(array $credentials): array
    {
        return $this->userRepository->attemptLogin($credentials);
    }
}
