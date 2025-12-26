<?php

namespace Database\Seeders;

use App\Models\Core\Currency;
use App\Models\Core\CurrencyRate;
use App\Models\Core\Locale;
use Illuminate\Database\Seeder;

class CoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Locales
        $en = Locale::firstOrCreate([
            'code' => 'en',
            'name' => 'English',
            'direction' => 'ltr',
            'is_default' => true,
        ]);
        $id = Locale::firstOrCreate([
            'code' => 'id',
            'name' => 'Indonesian',
            'direction' => 'ltr',
            'is_default' => false,
        ]);

        // Currencies
        $idr = Currency::firstOrCreate([
            'code' => 'IDR',
            'name' => 'Indonesian Rupiah',
            'is_default' => true,
        ]);
        $usd = Currency::firstOrCreate([
            'code' => 'USD',
            'name' => 'US Dollar',
            'is_default' => false,
        ]);

        // Currency Rates from IDR to USD
        CurrencyRate::firstOrCreate([
            'target_currency_id' => $usd->id,
            'rate' => 0.000067,
        ]);
    }
}
