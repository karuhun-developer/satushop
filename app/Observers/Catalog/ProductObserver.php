<?php

namespace App\Observers\Catalog;

use App\Enums\ProductTypeEnum;
use App\Models\Catalog\Product;
use App\Models\Catalog\ProductFlat;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void {}

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        if ($product->product->type === ProductTypeEnum::VARIABLE) {
            // Check if there is visible individually product
            $variants = ProductFlat::where('product_id', $product->id)->where('visible_individually', true)->count();

            if ($variants === 0) {
                // Set first variant to visible individually
                $firstVariant = ProductFlat::where('product_id', $product->id)->orderBy('id', 'asc')->first();
                if ($firstVariant) {
                    $firstVariant->visible_individually = true;
                    $firstVariant->save();
                }
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void {}
}
