<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy(\App\Observers\Core\CurrencyObserver::class)]
class Currency extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function rates()
    {
        return $this->hasMany(CurrencyRate::class);
    }
}
