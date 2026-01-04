<?php

namespace Database\Seeders;

use App\Models\Shop\Shop;
use Illuminate\Database\Seeder;

class DummyShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shop::factory()->count(100)->create();
    }
}
