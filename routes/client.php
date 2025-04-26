<?php

declare(strict_types=1);

use App\Http\Controllers\Client\ActiveSessionController;
use App\Http\Controllers\Client\BasicPageController;
use App\Http\Controllers\Client\CandidateController;
use App\Http\Controllers\Client\ContactUsController;
use App\Http\Controllers\Client\EmailVerificationController;
use App\Http\Controllers\Client\ForgotPasswordController;
use App\Http\Controllers\Client\LoginController;
use App\Http\Controllers\Client\PermissionController;
use App\Http\Controllers\Client\RegisterController;
use App\Http\Controllers\Client\ResetPasswordController;
use App\Http\Controllers\Client\ShortlistController;
use App\Http\Controllers\Client\SubscriptionController;
use App\Http\Controllers\Client\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], static function () {
    Route::get('forgot-password', [ForgotPasswordController::class, 'view'])->name('forgot-password.view');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendPassword'])->name('forgot-password.post');

    Route::group(['prefix' => 'login', 'as' => 'login.'], static function () {
        Route::get('/', [LoginController::class, 'view'])->name('view');
        Route::post('/', [LoginController::class, 'login'])->name('post');
    });

    Route::get('reset/password/{token}', [ResetPasswordController::class, 'view'])->name('reset-password.view');
    Route::post('reset/password/{token}', [ResetPasswordController::class, 'resetPassword'])->name('reset-password.post');

    Route::group(['prefix' => 'sign-up', 'as' => 'sign-up.'], static function () {
        Route::get('/', [RegisterController::class, 'view'])->name('view');
        Route::post('/', [RegisterController::class, 'register'])->name('post');
    });

    Route::group(['prefix' => 'email/verification', 'as' => 'email.verification.'], static function () {
        Route::post('resend/{hash}', [EmailVerificationController::class, 'resend'])
            ->name('resend')
            ->middleware('throttle:5,60');

        Route::get('{user}/{hash}', [EmailVerificationController::class, 'verify'])->name('verify');
    });
});

Route::group(['middleware' => 'auth'], static function () {
    Route::group(['prefix' => 'candidates', 'as' => 'candidate.', 'middleware' => 'has_subscription'], static function () {
        Route::get('/', [CandidateController::class, 'listPage'])->name('page.list');
        Route::get('list', [CandidateController::class, 'list'])->name('list');
        Route::get('{candidateSlug}', [CandidateController::class, 'show'])->name('show');
        Route::get('{candidate}/alternative-talent', [CandidateController::class, 'getAlternativeTalents'])->name('alternative-talent');
        Route::get('{candidate}/download-cv', [CandidateController::class, 'downloadCv'])->name('download-cv');
        Route::post('{candidate}/send-message', [CandidateController::class, 'sendMessage'])->name('send-message');
    });

    Route::group(['prefix' => 'shortlist', 'as' => 'shortlist.', 'middleware' => 'has_subscription'], static function () {
        Route::get('list', [ShortlistController::class, 'list'])->name('list');
        Route::get('{shortlist}/list', [ShortlistController::class, 'getCandidates'])->name('candidates.list');
        Route::post('/', [ShortlistController::class, 'store'])->name('store');
        Route::post('{shortlist}/{candidate}', [ShortlistController::class, 'detachCandidate'])->name('candidate.detach');
        Route::patch('{shortlist}', [ShortlistController::class, 'syncCandidate'])->name('candidate.sync');
        Route::delete('{shortlist}', [ShortlistController::class, 'destroy'])->name('destroy');
    });

    Route::get('permission/roles', [PermissionController::class, 'roles'])->name('permission.roles');

    Route::group(['prefix' => 'subscription', 'as' => 'subscription.'], static function () {
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::post('request-change', [SubscriptionController::class, 'requestChange'])->name('request-change')->middleware('has_subscription');
        Route::post('request-pause', [SubscriptionController::class, 'requestPause'])->name('request-pause')->middleware('has_subscription');
        Route::get('inactive', [SubscriptionController::class, 'inactive'])->name('inactive');
    });

    Route::group(['prefix' => 'user', 'as' => 'user.', 'middleware' => 'has_subscription'], static function () {
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::get('edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/', [UserController::class, 'update'])->name('update');
        Route::get('shortlists', [UserController::class, 'shortlists'])->name('shortlists');
    });

    Route::group(['prefix' => 'active-sessions', 'as' => 'active-session.'], static function () {
        Route::get('/', [ActiveSessionController::class, 'view'])
            ->name('view')
            ->withoutMiddleware([\App\Http\Middleware\TrackActiveSession::class]);

        Route::delete('{activeSession}', [ActiveSessionController::class, 'destroy'])
            ->name('destroy')
            ->withoutMiddleware([
                \App\Http\Middleware\CheckActiveSessionsCount::class,
                \App\Http\Middleware\TrackActiveSession::class,
            ]);
    });
});

Route::group(['prefix' => 'register', 'as' => 'register.'], static function () {
    Route::get('subscription', [RegisterController::class, 'subscription'])->name('subscription')->middleware('has_inactive_subscription');
    Route::get('subscription/thank-you', [RegisterController::class, 'subscriptionThankYou'])->name('subscription.thank-you');
});

Route::get('contact-us', [ContactUsController::class, 'view'])->name('contact-us.view');
Route::post('contact-us', [ContactUsController::class, 'post'])->name('contact-us.post');

Route::get('/', [BasicPageController::class, 'homePage'])->name('home.page');
Route::get('about-us', [BasicPageController::class, 'aboutUsPage'])->name('about-us.page');
Route::get('privacy-policy', [BasicPageController::class, 'privacyPolicyPage'])->name('privacy-policy.page');
Route::get('terms-and-conditions', [BasicPageController::class, 'termsAndConditionsPage'])->name('terms-and-conditions.page');
