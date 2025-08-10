<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::group(
    [
        'prefix' => 'user',
        'namespace' => 'User',
    ],
    static function () {
        Route::post('/register', [UserController::class, 'register'])
            ->withoutMiddleware('auth:sanctum')
            ->name('user.register');
        Route::post('/login', [UserController::class, 'login'])
            ->withoutMiddleware('auth:sanctum')
            ->name('user.login');
    }
);
