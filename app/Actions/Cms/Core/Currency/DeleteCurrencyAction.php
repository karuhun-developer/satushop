<?php

namespace App\Actions\Cms\Core\Currency;

use App\Models\Core\Currency;

class DeleteCurrencyAction
{
    /**
     * Handle the action.
     */
    public function handle(Currency $currency): ?bool
    {
        return $currency->delete();
    }
}
