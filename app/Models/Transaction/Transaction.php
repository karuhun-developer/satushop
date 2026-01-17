<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Transaction extends Model implements HasMedia
{
    use InteractsWithMedia;

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

    public function payment()
    {
        return $this->morphOne(\App\Models\Payment\Payment::class, 'payable');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function transactionShops()
    {
        return $this->hasMany(TransactionShop::class);
    }
}
