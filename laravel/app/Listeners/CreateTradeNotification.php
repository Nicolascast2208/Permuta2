<?php
namespace App\Listeners;

use App\Events\TradeAccepted;
use App\Models\Notification;

class CreateTradeNotification
{
    public function handle(TradeAccepted $event)
    {
        $offer = $event->offer;
        
        // Notificar al usuario que envió la oferta
        Notification::create([
            'user_id' => $offer->from_user_id,
            'type' => 'trade_accepted',
            'notifiable_id' => $offer->id,
            'notifiable_type' => get_class($offer),
            'message' => 'Tu oferta fue aceptada para: ' . $offer->productRequested->title
        ]);
        
        // Notificar al usuario que recibió la oferta
        Notification::create([
            'user_id' => $offer->to_user_id,
            'type' => 'trade_accepted',
            'notifiable_id' => $offer->id,
            'notifiable_type' => get_class($offer),
            'message' => 'Aceptaste una oferta para: ' . $offer->productRequested->title
        ]);
    }
}