<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Dotenv\Exception\ValidationException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);

        if ($result['success']) {
            return response()->json([
                'user' => $result['user'],
                'accessToken' => $result['accessToken'],
                'success' => 1,
            ]);
        }

        return response()->json([
            'message' => $result['message']
        ], 401);
    }

    public function register(RegistrationRequest $request)
    {
        try {
            $result = $this->authService->register($request->all());

            return response()->json([
                'user' => $result['user'],
                'accessToken' => $result['accessToken'],
                'success' => 1,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
