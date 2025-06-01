<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {}

    public function login(string $email, string $password): array
    {
        if (!Auth::attempt([
            'email' => $email,
            'password' => $password
        ])) {
            throw new UnauthorizedException('Invalid credentials');
        }

        $user = $this->userRepository->findBy('email', $email);
        /**
         * @var \Laravel\Sanctum\PersonalAccessToken $token
         */
        $token = $user->createToken('login-token');

        return [
            "token_type" => "Bearer",
            'access_token' => $token->plainTextToken,
        ];
    }
}
