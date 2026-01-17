<?php

namespace App\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductFlat extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia;

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
        'stock',
        'type',
        'rating',
        'sold_count',
        'visible_individually',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'special_price_start' => 'datetime',
        'special_price_end' => 'datetime',
        'special_price' => 'float',
        'weight' => 'float',
        'length' => 'float',
        'width' => 'float',
        'height' => 'float',
        'diameter' => 'float',
        'stock' => 'integer',
        'rating' => 'float',
        'sold_count' => 'integer',
        'visible_individually' => 'boolean',
        'type' => \App\Enums\ProductTypeEnum::class,
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->usingSuffixGenerator(
                fn (string $slug, int $iteration) => bin2hex(random_bytes(4))
            );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'parent_product_id');
    }

    public function firstVariant()
    {
        return $this->hasOne(ProductVariant::class, 'parent_product_id')->oldestOfMany();
    }

    public function partOfProduct()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_product_id');
    }

    public function attributes()
    {
        return $this->hasMany(\App\Models\Catalog\ProductAttribute::class, 'product_flat_id');
    }

    public function translations()
    {
        return $this->hasMany(\App\Models\Catalog\ProductFlatTranslation::class);
    }

    public function categories()
    {
        return $this->hasMany(ProductFlatCategory::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(\App\Models\Transaction\TransactionDetail::class);
    }
}
