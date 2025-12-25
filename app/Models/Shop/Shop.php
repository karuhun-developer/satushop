<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Shop extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'address',
        'description',
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

    // Get the activity log options.
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
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
}
