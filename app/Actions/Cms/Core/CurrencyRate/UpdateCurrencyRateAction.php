<?php

namespace App\Actions\Cms\Core\CurrencyRate;

use App\Models\Core\CurrencyRate;

class UpdateCurrencyRateAction
{
    /**
     * Handle the action.
     */
    public function handle(CurrencyRate $currencyRate, array $data): bool
    {
        return $currencyRate->update($data);
    }
}
