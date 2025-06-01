<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'authentication'], function () {
        // Public routes
        Route::post('/login', [AuthController::class, 'login']);

        // Protected routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/test', [AuthController::class, 'test']);
        });
    });

    Route::group([
        'middleware' => [
            'auth:sanctum',
        ],
    ], function () {
        Route::group([
            'middleware' => [
                'api.user_has_permission_to:settings',
            ],
        ], function () {
            Route::group([
                'prefix' => 'roles',
                'middleware' => [
                    'api.user_has_permission_to:settings_role',
                ],
            ], function () {
                Route::get('/', [RoleController::class, 'paginate']);
                Route::get('/{id}', [RoleController::class, 'show']);
                Route::post('/', [RoleController::class, 'store'])->middleware('api.user_has_permission_to:settings_role_create');
                Route::put('/{id}', [RoleController::class, 'update'])->middleware('api.user_has_permission_to:settings_role_edit');
                Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('api.user_has_permission_to:settings_role_delete');
            });
        });
    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
