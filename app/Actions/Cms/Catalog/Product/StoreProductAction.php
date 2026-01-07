<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Enums\ProductTypeEnum;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFlat;
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
                    'sku' => 'sample-sku-'.$product->id,
                    'name' => 'New Product '.$product->id,
                    'meta_data' => [
                        'meta_title' => 'New Product '.$product->id,
                        'meta_keywords' => 'product, new product',
                        'meta_description' => 'This is a new product.',
                    ],
                    'visible_individually' => true,
                    'type' => ProductTypeEnum::SIMPLE,
                ]);
                break;
            case ProductTypeEnum::VARIABLE:
                $productFlat = ProductFlat::create([
                    'product_id' => $product->id,
                    'sku' => 'sample-sku-'.$product->id,
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
                    'sku' => 'sample-sku-'.$product->id,
                    'name' => 'New Product '.$product->id,
                    'meta_data' => [
                        'meta_title' => 'New Product '.$product->id,
                        'meta_keywords' => 'product, new product',
                        'meta_description' => 'This is a new product.',
                    ],
                    'visible_individually' => true,
                    'type' => ProductTypeEnum::BUNDLE,
                ]);
                break;
        }

        return $productFlat;
    }

    private function createVariantProducts(Product $product): void
    {
        // Loop through attributes in the attribute family
        foreach ($product->attributeFamily->attributes as $attribute) {
            // Loop through options
            foreach ($attribute->options as $option) {
                // Create flat product entry
                $product->flats()->create([
                    'product_id' => $product->id,
                    'sku' => 'variant-sku-'.$product->sku.'-'.$attribute->code.'-'.$option->name,
                    'name' => 'Variant Product '.$product->sku.' '.$attribute->name.' '.$option->name,
                    'short_description' => 'This is a variant product of '.$product->sku.' with '.$attribute->name.' '.$option->name,
                    'description' => 'Detailed description for variant product of '.$product->sku.' with '.$attribute->name.' '.$option->name,
                    'meta_data' => [
                        'meta_title' => '',
                        'meta_keywords' => '',
                        'meta_description' => '',
                    ],
                    'visible_individually' => false,
                    'type' => ProductTypeEnum::SIMPLE,
                ]);
            }
        }
    }
}
