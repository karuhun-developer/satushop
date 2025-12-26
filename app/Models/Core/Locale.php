<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(\App\Observers\Core\LocaleObserver::class)]
class Locale extends Model
{
    protected $fillable = [
        'code',
        'name',
        'direction',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];
}
