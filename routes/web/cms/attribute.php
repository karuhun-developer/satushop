<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'attribute',
    'as' => 'attribute.',
], function () {
    Route::resources([
        'attribute-families' => \App\Http\Controllers\Cms\Attribute\AttributeFamilyController::class,
        'attributes' => \App\Http\Controllers\Cms\Attribute\AttributeController::class,
    ]);
});
