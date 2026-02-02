<?php

namespace App\Notifications;

use App\Models\Offer;
use App\Models\Chat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferAcceptedEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public $offer;
    public $chat;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
        // Buscar el chat asociado a la oferta
        $this->chat = Chat::where('offer_id', $offer->id)->first();
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $offer = $this->offer->load(['productRequested']);
        
        return (new MailMessage)
            ->subject('¡Tu oferta fue aceptada en Permuta2!')
            ->view('emails.offer_accepted', [
                'offer' => $offer,
                'user' => $notifiable,
                'productRequested' => $offer->productRequested,
                'chat' => $this->chat  // Pasar el chat a la vista
            ]);
    }
}