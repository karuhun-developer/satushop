<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Catalog\ProductFlat;

class ProductController extends Controller
{
    public function show(ProductFlat $product)
    {
        $product->load(
            'media',
            'variants',
            'variants.variantProduct.media',
            'variants.variantProduct.attributes',
            'variants.variantProduct.product.shop',
            'product.attributes.attribute',
            'product.attributes.attributeOption',
            'product.shop',
            'translations',
            'categories.productCategory',
        );

        // Load product images
        for ($i = 1; $i <= 8; $i++) {
            $product->{'image_'.$i} = $product->getFirstMediaUrl('image_'.$i);
        }

        // Load variant images
        foreach ($product->variants as $variant) {
            $variantProduct = $variant->variantProduct;
            for ($i = 1; $i <= 8; $i++) {
                $variantProduct->{'image_'.$i} = $variantProduct->getFirstMediaUrl('image_'.$i);
            }
        }

        $product->flat_attributes = $product->product->attributes->groupBy('attribute_id');

        return inertia('main/Product', [
            'product' => $product,
        ]);
    }
}
