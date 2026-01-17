<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class TransactionShipment extends Model
{
    protected $fillable = [
        'transaction_id',
        'transaction_shop_id',
        'title',
        'notes',
        'date',
        'is_arrived',
    ];

    protected $casts = [
        'date' => 'datetime',
        'is_arrived' => 'boolean',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionShop()
    {
        return $this->belongsTo(TransactionShop::class);
    }
}
