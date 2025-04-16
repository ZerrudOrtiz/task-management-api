<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function createAccessToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], 
        ]);
    }

    public function deleteCurrentAccessToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
