<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;

class AuthorizationService
{
    /**
     * User repository instance
     *
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * Create new service instance
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Attempt to authenticate a user
     *
     * @param array $credentials Authentication credentials including:
     *                          - 'email' (string) User email address
     *                          - 'password' (string) User password
     * @return array Contains:
     *               - 'user' (array) Authenticated user data
     *               - 'token' (string) API access token
     * @throws AuthenticationException When authentication fails
     * @see UserRepositoryInterface::attemptLogin()
     */
    public function attemptLogin(array $credentials): array
    {
        return $this->userRepository->attemptLogin($credentials);
    }
}
