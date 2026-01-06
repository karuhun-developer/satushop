<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'shop_id',
        'attribute_family_id',
        'type',
        'sku',
    ];

    protected $casts = [
        'type' => \App\Enums\ProductTypeEnum::class,
    ];

    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop\Shop::class);
    }

    public function attributeFamily()
    {
        return $this->belongsTo(\App\Models\Attribute\AttributeFamily::class);
    }

    public function flats()
    {
        return $this->hasMany(ProductFlat::class);
    }

    public function flatIndividual()
    {
        return $this->hasOne(ProductFlat::class)->where('visible_individually', false);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
