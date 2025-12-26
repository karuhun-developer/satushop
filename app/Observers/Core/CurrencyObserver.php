<?php

namespace App\Observers\Core;

use App\Models\Core\Currency;

class CurrencyObserver
{
    /**
     * Handle the Currency "created" event.
     */
    public function created(Currency $currency): void
    {
        if ($currency->is_default) {
            Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
            defaultCurrency(true);
        }
    }

    /**
     * Handle the Currency "updated" event.
     */
    public function updated(Currency $currency): void
    {
        if ($currency->is_default) {
            Currency::where('id', '!=', $currency->id)->update(['is_default' => false]);
            defaultCurrency(true);
        }
    }

    /**
     * Handle the Currency "deleted" event.
     */
    public function deleted(Currency $currency): void
    {
        if ($currency->is_default) {
            $firstCurrency = Currency::first();
            if ($firstCurrency) {
                $firstCurrency->is_default = true;
                $firstCurrency->save();
            }
            defaultCurrency(true);
        }
    }
}
