<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Shop extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;

    protected static function newFactory()
    {
        return \Database\Factories\ShopFactory::new();
    }

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'address',
        'postal_code',
        'latitude',
        'longitude',
        'rating',
        'total_reviews',
        'rajaongkir_province_id',
        'rajaongkir_city_id',
        'rajaongkir_district_id',
        'status',
    ];

    protected $casts = [
        'rating' => 'float',
        'status' => \App\Enums\ValidationEnum::class,
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

    // Media convertions
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('avif')
            // ->nonQueued()
            ->format('avif') // Specify AVIF format
            ->sharpen(10);
    }

    public function translations()
    {
        return $this->hasMany(ShopTranslation::class);
    }

    public function allUsers()
    {
        return $this->hasMany(ShopUser::class);
    }

    public function users()
    {
        return $this->hasMany(ShopUser::class)->where('is_owner', false);
    }

    public function owner()
    {
        return $this->hasOne(ShopUser::class)->where('is_owner', true);
    }

    public function transactionShops()
    {
        return $this->hasMany(\App\Models\Transaction\TransactionShop::class);
    }
}
