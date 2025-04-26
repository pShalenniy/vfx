<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\ContentDataController;
use App\Http\Controllers\Admin\EmailTemplateSettingController;
use App\Http\Controllers\Admin\OurPartnerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\TimezoneController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user', 'as' => 'user.'], static function () {
    Route::get('list', [UserController::class, 'list'])->name('list')->middleware('permission:user.page');
    Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:user.create');
    Route::patch('{user}', [UserController::class, 'update'])->name('update')->middleware('permission:user.edit');
    Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:user.delete');
});

Route::group(['prefix' => 'candidate', 'as' => 'candidate.'], static function () {
    Route::get('list', [CandidateController::class, 'list'])->name('list')->middleware('permission:candidate.list');
    Route::post('/', [CandidateController::class, 'store'])->name('store')->middleware('permission:candidate.create');
    Route::post('{candidate}/mark-starred', [CandidateController::class, 'markStarred'])
        ->name('mark-starred')
        ->middleware('permission:candidate.mark-starred');

    Route::patch('{candidate}', [CandidateController::class, 'update'])->name('update')->middleware('permission:candidate.edit');
    Route::delete('{candidate}', [CandidateController::class, 'destroy'])->name('destroy')->middleware('permission:candidate.delete');
});

Route::group(['prefix' => 'content-data', 'as' => 'content-data.'], static function () {
    Route::get('list', [ContentDataController::class, 'list'])->name('list')->middleware('permission:cms.block.list');
    Route::patch('/', [ContentDataController::class, 'set'])->name('set')->middleware('permission:cms.block.set');
});

Route::group(['prefix' => 'email-template-setting', 'as' => 'email-template-setting.'], static function () {
    Route::get('list', [EmailTemplateSettingController::class, 'list'])
        ->name('list')
        ->middleware('permission:email-template-setting.list');

    Route::patch('{emailTemplateSetting}', [EmailTemplateSettingController::class, 'update'])
        ->name('update')
        ->middleware('permission:email-template-setting.edit');
});

Route::group(['prefix' => 'our-partner', 'as' => 'our-partner.'], static function () {
    Route::get('list', [OurPartnerController::class, 'list'])->name('list')->middleware('permission:cms.block.our-partners.list');
    Route::post('/', [OurPartnerController::class, 'store'])->name('store')->middleware('permission:cms.block.our-partners.create');
    Route::patch('{ourPartner}', [OurPartnerController::class, 'update'])
        ->name('update')
        ->middleware('permission:cms.block.our-partners.edit');

    Route::delete('{ourPartner}', [OurPartnerController::class, 'destroy'])
        ->name('destroy')
        ->middleware('permission:cms.block.our-partners.delete');
});

Route::group(['prefix' => 'role', 'as' => 'role.'], static function () {
    Route::get('list', [RoleController::class, 'list'])->name('list')->middleware('permission:role.page');
    Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:role.create');
    Route::patch('{role}', [RoleController::class, 'update'])->name('update')->middleware('permission:role.edit');
    Route::delete('{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:role.delete');
});

Route::group(['prefix' => 'skill', 'as' => 'skill.'], static function () {
    Route::get('list', [SkillController::class, 'list'])->name('list')->middleware('permission:skill.page');
    Route::get('search', [SkillController::class, 'search'])->name('search')->middleware('permission:skill.search');
    Route::post('/', [SkillController::class, 'store'])->name('store')->middleware('permission:skill.create');
    Route::patch('{skill}', [SkillController::class, 'update'])->name('update')->middleware('permission:skill.edit');
    Route::delete('{skill}', [SkillController::class, 'destroy'])->name('destroy')->middleware('permission:skill.delete');
});

Route::group(['prefix' => 'subscription', 'as' => 'subscription.'], static function () {
    Route::patch('{subscription}/has-expired', [SubscriptionController::class, 'resetHasExpired'])
        ->name('update.has-expired')
        ->middleware('permission:subscription.update.has-expired');

    Route::patch('{subscription}/renew', [SubscriptionController::class, 'renew'])
        ->name('renew')
        ->middleware('permission:subscription.renew');
});

Route::group(['prefix' => 'timezone', 'as' => 'timezone.'], static function () {
    Route::post('/', [TimezoneController::class, 'store'])->name('store')->middleware('permission:timezone.create');
    Route::patch('{timezone}', [TimezoneController::class, 'update'])->name('update')->middleware('permission:timezone.edit');
    Route::delete('{timezone}', [TimezoneController::class, 'destroy'])->name('destroy')->middleware('permission:timezone.delete');
});
