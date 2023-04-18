<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {

    // Admin Endpoints
    Route::post('admin/login', [LoginController::class, 'login'])->name('login');
    Route::get('admin/logout', [LoginController::class, 'logout'])->name('logout');
        Route::group(['middleware' => 'auth:api'], function() {
            Route::group(['middleware' => 'admin'], function() {
                Route::group(['prefix' => 'admin'], function () {
                    Route::get('/user-listing', [AdminController::class, 'index']);
                    Route::post('/create', [AdminController::class, 'store']);
                    Route::put('/user-edit/{uuid}', [AdminController::class, 'update']);
                    Route::delete('/user-delete/{uuid}', [AdminController::class, 'destroy']);
                });
            });
        });

    // User Endpoints
    Route::post('user/login', [LoginController::class, 'login'])->name('login');
        Route::group(['middleware' => 'auth:api'], function() {
            Route::group(['middleware' => 'user'], function() {
                    Route::get('/user', [UserController::class, 'index']);
                    Route::delete('/user', [UserController::class, 'destroy']);
                    Route::put('/user/edit', [UserController::class, 'update']);
                    Route::get('/user/orders', [UserController::class, 'orders']);
            });
        });
});

