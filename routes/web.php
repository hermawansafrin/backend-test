<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

// auth routes
Route::get('', [AuthController::class, 'index'])->name('login');
Route::post('postlogin', [AuthController::class, 'postLogin'])->name('postlogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group([
    'prefix' => '/dashboard',
    'as' => 'dashboard.',
    'middleware' => 'auth',
], function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');

    Route::group([
        'prefix' => '/settings',
        'as' => 'settings.',
        'middleware' => 'web.user_has_permission_to:settings',
    ], function () {
        // role
        Route::group([
            'prefix' => '/role',
            'as' => 'role.',
            'middleware' => 'web.user_has_permission_to:settings_role',
        ], function () {
            Route::get('', function () {
                return 'role';
            })->name('index');
        });
    });
});
