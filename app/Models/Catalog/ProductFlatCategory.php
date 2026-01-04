<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductFlatCategory extends Model
{
    protected $fillable = [
        'product_id',
        'product_category_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
