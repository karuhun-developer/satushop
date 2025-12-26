<?php

namespace App\Actions\Cms\Core\Locale;

use App\Models\Core\Locale;

class StoreLocaleAction
{
    /**
     * Handle the action.
     */
    public function handle(array $data): Locale
    {
        return Locale::create($data);
    }
}
