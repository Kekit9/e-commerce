<?php

declare(strict_types=1);

namespace App\Services\Registration\Interface;

use App\Models\User;

interface RegistrationServiceInterface
{
    /**
     * Handle user registration
     *
     * @param array<string, mixed> $data User registration data
     *
     * @return User Newly created user
     */
    public function registerUser(array $data): User;
}
