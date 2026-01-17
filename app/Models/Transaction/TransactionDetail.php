<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id',
        'transaction_shop_id',
        'product_flat_id',
        'product_details',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'product_details' => 'array',
        'price' => 'float',
        'quantity' => 'integer',
        'total' => 'float',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionShop()
    {
        return $this->belongsTo(TransactionShop::class);
    }

    public function productFlat()
    {
        return $this->belongsTo(\App\Models\Catalog\ProductFlat::class);
    }
}
