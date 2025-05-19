<?php

declare(strict_types=1);

namespace App\Services\Registration;

use App\Models\User;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Services\Registration\Interface\RegistrationServiceInterface;

class RegistrationService implements RegistrationServiceInterface
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
