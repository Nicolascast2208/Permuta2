<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfferPolicy
{
    use HandlesAuthorization;

    public function accept(User $user, Offer $offer)
    {
        return $user->id === $offer->to_user_id;
    }

    public function reject(User $user, Offer $offer)
    {
        return $user->id === $offer->to_user_id;
    }
public function payCommission(User $user, Offer $offer)
{
    // Solo el dueño del producto solicitado puede pagar la comisión
    return $user->id === $offer->productRequested->user_id;
}
public function view(User $user, Offer $offer)
{
    // Solo los usuarios involucrados en la oferta pueden verla
    return $user->id === $offer->from_user_id || $user->id === $offer->to_user_id;
}
}