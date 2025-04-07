<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class RegistrationService
{
    /**
     * User repository instance
     *
     * @var UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle user registration.
     *
     * @param array $data
     *
     * @return User
     */
    public function registerUser(array $data): User
    {
        return $this->userRepository->create($data);
    }
}
