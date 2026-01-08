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
    Route::get('products/{product}/edit-modal', [App\Http\Controllers\Cms\Catalog\ProductController::class, 'editModal'])->name('products.edit-modal');
});
