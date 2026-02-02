<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;

class ExchangeModal extends Component
{
    public $showModal = false;
    public $productId;
    public $selectedOfferProduct;
    public $myProducts = [];
    public $showConfirmation = false;
    public $selectedProductDetail = null;
    public $productDesired;

    protected $listeners = ['open-exchange-modal' => 'openModal'];

    public function openModal($productId)
    {
        $this->reset([
            'selectedOfferProduct', 
            'showConfirmation', 
            'selectedProductDetail',
            'showModal'
        ]);
        
        $this->productId = $productId;
        $this->productDesired = Product::with('images', 'user')->find($productId);
        
        $this->myProducts = Auth::user()->products()
            ->with('images')
            ->where('status', 'available')
            ->get();
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

public function updatedSelectedOfferProduct($value)
{
    if ($value) {
        $this->selectedProductDetail = Product::with('images')->find($value);
    } else {
        $this->selectedProductDetail = null;
    }
}
public function openExchangeModal($productId)
{
    $this->productDesired = Product::with('images')->find($productId);
    $this->myProducts = auth()->user()->products()->with('images')->get();
    $this->showModal = true;
}
    public function submitOffer()
    {
        $this->validate([
            'selectedOfferProduct' => 'required|exists:products,id'
        ]);

        try {
    $offer = Offer::create([
    'from_user_id' => auth()->id(),
    'to_user_id' => $this->productDesired->user_id,
    'product_offered_id' => $this->selectedOfferProduct,
    'product_requested_id' => $this->productId,
    'status' => 'pending'
]);
            $this->showConfirmation = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Error al enviar la oferta: ' . $e->getMessage());
        }

        \App\Models\Notification::create([
    'user_id' => $offer->to_user_id,
    'type' => 'offer_received',
    'notifiable_id' => $offer->id,
    'notifiable_type' => Offer::class,
    'message' => 'Recibiste una oferta para: ' . $offer->productRequested->title,
    'read' => false
]);
    }

    public function render()
    {
        return view('livewire.exchange-modal');
    }
}