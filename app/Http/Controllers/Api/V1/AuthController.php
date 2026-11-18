<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Issue a Sanctum API token for a user.
     *
     * POST /api/v1/login
     * Body: { "email": "...", "password": "...", "device_name": "Postman" }
     * Returns: { "token": "...", "user": { ... } }
     *
     * This endpoint allows editors/admins to authenticate and receive a token
     * for use in Postman/Bruno to test POST, PATCH and DELETE guide endpoints.
     *
     * Status 200 on success, 422 on validation failure.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('The provided credentials are incorrect.')],
            ]);
        }

        if (! $user->is_active) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Account is deactivated. Contact an administrator.',
            ], 403, ['Content-Type' => 'application/json']);
        }

        $deviceName = $request->device_name ?? 'api-client';

        // Revoke existing tokens for this device to avoid token proliferation
        $user->tokens()->where('name', $deviceName)->delete();

        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token'  => $token,
            'user'   => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role->name ?? 'unknown',
            ],
        ], 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Revoke current token (logout from API).
     *
     * POST /api/v1/logout
     * Header: Authorization: Bearer <token>
     * Status 200 on success.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Token revoked. Logged out successfully.',
        ], 200, ['Content-Type' => 'application/json']);
    }
}
