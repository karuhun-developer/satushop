<?php

namespace App\Actions\Cms\Catalog\ProductCategory;

use App\Models\Catalog\ProductCategory;

class DeleteProductCategoryAction
{
    /**
     * Handle the action.
     */
    public function handle(ProductCategory $productcategory): ?bool
    {
        return $productcategory->delete();
    }
}
