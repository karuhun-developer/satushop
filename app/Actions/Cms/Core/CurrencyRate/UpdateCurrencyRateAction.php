<?php

namespace App\Actions\Cms\Core\CurrencyRate;

use App\Models\Core\CurrencyRate;

class UpdateCurrencyRateAction
{
    /**
     * Handle the action.
     */
    public function handle(CurrencyRate $CurrencyRate, array $data): bool
    {
        return $CurrencyRate->update($data);
    }
}
