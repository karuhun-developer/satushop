<?php

namespace App\Actions\Cms\Core\CurrencyRate;

use App\Models\Core\CurrencyRate;

class DeleteCurrencyRateAction
{
    /**
     * Handle the action.
     */
    public function handle(CurrencyRate $currencyrate): ?bool
    {
        return $currencyrate->delete();
    }
}
