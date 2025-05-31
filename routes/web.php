<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\UserController;
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

    // regional customers
    Route::group([
        'prefix' => '/customers',
        'as' => 'customers.',
        'middleware' => 'web.user_has_permission_to:customers',
    ], function () {
        Route::get('', [CustomerController::class, 'index'])->name('index');
        Route::get('/get-yajra', [CustomerController::class, 'getYajra'])->name('getYajra');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/store', [CustomerController::class, 'store'])->name('store')->middleware('web.user_has_permission_to:customers_add');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('update')->middleware('web.user_has_permission_to:customers_edit');
        Route::delete('/delete/{id}', [CustomerController::class, 'destroy'])->name('delete')->middleware('web.user_has_permission_to:customers_delete');
    });

    // regional settings
    Route::group([
        'prefix' => '/settings',
        'as' => 'settings.',
        'middleware' => 'web.user_has_permission_to:settings',
    ], function () {
        // user
        Route::group([
            'prefix' => '/users',
            'as' => 'users.',
            'middleware' => 'web.user_has_permission_to:settings_user',
        ], function () {
            Route::get('', [UserController::class, 'index'])->name('index');
            Route::get('/get-yajra', [UserController::class, 'getYajra'])->name('getYajra');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store')->middleware('web.user_has_permission_to:settings_user_add');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('update')->middleware('web.user_has_permission_to:settings_user_edit');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete')->middleware('web.user_has_permission_to:settings_user_delete');
        });

        // role
        Route::group([
            'prefix' => '/roles',
            'as' => 'roles.',
            'middleware' => 'web.user_has_permission_to:settings_role',
        ], function () {
            Route::get('', [RoleController::class, 'index'])->name('index');
            Route::get('/get-yajra', [RoleController::class, 'getYajra'])->name('getYajra');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/store', [RoleController::class, 'store'])->name('store')->middleware('web.user_has_permission_to:settings_role_add');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [RoleController::class, 'update'])->name('update')->middleware('web.user_has_permission_to:settings_role_edit');
            Route::delete('/delete/{id}', [RoleController::class, 'destroy'])->name('delete')->middleware('web.user_has_permission_to:settings_role_delete');
        });
    });
});
