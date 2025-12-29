<?php

namespace App\Actions\Cms\Core\Locale;

use App\Models\Core\Locale;

class UpdateLocaleAction
{
    /**
     * Handle the action.
     */
    public function handle(Locale $locale, array $data): bool
    {
        return $locale->update($data);
    }
}
