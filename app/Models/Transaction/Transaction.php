<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'reference',
        'ref_number',
        'name',
        'email',
        'phone',
        'address',
        'postcode',
        'rajaongkir_province_id',
        'rajaongkir_city_id',
        'rajaongkir_district_id',
        'notes',
        'payment_method',
        'amount',
        'shipping_cost',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'amount' => 'float',
        'shipping_cost' => 'float',
        'total_amount' => 'float',
        'status' => \App\Enums\PaymentStatusEnum::class,
    ];
}
