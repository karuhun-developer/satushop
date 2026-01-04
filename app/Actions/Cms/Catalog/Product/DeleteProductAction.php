<?php

namespace App\Actions\Cms\Catalog\Product;

use App\Models\Catalog\ProductFlat;

class DeleteProductAction
{
    /**
     * Handle the action.
     */
    public function handle(ProductFlat $product): ?bool
    {
        return $product->delete();
    }
}
