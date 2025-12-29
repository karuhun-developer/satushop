<?php

namespace App\Actions\Cms\Core\Currency;

use App\Models\Core\Currency;

class UpdateCurrencyAction
{
    /**
     * Handle the action.
     */
    public function handle(Currency $currency, array $data): bool
    {
        return $currency->update($data);
    }
}
