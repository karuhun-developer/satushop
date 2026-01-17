<?php

namespace App\Actions\Api\Callback;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment\Payment;
use App\Models\Transaction\Transaction;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class HandleMidtransCallbackAction
{
    public function __construct(
        public readonly MidtransService $midtransService,
    ) {}

    public function handle(array $payload)
    {
        Log::info('Midtrans Callback Received', [
            'request' => $payload,
        ]);

        // Validate signature key
        if (! $this->midtransService->validateSignature(
            $payload['order_id'],
            $payload['status_code'],
            $payload['gross_amount'],
            $payload['signature_key'],
        )) {
            throw new \Exception('Invalid signature key', 403);
        }

        // Get the order ID without the suffix
        $orderId = $payload['order_id'];
        $orderId = explode('-', $orderId)[0];

        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            throw new \Exception('Transaction not found', 404);
        }

        if ($payment->paid_at || ($payment->expires_at && $payment->expires_at?->isPast())) {
            throw new \Exception('Transaction already paid or expired', 400);
        }

        $this->handlePaymentStatus($payload['transaction_status'], $payment);

        return $payment;
    }

    protected function handlePaymentStatus($transactionStatus, Payment $payment)
    {
        switch ($transactionStatus) {
            case 'capture':
                $payment->update([
                    'paid_at' => now(),
                ]);
                $this->capture($payment);
                break;
            case 'settlement':
                $payment->update([
                    'paid_at' => now(),
                ]);
                $this->capture($payment);
                break;
            case 'pending':
                break;
            case 'deny':
                $payment->update([
                    'expires_at' => now(),
                ]);
                $this->deny($payment);
                break;
            case 'expire':
                $payment->update([
                    'expires_at' => now(),
                ]);
                $this->deny($payment);
                break;
            case 'cancel':
                $payment->update([
                    'expires_at' => now(),
                ]);
                $this->deny($payment);
                break;
        }
    }

    protected function capture(Payment $payment)
    {
        if ($payment->payable_type === Transaction::class) {
            $this->handleAfterTransactionPaid($payment->payable);
        }
    }

    protected function handleAfterTransactionPaid(Transaction $transaction)
    {
        $transaction->update([
            'status' => PaymentStatusEnum::SETTLEMENT,
        ]);

        $this->sendNotification($transaction, true);
    }

    protected function deny(Payment $payment)
    {
        if ($payment->payable_type === Transaction::class) {
            $transaction = $payment->payable;
            $transaction->update([
                'status' => PaymentStatusEnum::DENY,
            ]);

            $this->sendNotification($transaction, false);
        }
    }

    protected function sendNotification(Transaction $transaction, bool $isSuccess)
    {
        if ($transaction->user) {
            $transaction->user->notify(
                new \App\Notifications\PaymentNotification(
                    isSuccess: $isSuccess,
                    transaction: $transaction,
                )
            );
        } else {
            Notification::route('mail', $transaction->email)
                ->notify(
                    new \App\Notifications\PaymentNotification(
                        isSuccess: $isSuccess,
                        transaction: $transaction,
                    )
                );
        }
    }
}
