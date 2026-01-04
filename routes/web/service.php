<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'service',
    'as' => 'service.',
    'middleware' => ['auth', 'verified'],
], function () {
    // Raja Ongkir Service
    require 'service/rajaongkir.php';

    // Shop Service
    require 'service/shop.php';
});
