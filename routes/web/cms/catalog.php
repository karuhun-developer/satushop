<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'catalog',
    'as' => 'catalog.',
], function () {
    Route::resources([
        'product-categories' => App\Http\Controllers\Cms\Catalog\ProductCategoryController::class,
        'products' => App\Http\Controllers\Cms\Catalog\ProductController::class,
    ]);
});
