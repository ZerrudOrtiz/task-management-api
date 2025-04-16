<?php

namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(string $email, string $password): array
    {
        $user = $this->authRepository->findUserByEmail($email);

        if ($user && Hash::check($password, $user->password)) {
            $token = $this->authRepository->createAccessToken($user);

            return [
                'success' => true,
                'user' => $user,
                'accessToken' => $token,
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid login',
        ];
    }

   public function register(array $data)
    {
        $user = $this->authRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'accessToken' => $token,
            'success' => 1,
        ];
    }
    public function logout(User $user): void
    {
        $this->authRepository->deleteCurrentAccessToken($user);
    }
}
