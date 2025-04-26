<?php

declare(strict_types=1);

use App\Http\Controllers\Common\CityController;
use App\Http\Controllers\Common\CompanyController;
use App\Http\Controllers\Common\JobRoleController;
use App\Http\Controllers\Common\LogoutController;
use App\Http\Controllers\Common\PreferredLocationController;
use App\Http\Controllers\Common\PreferredWorkEnvironmentController;
use App\Http\Controllers\Common\RegionController;
use App\Http\Controllers\Common\SkillController;
use App\Http\Controllers\Common\TelevisionShowController;
use App\Http\Controllers\Common\TimezoneController;
use App\Http\Controllers\Common\UserCompanyController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], static function () {
    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'anonymous_throttle'], static function () {
    Route::get('city/{region}/list', [CityController::class, 'listByRegion'])->name('city.list.by-region');

    Route::get('job-role/search', [JobRoleController::class, 'search'])->name('job-role.search');

    Route::get('region/{country}/list', [RegionController::class, 'listByCountry'])->name('region.list.by-country');
});

Route::get('city/search', [CityController::class, 'search'])->name('city.search');

Route::get('company/search', [CompanyController::class, 'search'])->name('company.search');

Route::get('preferred-work-environment/search', [PreferredWorkEnvironmentController::class, 'search'])->name('preferred-work-environment.search');

Route::get('preferred-location/search', [PreferredLocationController::class, 'search'])->name('preferred-location.search');

Route::get('skill/search', [SkillController::class, 'search'])->name('skill.search');

Route::get('television-show/search', [TelevisionShowController::class, 'search'])->name('television-show.search');

Route::get('timezone/list', [TimezoneController::class, 'list'])->name('timezone.list');

Route::get('user-company/search', [UserCompanyController::class, 'search'])->name('user-company.search');
