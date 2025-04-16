<?php

declare(strict_types=1);

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
     * @param array $credentials
     *
     * @return array
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
