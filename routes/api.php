<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
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
        // orders
        Route::group([
            'prefix' => 'orders',
            'middleware' => [
                'api.user_has_permission_to:orders',
            ],
        ], function () {
            Route::get('/', [TransactionController::class, 'paginate']);
            Route::get('/{id}', [TransactionController::class, 'show']);
            Route::post('/', [TransactionController::class, 'store'])->middleware('api.user_has_permission_to:orders_add');
            Route::put('/{id}', [TransactionController::class, 'update'])->middleware('api.user_has_permission_to:orders_edit');
            Route::delete('/{id}', [TransactionController::class, 'destroy'])->middleware('api.user_has_permission_to:orders_delete');
            Route::patch('/{id}/complete', [TransactionController::class, 'complete'])->middleware('api.user_has_permission_to:orders_edit');
            Route::patch('/{id}/cancel', [TransactionController::class, 'cancel'])->middleware('api.user_has_permission_to:orders_edit');
        });

        // products
        Route::group([
            'prefix' => 'products',
            'middleware' => [
                'api.user_has_permission_to:products',
            ],
        ], function () {
            Route::get('/', [ProductController::class, 'paginate']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::post('/', [ProductController::class, 'store'])->middleware('api.user_has_permission_to:products_add');
            Route::put('/{id}', [ProductController::class, 'update'])->middleware('api.user_has_permission_to:products_edit');
            Route::delete('/{id}', [ProductController::class, 'destroy'])->middleware('api.user_has_permission_to:products_delete');
        });

        // customers
        Route::group([
            'prefix' => 'customers',
            'middleware' => [
                'api.user_has_permission_to:customers',
            ],
        ], function () {
            Route::get('/', [CustomerController::class, 'paginate']);
            Route::get('/{id}', [CustomerController::class, 'show']);
            Route::post('/', [CustomerController::class, 'store'])->middleware('api.user_has_permission_to:customers_add');
            Route::put('/{id}', [CustomerController::class, 'update'])->middleware('api.user_has_permission_to:customers_edit');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->middleware('api.user_has_permission_to:customers_delete');
        });

        Route::group([
            'middleware' => [
                'api.user_has_permission_to:settings',
            ],
        ], function () {
            // users
            Route::group([
                'prefix' => 'users',
                'middleware' => [
                    'api.user_has_permission_to:settings_user',
                ],
            ], function () {
                Route::get('/', [UserController::class, 'paginate']);
                Route::get('/{id}', [UserController::class, 'show']);
                Route::post('/', [UserController::class, 'store'])->middleware('api.user_has_permission_to:settings_user_add');
                Route::put('/{id}', [UserController::class, 'update'])->middleware('api.user_has_permission_to:settings_user_edit');
                Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('api.user_has_permission_to:settings_user_delete');
            });

            // roles
            Route::group([
                'prefix' => 'roles',
                'middleware' => [
                    'api.user_has_permission_to:settings_role',
                ],
            ], function () {
                Route::get('/', [RoleController::class, 'paginate']);
                Route::get('/{id}', [RoleController::class, 'show']);
                Route::post('/', [RoleController::class, 'store'])->middleware('api.user_has_permission_to:settings_role_add');
                Route::put('/{id}', [RoleController::class, 'update'])->middleware('api.user_has_permission_to:settings_role_edit');
                Route::delete('/{id}', [RoleController::class, 'destroy'])->middleware('api.user_has_permission_to:settings_role_delete');
            });
        });
    });
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
