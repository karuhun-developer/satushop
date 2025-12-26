<?php

namespace App\Actions\Cms\Core\Currency;

use App\Models\Core\Currency;

class StoreCurrencyAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Currency
    {
        return Currency::create($data);
    }
}
