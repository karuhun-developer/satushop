<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = [
        'product_id',
        'product_flat_id',
        'attribute_id',
        'attribute_option_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productFlat()
    {
        return $this->belongsTo(ProductFlat::class);
    }

    public function attribute()
    {
        return $this->belongsTo(\App\Models\Attribute\Attribute::class);
    }

    public function attributeOption()
    {
        return $this->belongsTo(\App\Models\Attribute\AttributeOption::class);
    }
}
