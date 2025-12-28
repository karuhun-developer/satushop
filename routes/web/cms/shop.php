<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'shop',
    'as' => 'shop.',
], function () {
    Route::resources([
        'shops' => \App\Http\Controllers\Cms\Shop\ShopController::class,
    ]);

    Route::patch('shops/{shop}/status', [\App\Http\Controllers\Cms\Shop\ShopController::class, 'updateStatus'])->name('shops.update-status');
});
