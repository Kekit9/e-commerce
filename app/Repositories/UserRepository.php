<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new user.
     *
     * @param array<string, mixed> $data User data (name, email, password, etc.)
     *
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Login user in system.
     *
     * @param array<string, string> $credentials Login credentials (email, password)
     *
     * @return array{
     *      user: array{
     *          id: int,
     *          name: string,
     *          email: string,
     *          role: string
     *      },
     *      token: string
     *  }
     *
     * @throws AuthenticationException
     */
    public function attemptLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException(__('auth.invalid_cred'));
        }

        $user = Auth::user();

        if (!$user instanceof User) {
            throw new AuthenticationException(__('auth.user_not_found'));
        }

        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'token' => $user->createToken('auth-token')->plainTextToken
        ];
    }
}
