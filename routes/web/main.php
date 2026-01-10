<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\Main\HomeController::class, 'index'])->name('home');

Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', function () {
        return Inertia\Inertia::render('Shop/Index', [
            'products' => [
                ['id' => 1, 'name' => 'Premium Wireless Headphones', 'price' => 'Rp 2.500.000', 'rating' => 4.8, 'category' => 'Electronics', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&q=80&w=400&h=400'],
                ['id' => 2, 'name' => 'Minimalist Watch', 'price' => 'Rp 1.200.000', 'rating' => 4.5, 'category' => 'Fashion', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&q=80&w=400&h=400'],
                ['id' => 3, 'name' => 'Ergonomic Office Chair', 'price' => 'Rp 3.500.000', 'rating' => 4.9, 'category' => 'Home & Living', 'image' => 'https://images.unsplash.com/photo-1505843490538-5133c6c7d0e1?auto=format&fit=crop&q=80&w=400&h=400'],
                ['id' => 4, 'name' => 'Running Shoes Force', 'price' => 'Rp 1.800.000', 'rating' => 4.7, 'category' => 'Sports', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&q=80&w=400&h=400'],
            ],
        ]);
    })->name('index');

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
});

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
