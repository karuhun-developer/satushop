<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class ProductFlat extends Model
{
    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'slug',
        'short_description',
        'description',
        'meta_data',
        'price',
        'special_price',
        'special_price_start',
        'special_price_end',
        'weight',
        'length',
        'width',
        'height',
        'diameter',
        'rating',
        'visible_individually',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'special_price_start' => 'datetime',
        'special_price_end' => 'datetime',
        'price' => 'float',
        'special_price' => 'float',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
        'diameter' => 'float',
        'visible_individually' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'parent_product_id');
    }

    public function partOfProduct()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_product_id');
    }

    public function attributes()
    {
        return $this->hasMany(\App\Models\Catalog\ProductAttribute::class);
    }

    public function translations()
    {
        return $this->hasMany(\App\Models\Catalog\ProductFlatTranslation::class);
    }
}
