<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\ApiHelpers;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiHelpers;
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        //$data['password'] = Hash::make($data['password']);
        if (!isset($data['hire_date'])) {
            $data['hire_date'] = \Illuminate\Support\now();
        }

        $user = User::create($data);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('login', $request->login)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->onError(401, 'Invalid credentials');
        }

        ///TODO: Сделать права токенам
        $token = $user->createToken('auth-token')->plainTextToken;

        return $this->onSuccess([
            'user' => new UserResource($user),
            'token' => $token,
        ], 'User logged in successfully',
            className: 'UserAuth', container_type: 'object', checkContainerType: false);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->onSuccess(null, 'Logged out successfully');
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return $this->onSuccess(new UserResource($request->user()), 'User retrieved successfully');
    }

    public function check() : JsonResponse
    {
        return $this->onSuccess(null, 'You are logged in');
    }
}
