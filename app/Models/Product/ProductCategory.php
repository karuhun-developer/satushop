<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class ProductCategory extends Model implements HasMedia
{
    use HasSlug, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
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
}
