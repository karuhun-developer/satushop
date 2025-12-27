<?php

use App\Actions\Cms\Core\Currency\DeleteCurrencyAction;
use App\Actions\Cms\Core\Currency\StoreCurrencyAction;
use App\Actions\Cms\Core\Currency\UpdateCurrencyAction;
use App\Models\Core\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store currency action creates a currency', function () {
    $action = new StoreCurrencyAction;
    $data = [
        'code' => 'USD',
        'name' => 'US Dollar',
    ];

    $currency = $action->handle($data);

    expect($currency)->toBeInstanceOf(Currency::class);
    $this->assertDatabaseHas('currencies', $data);
});

test('update currency action updates a currency', function () {
    $currency = Currency::create([
        'code' => 'OLD',
        'name' => 'Old Name',
        'is_default' => false,
    ]);
    $action = new UpdateCurrencyAction;
    $data = [
        'code' => 'NEW',
        'name' => 'New Name',
    ];

    $result = $action->handle($currency, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('currencies', ['id' => $currency->id, 'code' => 'NEW']);
});

test('delete currency action deletes a currency', function () {
    $currency = Currency::create([
        'code' => 'DEL',
        'name' => 'Delete Me',
        'is_default' => false,
    ]);
    $action = new DeleteCurrencyAction;

    $result = $action->handle($currency);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('currencies', ['id' => $currency->id]);
});
