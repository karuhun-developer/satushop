<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductFlatCategory extends Model
{
    protected $fillable = [
        'product_flat_id',
        'product_category_id',
    ];

    public function productFlat()
    {
        return $this->belongsTo(ProductFlat::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
