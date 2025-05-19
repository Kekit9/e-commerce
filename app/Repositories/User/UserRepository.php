<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\User\Interface\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

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
     * Attempt to authenticate user and return auth tokens
     *
     * @param array<string, string> $credentials
     *
     * @return array{user: User, token: NewAccessToken}
     *
     * @throws AuthenticationException
     */
    public function attemptLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new AuthenticationException(__('auth.invalid_cred'));
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user instanceof User) {
            throw new AuthenticationException(__('auth.user_not_found'));
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')
        ];
    }
}
