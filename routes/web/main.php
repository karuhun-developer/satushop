<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('home');
Route::get('/explore', [App\Http\Controllers\Main\ExploreController::class, 'index'])->name('explore');
Route::get('/explore/category/{category}', [App\Http\Controllers\Main\ExploreController::class, 'category'])->name('explore.category');

Route::get('/{id}', function ($id) {
    // Mock finding product
    return Inertia\Inertia::render('Shop/Show', [
        'product' => [
            'id' => $id,
            'name' => 'Premium Product Name',
            'price' => 'Rp 2.500.000',
            'description' => 'Experience premium quality with our flagship product. Designed for comfort and durability.',
            'rating' => 4.8,
            'category' => 'Electronics',
            'images' => [
                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=600&h=600',
                'https://images.unsplash.com/photo-1546435770-a3e426bf472b?auto=format&fit=crop&q=80&w=600&h=600',
                'https://images.unsplash.com/photo-1583394838336-acd977736f90?auto=format&fit=crop&q=80&w=600&h=600',
            ],
        ],
    ]);
})->name('show');

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
