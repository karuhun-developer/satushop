<?php

namespace Database\Seeders;

use App\Models\Shop\Shop;
use App\Models\Shop\ShopTranslation;
use App\Models\Shop\ShopUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class DefaultShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default shop
        $superadmin = User::where('email', 'superadmin@superadmin.com')->first();

        if ($superadmin) {
            $shop = Shop::firstOrCreate(
                [
                    'name' => 'Default Shop',
                ],
                [
                    'phone' => '+621234567890',
                    'email' => 'default@shop.com',
                    'address' => '123 Default St, Default City, Default Country',
                    'postal_code' => '40115',
                    'latitude' => '-6.914744',
                    'longitude' => '107.609810',
                    'rajaongkir_province_id' => 5,
                    'rajaongkir_city_id' => 55,
                    'rajaongkir_district_id' => 423,
                    'status' => true,
                ]
            );
            ShopUser::firstOrCreate([
                'shop_id' => $shop->id,
                'user_id' => $superadmin->id,
                'is_owner' => true,
            ]);

            // Add translation for default shop
            foreach (getLocales() as $locale) {
                ShopTranslation::firstOrCreate([
                    'shop_id' => $shop->id,
                    'locale' => $locale->code,
                ], [
                    'name' => $locale->code === 'en' ? 'Default Shop' : 'Toko Default',
                    'description' => $locale->code === 'en' ? 'This is the default shop.' : 'Ini adalah toko default.',
                ]);
            }
        }
    }
}
