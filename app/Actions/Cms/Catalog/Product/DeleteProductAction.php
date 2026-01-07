<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Enums\ProductTypeEnum;
use App\Models\Catalog\ProductFlat;

class DeleteProductAction
{
    /**
     * Handle the action.
     */
    public function handle(ProductFlat $product): ?bool
    {
        if ($product->type === ProductTypeEnum::VARIABLE) {
            return $product->product->delete();
        }

        if ($product->product->type === ProductTypeEnum::SIMPLE) {
            return $product->product->delete();
        }

        return $product->delete();
    }
}
