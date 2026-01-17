<?php

namespace App\Notifications;

use App\Models\Transaction\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

// use Illuminate\Contracts\Queue\ShouldQueue
class PaymentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public bool $isSuccess,
        public readonly Transaction $transaction,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->isSuccess ? 'Payment Successful' : 'Payment Failed';
        $status = $this->isSuccess ? 'successful' : 'failed';

        return (new MailMessage)
            ->subject($subject.' #'.$this->transaction->reference)
            ->line('Your payment for transaction #'.$this->transaction->reference.' with amount of '.numberToCurrency($this->transaction->total_amount).' has been '.$status.'.')
            ->line('You can view the details of your transaction by clicking the button below.')
            ->action('Detail Transaction', URL::signedRoute('transaction.show', [
                'transaction' => $this->transaction->id,
            ]))
            ->line('Thank you for shopping with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
