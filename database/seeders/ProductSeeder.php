<?php

namespace Database\Seeders;

use App\Enums\ProductTypeEnum;
use App\Models\Attribute\AttributeFamily;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductCategory;
use App\Models\Catalog\ProductFlat;
use App\Models\Shop\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
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

        // 3. Ensure Categories Exist
        $categories = ['Electronics', 'Fashion', 'Home & Living', 'Sports', 'Books'];
        $categoryIds = [];
        foreach ($categories as $catName) {
            $cat = ProductCategory::firstOrCreate([
                'name' => $catName,
            ]);
            $categoryIds[] = $cat->id;
        }

        // 4. Create Products
        $this->command->info('Creating products for Default Shop...');

        for ($i = 1; $i <= 50; $i++) {
            $name = 'Product '.$i.' '.fake()->word();
            $price = fake()->numberBetween(10000, 1000000);
            $rating = fake()->randomFloat(1, 3, 5);
            $soldCount = fake()->numberBetween(0, 500);

            // Randomly assign category first to determine family
            $randomCatId = $categoryIds[array_rand($categoryIds)];
            $categorySlug = ProductCategory::find($randomCatId)->slug;

            // Determine family based on category (mapping for demo)
            if (in_array($categorySlug, ['electronics', 'computers', 'phones'])) {
                $familyId = $electronicsFamily->id;
            } elseif (in_array($categorySlug, ['fashion', 'clothing', 'shoes'])) {
                $familyId = $clothingFamily->id;
            } else {
                $familyId = (rand(0, 1) ? $electronicsFamily->id : $clothingFamily->id);
            }

            // Create Product
            $product = Product::create([
                'shop_id' => $shop->id,
                'attribute_family_id' => $familyId,
                'type' => ProductTypeEnum::SIMPLE,
                'sku' => Str::slug($name).'-'.Str::random(6),
            ]);

            // Create Product Flat
            $flat = ProductFlat::create([
                'product_id' => $product->id,
                'sku' => $product->sku,
                'name' => $name,
                'slug' => Str::slug($name).'-'.$product->id,
                'price' => $price,
                'type' => ProductTypeEnum::SIMPLE,
                'rating' => $rating,
                'sold_count' => $soldCount,
                'visible_individually' => true,
                'short_description' => fake()->sentence(),
                'description' => fake()->paragraph(),
            ]);

            // Attach category
            $flat->categories()->create([
                'product_category_id' => $randomCatId,
            ]);
        }

        $this->command->info('50 Products created successfully.');
    }
}
