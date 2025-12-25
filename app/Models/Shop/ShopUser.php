<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ShopUser extends Model
{
    protected $fillable = [
        'shop_id',
        'user_id',
        'is_owner',
    ];

    protected $casts = [
        'is_owner' => 'boolean',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
