<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    public function show($transactionId)
    {
        // Logic to retrieve and display the transaction details
        return inertia('main/TransactionShow', [
            'transactionId' => $transactionId,
        ]);
    }
}
