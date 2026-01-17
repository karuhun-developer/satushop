<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Transaction\Transaction;
use App\Traits\WithMediaCollection;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use WithMediaCollection;

    public function show(Request $request, Transaction $transaction)
    {
        if (! $request->hasValidSignature() && $transaction->user_id !== auth()->id()) {
            abort(401);
        }

        // Load necessary relationships
        $transaction->load([
            'media',
            'payment',
            'transactionShops.shop',
            'transactionShops.transactionDetails.productFlat.media',
        ]);

        // Load media
        $transaction->transactionShops->each(function ($transactionShop) {
            $transactionShop->transactionDetails->each(function ($detail) {
                $detail->productFlat->images_1 = $detail->productFlat->getFirstMediaUrl('images_1');
                $detail->productFlat->makeHidden('media');
            });
        });

        // Load payment proof if exists
        if ($transaction->payment_method === 'bank_transfer') {
            $transaction->payment_proof = $transaction->getFirstMediaUrl('payment_proof');
        }

        return inertia('main/transaction/Show', [
            'transaction' => $transaction,
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_proof' => 'required|image|max:5120', // max 5MB
        ]);

        $this->saveMedia(
            model: $transaction,
            file: $request->file('payment_proof'),
            collection: 'payment_proof',
        );

        return back();
    }
}
