<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;

class AuthorizationService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function attemptLogin(array $credentials): bool
    {
        return $this->userRepository->attemptLogin($credentials);
    }
}
