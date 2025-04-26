<?php

declare(strict_types=1);

use App\Http\Controllers\Client\Candidate\AccountSettingController;
use App\Http\Controllers\Client\Candidate\ForgotPasswordController;
use App\Http\Controllers\Client\Candidate\LoginController;
use App\Http\Controllers\Client\Candidate\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], static function () {
    Route::group(['prefix' => 'forgot-password', 'as' => 'forgot-password.'], static function () {
        Route::get('/', [ForgotPasswordController::class, 'view'])->name('view');
        Route::post('/', [ForgotPasswordController::class, 'sendPassword'])->name('post');
    });

    Route::group(['prefix' => 'login', 'as' => 'login.'], static function () {
        Route::get('/', [LoginController::class, 'view'])->name('view');
        Route::post('/', [LoginController::class, 'login'])->name('post');
    });

    Route::group(['prefix' => 'reset/password', 'as' => 'reset-password.'], static function () {
        Route::get('{token}', [ResetPasswordController::class, 'view'])->name('view');
        Route::post('{token}', [ResetPasswordController::class, 'resetPassword'])->name('post');
    });
});

Route::group(['middleware' => 'auth'], static function () {
    Route::group(['prefix' => 'account-settings', 'as' => 'account-settings.'], static function () {
        Route::get('/', [AccountSettingController::class, 'show'])->name('show');
        Route::patch('/', [AccountSettingController::class, 'update'])->name('update');
    });
});
