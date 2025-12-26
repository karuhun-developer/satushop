<?php

namespace App\Actions\Cms\Core\Currency;

use App\Models\Core\Currency;

class UpdateCurrencyAction
{
    /**
     * Handle the action.
     */
    public function handle(Currency $Currency, array $data): bool
    {
        return $Currency->update($data);
    }
}
