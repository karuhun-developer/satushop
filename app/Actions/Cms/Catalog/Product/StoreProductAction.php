<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Enums\ProductTypeEnum;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductAttribute;
use App\Models\Catalog\ProductFlat;
use App\Models\Catalog\ProductVariant;
use App\Traits\WithMediaCollection;

class StoreProductAction
{
    use WithMediaCollection;

    /**
     * Handle the action.
     */
    public function handle(array $data): ProductFlat
    {
        $product = Product::create($data);

        // Check product type
        switch ($product->type) {
            case ProductTypeEnum::SIMPLE:
                $productFlat = ProductFlat::create([
                    'product_id' => $product->id,
                    'sku' => 'sku-'.$product->id,
                    'name' => 'New Product '.$product->id,
                    'meta_data' => [
                        'meta_title' => 'New Product '.$product->id,
                        'meta_keywords' => 'product, new product',
                        'meta_description' => 'This is a new product.',
                    ],
                    'visible_individually' => true,
                    'type' => ProductTypeEnum::SIMPLE,
                    'stock' => 0,
                ]);
                break;
            case ProductTypeEnum::VARIABLE:
                $productFlat = ProductFlat::create([
                    'product_id' => $product->id,
                    'sku' => 'sku-'.$product->id,
                    'name' => 'New Product '.$product->id,
                    'meta_data' => [
                        'meta_title' => 'New Product '.$product->id,
                        'meta_keywords' => 'product, new product',
                        'meta_description' => 'This is a new product.',
                    ],
                    'visible_individually' => true,
                    'type' => ProductTypeEnum::VARIABLE,
                ]);
                $this->createVariantProducts($product);
                break;
            case ProductTypeEnum::BUNDLE:
                // Handle downloadable product specific logic here
                $productFlat = ProductFlat::create([
                    'product_id' => $product->id,
                    'sku' => 'sku-'.$product->id,
                    'name' => 'New Product '.$product->id,
                    'meta_data' => [
                        'meta_title' => 'New Product '.$product->id,
                        'meta_keywords' => 'product, new product',
                        'meta_description' => 'This is a new product.',
                    ],
                    'visible_individually' => true,
                    'type' => ProductTypeEnum::BUNDLE,
                    'stock' => 0,
                ]);
                break;
        }

        return $productFlat;
    }

    private function createVariantProducts(Product $product): void
    {
        // Loop through attributes in the attribute family
        foreach ($product->attributeFamily->groups->load('attribute') as $group) {
            // Loop through options
            foreach ($group->attribute->options as $option) {
                // Create flat product entry
                $flat = $product->flats()->create([
                    'product_id' => $product->id,
                    'sku' => 'variant-sku-'.$product->sku.'-'.$group->attribute->code.'-'.$option->name,
                    'name' => 'Variant Product '.$product->sku.' '.$group->attribute->name.' '.$option->name,
                    'short_description' => 'This is a variant product of '.$product->sku.' with '.$group->attribute->name.' '.$option->name,
                    'description' => 'Detailed description for variant product of '.$product->sku.' with '.$group->attribute->name.' '.$option->name,
                    'meta_data' => [
                        'meta_title' => '',
                        'meta_keywords' => '',
                        'meta_description' => '',
                    ],
                    'visible_individually' => false,
                    'stock' => 0,
                    'type' => ProductTypeEnum::SIMPLE,
                ]);

                // Attatch attribute option to flat product
                ProductAttribute::firstOrCreate([
                    'product_id' => $product->id,
                    'product_flat_id' => $flat->id,
                    'attribute_id' => $group->attribute->id,
                    'attribute_option_id' => $option->id,
                ]);
            }
        }

        // Set default variant for the variable product
        $parentVariant = ProductFlat::query()
            ->where('product_id', $product->id)
            ->where('type', ProductTypeEnum::VARIABLE)
            ->first();
        $firstVariant = ProductFlat::query()
            ->where('product_id', $product->id)
            ->where('type', ProductTypeEnum::SIMPLE)
            ->first();

        ProductVariant::create([
            'product_id' => $product->id,
            'parent_product_id' => $parentVariant->id,
            'variant_product_id' => $firstVariant->id,
        ]);
    }
}
