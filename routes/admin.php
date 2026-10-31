<?php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TimelineController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
        Route::get('/profile', 'profile')->name('profile');
        Route::get('/options', 'options')->name('options');
    });

    Route::resource('pages', PageController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('services', ServiceController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('portfolio', PortfolioController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('timeline', TimelineController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('team', TeamController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('contacts', ContactController::class)->only([
        'index', 'show',
    ]);

    Route::resource('strings', TranslationController::class)->only([
        'index', 'create', 'edit',
    ]);

    Route::resource('users', UserController::class)->only([
        'index', 'create', 'edit',
    ]);
});
