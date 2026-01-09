<?php

namespace Database\Seeders;

use App\Models\Attribute\Attribute;
use App\Models\Attribute\AttributeFamily;
use App\Models\Attribute\AttributeGroup;
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
            'code' => 'electronics',
            'name' => 'Electronics',
        ]);
        $clothingFamily = AttributeFamily::firstOrCreate([
            'code' => 'clothing',
            'name' => 'Clothing',
        ]);

        // Attribute: Color
        $color = Attribute::firstOrCreate([
            'code' => 'color',
            'name' => 'Color',
            'order' => 1,
            'status' => true,
        ]);
        $color->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Color',
        ]);
        $color->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Warna',
        ]);

        // Attribute: Size
        $size = Attribute::firstOrCreate([
            'code' => 'size',
            'name' => 'Size',
            'order' => 2,
            'status' => true,
        ]);
        $size->translations()->firstOrCreate([
            'locale' => 'en',
            'name' => 'Size',
        ]);
        $size->translations()->firstOrCreate([
            'locale' => 'id',
            'name' => 'Ukuran',
        ]);

        // Color options (Red, Blue, Green)
        $redOption = $color->options()->firstOrCreate([
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

        $blueOption = $color->options()->firstOrCreate([
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

        $greenOption = $color->options()->firstOrCreate([
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
        $smallOption = $size->options()->firstOrCreate([
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

        $mediumOption = $size->options()->firstOrCreate([
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

        $largeOption = $size->options()->firstOrCreate([
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

        // Link attributes to families via attribute groups
        AttributeGroup::firstOrCreate([
            'attribute_family_id' => $clothingFamily->id,
            'attribute_id' => $color->id,
        ]);
        AttributeGroup::firstOrCreate([
            'attribute_family_id' => $clothingFamily->id,
            'attribute_id' => $size->id,
        ]);
        AttributeGroup::firstOrCreate([
            'attribute_family_id' => $electronicsFamily->id,
            'attribute_id' => $color->id,
        ]);
    }
}
