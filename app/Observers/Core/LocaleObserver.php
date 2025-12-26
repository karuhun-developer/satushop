<?php

namespace App\Observers\Core;

use App\Models\Core\Locale;

class LocaleObserver
{
    /**
     * Handle the Locale "created" event.
     */
    public function created(Locale $locale): void
    {
        if ($locale->is_default) {
            // Set all other locales to not default
            Locale::where('id', '!=', $locale->id)->update(['is_default' => false]);
            defaultLocale(true);
        }
    }

    /**
     * Handle the Locale "updated" event.
     */
    public function updated(Locale $locale): void
    {
        if ($locale->is_default) {
            // Set all other locales to not default
            Locale::where('id', '!=', $locale->id)->update(['is_default' => false]);
            defaultLocale(true);
        }
    }

    /**
     * Handle the Locale "deleted" event.
     */
    public function deleted(Locale $locale): void
    {
        if ($locale->is_default) {
            // If the deleted locale was default, set another locale as default
            $anotherLocale = Locale::first();
            if ($anotherLocale) {
                $anotherLocale->is_default = true;
                $anotherLocale->save();
            }

            defaultLocale(true);
        }
    }
}
