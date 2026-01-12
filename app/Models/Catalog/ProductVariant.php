<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'parent_product_id',
        'variant_product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function parentProduct()
    {
        return $this->belongsTo(ProductFlat::class, 'parent_product_id');
    }

    public function variantProduct()
    {
        return $this->belongsTo(ProductFlat::class, 'variant_product_id');
    }
}
