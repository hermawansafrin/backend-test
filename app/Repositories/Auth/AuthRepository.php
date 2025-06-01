<?php

namespace App\Repositories\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    /**
     * Check if user is login
     * @return bool
     */
    public function isLogin(): bool
    {
        $authCheck = Auth::check();
        return $authCheck;
    }

    /**
     * Do login logic
     * @param Request $request
     * @return bool
     */
    public function doLogin(Request $request): bool
    {
        $authAttempt = Auth::attempt($request->only('email', 'password'));
        return $authAttempt;
    }

    /**
     * Get login user
     * @return array|null
     */
    public function loginUser(): array|null
    {
        $user = Auth::user();
        if ($user === null) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => [
                'type' => 'Bearer',
                'value' => $token,
            ],
        ];
    }

    /**
     * Logout API
     * @param Request $request
     * @return void
     */
    public function logoutApi(Request $request): void
    {
        // Get current token
        $token = $request->user()->currentAccessToken();

        if ($token) {
            // Revoke the token
            $token->delete();
        }
    }

    /**
     * Do logout logic
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
