<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    protected $fillable = [
        'target_currency_id',
        'rate',
    ];

    public function targetCurrency()
    {
        return $this->belongsTo(Currency::class, 'target_currency_id');
    }
}
