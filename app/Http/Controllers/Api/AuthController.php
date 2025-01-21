<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'error' => true,
                'message' => 'Email or password are invalid. Try Again.',
                'code' => 10000,
                'details' => null
            ], 401);
        }

        $user = Auth::user();
        $token = $this->_createToken($user);

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                ],
                'token' => $token['token'],
                'tokenType' => $token['token_type'],
                'expiresAt' => $token['expires_at'],
            ]
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json();
    }

    /**
     * Create token for user
     * @param \App\Models\User $user
     *
     * @return array
     */
    private function _createToken(\App\Models\User $user): array
    {
        $token = $user->createToken('auth-token', ['*'], now()->addHours(12));

        return [
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at,
            'token_type' => 'Bearer',
        ];
    }
}
