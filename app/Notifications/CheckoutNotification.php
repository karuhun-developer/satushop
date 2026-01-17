<?php

namespace App\Notifications;

use App\Models\Transaction\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

// use Illuminate\Contracts\Queue\ShouldQueue
class CheckoutNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
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
        return (new MailMessage)
            ->subject('Checkout Successful #'.$this->transaction->reference.'!! Process your order now.')
            ->line('Your checkout was successful with amount '.numberToCurrency($this->transaction->total_amount).'.')
            ->line('Please click the button below to process your order.')
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
