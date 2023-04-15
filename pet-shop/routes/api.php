<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;

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

Route::get('/login', [LoginController::class, 'login'])->name('login');;

Route::group(['prefix' => 'v1'], function () {
        Route::group(['middleware' => 'auth:api'], function() {
            Route::group(['middleware' => 'admin'], function() {
                Route::group(['prefix' => 'admin'], function () {
                    Route::get('/user-listing', [UserController::class, 'index']);
                    Route::put('/user-edit/{uuid}', [UserController::class, 'update']);
                    Route::delete('/user-delete/{uuid}', [UserController::class, 'destroy']);
                });
            });
        });
});

