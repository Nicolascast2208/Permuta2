<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferRejectedEmail extends Notification implements ShouldQueue
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
        
        return (new MailMessage)
            ->subject('Actualización sobre tu oferta en Permuta2')
            ->view('emails.offer_rejected', [
                'offer' => $this->offer,
                'user' => $notifiable,
                'productRequested' => $productRequested
            ]);
    }
}