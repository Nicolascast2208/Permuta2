<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'product_offered_id',
        'product_requested_id',
        'complementary_amount',
        'comment', 
        'status',
        'payment_status',
        'read_by_receiver' 

    ];

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WAITING_PAYMENT = 'waiting_payment';

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }


    // RELACIÓN ANTIGUA (para compatibilidad)
    public function productOffered()
    {
        return $this->belongsTo(Product::class, 'product_offered_id');
    }

    // NUEVA RELACIÓN: Productos ofrecidos (pueden ser múltiples)
    public function productsOffered()
    {
        return $this->belongsToMany(Product::class, 'offer_products', 'offer_id', 'product_id');
    }

    public function productRequested()
    {
        return $this->belongsTo(Product::class, 'product_requested_id');
    }

    public function chat()
    {
        return $this->hasOne(Chat::class);
    }
    public function questions()
{
    return $this->hasMany(Question::class);
}
    public function getStatusNameAttribute()
    {
        return [
            'pending' => 'Pendiente',
            'accepted' => 'Aceptada',
            'rejected' => 'Rechazada',
            'waiting_payment' => 'Esperando pago'
        ][$this->status] ?? 'Desconocido';
    }
    
    public static function hasUserOffered($userId, $productRequestedId)
    {
        return self::where('from_user_id', $userId)
                   ->where('product_requested_id', $productRequestedId)
                   ->exists();
    }
        public function intermediateQuestions()
    {
        return $this->hasMany(IntermediateQuestion::class);
    }

    public function unansweredQuestions()
    {
        return $this->intermediateQuestions()->whereNull('answer');
    }
public static function canUserOffer($userId, $productRequestedId)
{
    return !self::where('from_user_id', $userId)
        ->where('product_requested_id', $productRequestedId)
        ->whereIn('status', ['pending', 'accepted', 'waiting_payment'])
        ->exists();
}

public static function getUserOffer($userId, $productRequestedId)
{
    return self::where('from_user_id', $userId)
        ->where('product_requested_id', $productRequestedId)
        ->whereIn('status', ['pending', 'accepted', 'waiting_payment'])
        ->first();
}


/**
 * Obtener todas las ofertas activas para un producto
 */
public static function getActiveOffersForProduct($productRequestedId)
{
    return self::where('product_requested_id', $productRequestedId)
        ->whereIn('status', ['pending', 'accepted', 'waiting_payment'])
        ->get();
}

/**
 * Verificar si ya hay una oferta aceptada para un producto
 */
public static function hasAcceptedOfferForProduct($productRequestedId)
{
    return self::where('product_requested_id', $productRequestedId)
        ->where('status', 'accepted')
        ->exists();
}

}