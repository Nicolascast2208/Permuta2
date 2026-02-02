<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;

class ReceivedOffers extends Component
{
    use WithPagination;

    // Cambiar nombre del método para aceptar
    public function acceptOfferx(Offer $offer)
    {
        $offer->update(['status' => 'accepted']);
        
        // Crear chat
        $offer->chat()->create([
            'user1_id' => $offer->from_user_id,
            'user2_id' => $offer->to_user_id
        ]);
        
        session()->flash('success', 'Oferta aceptada');
    }

    // Cambiar nombre del método para rechazar

public function rejectOffer($offerId)
{
    $offer = Offer::findOrFail($offerId);
    $offer->update(['status' => 'rejected']);

    session()->flash('success', 'Oferta rechazada');
}

    public function render()
    {
        $offers = Offer::with(['fromUser', 'productOffered', 'productRequested'])
            ->where('to_user_id', auth()->id())
            ->paginate(10);

        return view('livewire.received-offers', compact('offers'));
    }
}