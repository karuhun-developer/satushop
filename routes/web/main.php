<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('home');
Route::get('/explore', [App\Http\Controllers\Main\ExploreController::class, 'index'])->name('explore');
Route::get('/explore/category/{category}', [App\Http\Controllers\Main\ExploreController::class, 'category'])->name('explore.category');
Route::get('/cart', [App\Http\Controllers\Main\CartController::class, 'index'])->name('cart');
Route::get('/checkout', [App\Http\Controllers\Main\CheckoutController::class, 'index'])->name('checkout');

// Single Product
Route::get('/{product}', [App\Http\Controllers\Main\ProductController::class, 'show'])->name('product.show');
