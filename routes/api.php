<?php

use App\Http\Controllers\ProfilingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

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
        Route::patch('/profile', [UserController::class, 'updateProfile'])
            ->name('user.updateProfile');
    }
);

Route::group(
    [
        'prefix' => 'profiling',
        'namespace' => 'Profiling',
    ],
    static function () {
        Route::get('/', [ProfilingController::class, 'getProfilingQuestions'])
            ->name('profiling.getQuestions');
    }
);

Route::group(
    [
        'prefix' => 'wallet',
        'namespace' => 'Wallet',
    ],
    static function () {
        Route::get('/', [WalletController::class, 'getWallet'])
            ->name('wallet.get');
        Route::post('/{transaction}/claim', [WalletController::class, 'claimTransaction'])
            ->name('wallet.claimTransaction');
    }
);
