<?php

namespace Database\Seeders;

use App\Models\Attribute\AttributeFamily;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create attributes family and attributes with translations and options
        $electronicsFamily = AttributeFamily::firstOrCreate([
            'name' => 'Electronics',
        ]);
        $clothingFamily = AttributeFamily::firstOrCreate([
            'name' => 'Clothing',
        ]);

        // Attribute: Color for Electronics
        $colorAttribute = $electronicsFamily->attributes()->firstOrCreate([
            'name' => 'Color',
            'order' => 1,
            'status' => true,
        ]);
        $colorAttribute->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Color',
        ]);
        $colorAttribute->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Warna',
        ]);

        // Attribute: Size for Electronics
        $sizeAttribute = $electronicsFamily->attributes()->firstOrCreate([
            'name' => 'Size',
            'order' => 2,
            'status' => true,
        ]);
        $sizeAttribute->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Size',
        ]);
        $sizeAttribute->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Ukuran',
        ]);

        // Attribute: Color for Clothing
        $clothingColorAttribute = $clothingFamily->attributes()->firstOrCreate([
            'name' => 'Color',
            'order' => 1,
            'status' => true,
        ]);
        $clothingColorAttribute->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Color',
        ]);
        $clothingColorAttribute->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Warna',
        ]);

        // Color options (Red, Blue, Green)
        $redOption = $colorAttribute->options()->firstOrCreate([
            'name' => 'Red',
            'order' => 1,
            'status' => true,
        ]);
        $redOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Red',
        ]);
        $redOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Merah',
        ]);

        $blueOption = $colorAttribute->options()->firstOrCreate([
            'name' => 'Blue',
            'order' => 2,
            'status' => true,
        ]);
        $blueOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Blue',
        ]);
        $blueOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Biru',
        ]);

        $greenOption = $colorAttribute->options()->firstOrCreate([
            'name' => 'Green',
            'order' => 3,
            'status' => true,
        ]);
        $greenOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Green',
        ]);
        $greenOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Hijau',
        ]);

        // Size options (Small, Medium, Large)
        $smallOption = $sizeAttribute->options()->firstOrCreate([
            'name' => 'Small',
            'order' => 1,
            'status' => true,
        ]);
        $smallOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Small',
        ]);
        $smallOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Kecil',
        ]);

        $mediumOption = $sizeAttribute->options()->firstOrCreate([
            'name' => 'Medium',
            'order' => 2,
            'status' => true,
        ]);
        $mediumOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Medium',
        ]);
        $mediumOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Sedang',
        ]);

        $largeOption = $sizeAttribute->options()->firstOrCreate([
            'name' => 'Large',
            'order' => 3,
            'status' => true,
        ]);
        $largeOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Large',
        ]);
        $largeOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Besar',
        ]);

        // Clothing color options (Black, White)
        $blackOption = $clothingColorAttribute->options()->firstOrCreate([
            'name' => 'Black',
            'order' => 1,
            'status' => true,
        ]);
        $blackOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Black',
        ]);
        $blackOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Hitam',
        ]);

        $whiteOption = $clothingColorAttribute->options()->firstOrCreate([
            'name' => 'White',
            'order' => 2,
            'status' => true,
        ]);
        $whiteOption->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'White',
        ]);
        $whiteOption->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Putih',
        ]);
    }
}
