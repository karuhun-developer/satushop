<?php

namespace App\Actions\Cms\Core\CurrencyRate;

use App\Models\Core\CurrencyRate;

class StoreCurrencyRateAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): CurrencyRate
    {
        return CurrencyRate::create($data);
    }
}
