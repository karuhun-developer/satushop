<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'postcode',
        'rajaongkir_province_id',
        'rajaongkir_city_id',
        'rajaongkir_district_id',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
