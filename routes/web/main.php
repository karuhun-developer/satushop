<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('home');
Route::get('/explore', [App\Http\Controllers\Main\ExploreController::class, 'index'])->name('explore');
Route::get('/explore/category/{category}', [App\Http\Controllers\Main\ExploreController::class, 'category'])->name('explore.category');
Route::get('/cart', [App\Http\Controllers\Main\CartController::class, 'index'])->name('cart');

// Checkout
Route::get('/checkout', [App\Http\Controllers\Main\CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [App\Http\Controllers\Main\CheckoutController::class, 'store'])->name('checkout.process');

// After checkout
Route::get('/transactions/{transaction}', [App\Http\Controllers\Main\TransactionController::class, 'show'])->name('transaction.show');
Route::put('/transactions/{transaction}', [App\Http\Controllers\Main\TransactionController::class, 'update'])->name('transaction.update');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/my-profile', [App\Http\Controllers\Main\ProfileController::class, 'index'])->name('my-profile');
    Route::put('/my-profile', [App\Http\Controllers\Main\ProfileController::class, 'update'])->name('my-profile.update');

    // User Addresses
    Route::post('/user-addresses', [App\Http\Controllers\Main\UserAddressController::class, 'store'])->name('user-addresses.store');
    Route::put('/user-addresses/{userAddress}', [App\Http\Controllers\Main\UserAddressController::class, 'update'])->name('user-addresses.update');
    Route::delete('/user-addresses/{userAddress}', [App\Http\Controllers\Main\UserAddressController::class, 'destroy'])->name('user-addresses.destroy');

    // After login redirect
    Route::get('/after-login', function () {
        if (auth()->user()->isUser()) {
            return to_route('home');
        } else {
            return to_route('cms.dashboard');
        }
    });
});

// Single Product
Route::get('/{product}', [App\Http\Controllers\Main\ProductController::class, 'show'])->name('product.show');
