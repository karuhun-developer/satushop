<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'shop',
], function () {
    Route::get('shop', [\App\Http\Controllers\Service\ShopController::class, 'shop'])->name('index');
});
