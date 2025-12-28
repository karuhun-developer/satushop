<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'rajaongkir',
    'as' => 'rajaongkir.',
], function () {
    Route::get('provinces', [\App\Http\Controllers\Service\RajaOnkirController::class, 'provinces'])->name('provinces');
    Route::get('cities/{provinceId}', [\App\Http\Controllers\Service\RajaOnkirController::class, 'cities'])->name('cities');
    Route::get('districts/{cityId}', [\App\Http\Controllers\Service\RajaOnkirController::class, 'districts'])->name('districts');
    Route::get('sub-districts/{districtId}', [\App\Http\Controllers\Service\RajaOnkirController::class, 'subDistricts'])->name('sub-districts');
    Route::post('cost', [\App\Http\Controllers\Service\RajaOnkirController::class, 'cost'])->name('cost');
});
