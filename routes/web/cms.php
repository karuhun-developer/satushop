<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'cms',
    'as' => 'cms.',
    'middleware' => ['auth', 'verified'],
], function () {
    // Dashboard Route
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    // Attribute Routes
    require 'cms/attribute.php';

    // Management Routes
    require 'cms/management.php';

    // Core Routes
    require 'cms/core.php';

    // Logs
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs')->middleware('auth', 'role:superadmin');
});
