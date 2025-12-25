<?php

namespace Database\Seeders;

use App\Models\Shop\Shop;
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
            Shop::firstOrCreate(
                [
                    'name' => 'Default Shop',
                ],
                [
                    'phone' => '+621234567890',
                    'email' => 'default@shop.com',
                    'address' => '123 Default St, Default City, Default Country',
                    'description' => 'This is the default shop created by DefaultShopSeeder.',
                    'latitude' => '-6.914744',
                    'longitude' => '107.609810',
                    'rajaongkir_province_id' => 5,
                    'rajaongkir_city_id' => 55,
                    'rajaongkir_district_id' => 423,
                    'status' => true,
                ]
            );
            ShopUser::firstOrCreate([
                'shop_id' => 1,
                'user_id' => $superadmin->id,
                'is_owner' => true,
            ]);
        }
    }
}
