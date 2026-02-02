<?php
namespace App\Listeners;

use App\Events\OfferReceived;
use App\Models\Notification;

class CreateOfferNotification
{
    public function handle(OfferReceived $event)
    {
        $offer = $event->offer;
        
        Notification::create([
            'user_id' => $offer->to_user_id,
            'type' => 'offer_received',
            'notifiable_id' => $offer->id,
            'notifiable_type' => get_class($offer),
            'message' => 'Recibiste una oferta para: ' . $offer->productRequested->title
        ]);
    }
}