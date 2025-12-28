<?php

use App\Actions\Cms\Core\CurrencyRate\DeleteCurrencyRateAction;
use App\Actions\Cms\Core\CurrencyRate\StoreCurrencyRateAction;
use App\Actions\Cms\Core\CurrencyRate\UpdateCurrencyRateAction;
use App\Models\Core\Currency;
use App\Models\Core\CurrencyRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('store currency rate action creates a currency rate', function () {
    $targetCurrency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'is_default' => false]);
    $action = new StoreCurrencyRateAction;
    $data = [
        'target_currency_id' => $targetCurrency->id,
        'rate' => 1.5,
    ];

    $currencyRate = $action->handle($data);

    expect($currencyRate)->toBeInstanceOf(CurrencyRate::class);
    $this->assertDatabaseHas('currency_rates', $data);
});

test('update currency rate action updates a currency rate', function () {
    $targetCurrency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'is_default' => false]);
    $currencyRate = CurrencyRate::create([
        'target_currency_id' => $targetCurrency->id,
        'rate' => 1.0,
    ]);

    $action = new UpdateCurrencyRateAction;
    $data = [
        'target_currency_id' => $targetCurrency->id,
        'rate' => 2.0,
    ];

    $result = $action->handle($currencyRate, $data);

    expect($result)->toBeTrue();
    $this->assertDatabaseHas('currency_rates', ['id' => $currencyRate->id, 'rate' => 2.0]);
});

test('delete currency rate action deletes a currency rate', function () {
    $targetCurrency = Currency::create(['code' => 'USD', 'name' => 'US Dollar', 'is_default' => false]);
    $currencyRate = CurrencyRate::create([
        'target_currency_id' => $targetCurrency->id,
        'rate' => 1.0,
    ]);
    $action = new DeleteCurrencyRateAction;

    $result = $action->handle($currencyRate);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('currency_rates', ['id' => $currencyRate->id]);
});
