<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findUserByEmail(string $email);
    public function createAccessToken(User $user);
    public function deleteCurrentAccessToken(User $user);
    public function create(array $data);
}
