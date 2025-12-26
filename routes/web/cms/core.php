<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'core',
    'as' => 'core.',
], function () {
    Route::resources([
        'locales' => App\Http\Controllers\Cms\Core\LocaleController::class,
        'currencies' => App\Http\Controllers\Cms\Core\CurrencyController::class,
        'currency-rates' => App\Http\Controllers\Cms\Core\CurrencyRateController::class,
    ]);
});
