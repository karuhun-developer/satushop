<?php

namespace App\Actions\Main\Checkout;

use App\Enums\ShippingStatusEnum;
use App\Models\Catalog\ProductFlat;
use App\Models\Payment\Payment;
use App\Models\Shop\Shop;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionShop;
use App\Services\MidtransService;
use App\Traits\WithGenerateReference;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class StoreCheckoutAction
{
    use WithGenerateReference;

    public function __construct(
        public readonly MidtransService $midtransService,
    ) {}

    /**
     * Handle the action.
     */
    public function handle(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Check if user is authenticated and set user_id
            if (auth()->check()) {
                $data['user_id'] = auth()->id();
            }

            // Generate a unique reference for the transaction
            $reference = $this->generateReference(
                model: new Transaction,
                prefix: 'TRX-',
            );

            // Create the transaction record
            $data['reference'] = $reference['code'];
            $data['ref_number'] = $reference['number'];
            $transaction = Transaction::create($data);

            // Loop through shipping shops
            $grandTotal = 0;
            $shippingTotal = 0;
            foreach ($data['shipping'] as $shopId => $shipping) {
                // Check if shop id is valid
                $shop = Shop::where('id', $shopId)->firstOrFail();
                $transactionShop = TransactionShop::create([
                    'transaction_id' => $transaction->id,
                    'shop_id' => $shop->id,
                    'receipt_number' => null,
                    'courier' => $shipping['courier'],
                    'shipment_details' => json_encode($shipping),
                    'amount' => 0,
                    'shipping_cost' => $shipping['cost'],
                    'total_amount' => 0,
                    'shipping_status' => ShippingStatusEnum::PENDING,
                ]);

                // Loop through items for the shop
                $shopTotal = 0;
                foreach ($data['items'][$shop->id] as $productId => $item) {
                    // Check if product id is valid
                    $flat = ProductFlat::where('id', $productId)->firstOrFail();

                    // Check if product belongs to the shop
                    if ($flat->product->shop_id != $shopId) {
                        throw new \Exception('Product does not belong to the specified shop.');
                    }

                    // Check if stock is sufficient
                    // if ($flat->stock < $item['quantity']) {
                    //     throw new \Exception('Insufficient stock for product: ' . $flat->product->name);
                    // }

                    // Calculate item total
                    $itemTotal = $flat->price * $item['quantity'];

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'transaction_shop_id' => $transactionShop->id,
                        'product_flat_id' => $flat->id,
                        'product_details' => json_encode($flat->toArray()),
                        'price' => $flat->price,
                        'quantity' => $item['quantity'],
                        'total' => $itemTotal,
                    ]);

                    // Calculate shop total
                    $shopTotal += $itemTotal;
                }

                // Update transaction shop totals
                $transactionShop->amount = $shopTotal;
                $transactionShop->total_amount = $shopTotal + $transactionShop->shipping_cost;
                $transactionShop->save();

                // Update grand total
                $shippingTotal += $transactionShop->shipping_cost;
                $grandTotal += $transactionShop->total_amount;
            }

            // Update transaction grand total
            $transaction->amount = $grandTotal;
            $transaction->shipping_cost = $shippingTotal;
            $transaction->total_amount = $grandTotal + $shippingTotal;
            $transaction->save();

            // Check payment type
            if ($data['payment_method'] === 'midtrans') {
                // Create payment record
                $orderId = uniqid().time();
                $payment = Payment::create([
                    'driver' => $data['payment_method'],
                    'payable_type' => Transaction::class,
                    'payable_id' => $transaction->id,
                    'order_id' => $orderId,
                    'transaction_id' => null,
                    'payment_type' => $data['gateway_type'],
                    // AUTO_GENERATED
                    'account_number' => 'AUTO_GENERATED',
                    'channel' => $data['gateway_bank'],
                    'expired_at' => now()->addHours(24),
                    'amount' => $transaction->total_amount,
                ]);

                // Check gateway type
                switch ($data['gateway_type']) {
                    case 'bank_transfer':
                        $midtrans = $this->midtransService->createBankTransfer(
                            orderId: $payment->order_id,
                            bank: $payment->channel,
                            amount: $payment->amount,
                        );
                        break;
                    case 'qris':
                        $midtrans = $this->midtransService->createQris(
                            orderId: $payment->order_id,
                            amount: $payment->amount,
                        );
                        break;
                    default:
                        throw new \Exception('Unsupported gateway type');
                }

                // Throw an exception if Midtrans transaction creation failed
                if (! $midtrans['successful']) {
                    throw new \Exception('Failed to create Midtrans transaction: '.$midtrans['message']);
                }

                // Update payment with Midtrans details
                $payment->transaction_id = $midtrans['transaction_id'];
                $payment->account_number = $midtrans['account'];
                $payment->account_code = $midtrans['code'] ?? null;
                $payment->save();
            }

            // Send checkout notification
            if (auth()->check()) {
                auth()->user()->notify(
                    new \App\Notifications\CheckoutNotification($transaction)
                );
            } else {
                Notification::route('mail', $transaction->email)
                    ->notify(
                        new \App\Notifications\CheckoutNotification($transaction)
                    );
            }

            return $transaction;
        });
    }
}
