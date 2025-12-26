<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ShopTranslation extends Model
{
    protected $fillable = [
        'shop_id',
        'locale',
        'name',
        'description',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
