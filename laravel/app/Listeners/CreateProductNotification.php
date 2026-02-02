<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Models\Notification;

class CreateProductNotification
{
    public function handle(ProductCreated $event)
    {
        $product = $event->product;
        \Log::info('Listener ejecutado para product_id: '.$product->id);
        Notification::create([
            'user_id' => $product->user_id,
            'type' => 'product_created',
            'notifiable_id' => $product->id,
            'notifiable_type' => get_class($product),
            'message' => 'Publicaste un nuevo producto: ' . $product->title
        ]);
    }
}