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
     * Do logout logic
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
