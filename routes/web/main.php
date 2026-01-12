<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('home');
Route::get('/explore', [App\Http\Controllers\Main\ExploreController::class, 'index'])->name('explore');
Route::get('/explore/category/{category}', [App\Http\Controllers\Main\ExploreController::class, 'category'])->name('explore.category');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', function () {
        return Inertia\Inertia::render('Cart/Index');
    })->name('index');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', function () {
        return Inertia\Inertia::render('Checkout/Index');
    })->name('index');
});

Route::get('/{product}', [App\Http\Controllers\Main\ProductController::class, 'show'])->name('product.show');
