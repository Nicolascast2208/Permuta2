<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferReceivedEmail extends Notification implements ShouldQueue
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
        $fromUser = $this->offer->fromUser;
        
        return (new MailMessage)
            ->subject('¡Tienes una nueva oferta en Permuta2!')
            ->view('emails.offer_received', [
                'offer' => $this->offer,
                'user' => $notifiable,
                'productRequested' => $productRequested,
                'fromUser' => $fromUser
            ]);
    }
}