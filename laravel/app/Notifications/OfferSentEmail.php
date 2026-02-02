<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferSentEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $productRequested = $this->offer->productRequested;
        $toUser = $this->offer->toUser;
        
        return (new MailMessage)
            ->subject('Tu oferta ha sido enviada en Permuta2')
            ->view('emails.offer_sent', [
                'offer' => $this->offer,
                'user' => $notifiable,
                'productRequested' => $productRequested,
                'toUser' => $toUser
            ]);
    }
}