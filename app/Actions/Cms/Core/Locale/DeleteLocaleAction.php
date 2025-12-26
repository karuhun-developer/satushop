<?php

namespace App\Actions\Cms\Core\Locale;

use App\Models\Core\Locale;

class DeleteLocaleAction
{
    /**
     * Handle the action.
     */
    public function handle(Locale $locale): ?bool
    {
        return $locale->delete();
    }
}
