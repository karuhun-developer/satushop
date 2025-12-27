<?php

use App\Models\Core\Currency;
use App\Models\Core\Locale;
use Illuminate\Support\Facades\Cache;

function numberToCurrency($value)
{
    return number_format($value, 0, ',', '.');
}

function currencyToNumber($value)
{
    return (int) str_replace('.', '', $value);
}

function defaultLocale($refresh = false)
{
    if ($refresh) {
        Cache::forget('default:locale');
    }

    return Cache::remember('default:locale', now()->addDay(), function () {
        return Locale::where('is_default', true)->first();
    });
}

function getLocales()
{
    return Locale::orderBy('is_default', 'desc')->get();
}

function defaultCurrency($refresh = false)
{
    if ($refresh) {
        Cache::forget('default:currency');
    }

    return Cache::remember('default:currency', now()->addDay(), function () {
        return Currency::where('is_default', true)->first();
    });
}

function getCurrencies()
{
    return Currency::orderBy('is_default', 'desc')->get();
}

function getRate(Currency $currency)
{
    $defaultCurrency = defaultCurrency();

    if ($currency->id === $defaultCurrency->id) {
        return 1;
    }

    $rate = $currency->rates()->where('target_currency_id', $currency->id)->first();

    return $rate ? $rate->rate : null;
}
