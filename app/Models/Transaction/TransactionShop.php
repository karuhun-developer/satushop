<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class TransactionShop extends Model
{
    protected $fillable = [
        'transaction_id',
        'shop_id',
        'receipt_number',
        'courier',
        'shipment_details',
        'shipment_parameters',
        'amount',
        'shipping_cost',
        'total_amount',
        'shipping_status',
    ];

    protected $casts = [
        'shipment_details' => 'array',
        'shipment_parameters' => 'array',
        'amount' => 'float',
        'shipping_cost' => 'float',
        'total_amount' => 'float',
        'shipping_status' => \App\Enums\ShippingStatusEnum::class,
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop\Shop::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function transactionShipments()
    {
        return $this->hasMany(\App\Models\Transaction\TransactionShipment::class);
    }
}
