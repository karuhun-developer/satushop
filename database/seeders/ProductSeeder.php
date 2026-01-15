<?php

namespace Database\Seeders;

use App\Actions\Cms\Catalog\Product\StoreProductAction;
use App\Enums\ProductTypeEnum;
use App\Models\Attribute\AttributeFamily;
use App\Models\Shop\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(StoreProductAction $storeProductAction): void
    {
        // 1. Get Default Shop
        $shop = Shop::where('name', 'Default Shop')->first();
        if (! $shop) {
            $this->command->error('Default Shop not found. Please run DefaultShopSeeder first.');

            return;
        }

        // 2. Get Attribute Families
        $electronicsFamily = AttributeFamily::where('code', 'electronics')->first();
        $clothingFamily = AttributeFamily::where('code', 'clothing')->first();

        if (! $electronicsFamily || ! $clothingFamily) {
            $this->command->error('Attribute families not found. Please run AttributeSeeder first.');

            return;
        }

        for ($i = 1; $i <= 50; $i++) {
            $name = 'Product '.$i.' '.fake()->word();
            $familyId = (rand(0, 1) ? $electronicsFamily->id : $clothingFamily->id);

            $sku = Str::slug($name).'-'.Str::random(6);
            $type = fake()->boolean(80) ? ProductTypeEnum::SIMPLE : ProductTypeEnum::VARIABLE;

            // Create Product using Action
            $storeProductAction->handle([
                'shop_id' => $shop->id,
                'attribute_family_id' => $familyId,
                'type' => $type,
                'sku' => $sku,
            ]);
        }
    }
}
