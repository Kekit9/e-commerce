<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class RegistrationService
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
     * Handle user registration.
     *
     * @param array<string, mixed> $data
     *
     * @return User
     */
    public function registerUser(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
