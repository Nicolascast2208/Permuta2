<?php

namespace App\Livewire;

use App\Models\Offer;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Auth;
use App\Events\OfferReceived;
use App\Events\TradeAccepted;
class OfferModal extends Component
{
    public $show = false;
    public $productId;
    public $selectedProductId;
    public $confirmationMessage = '';
    public $showConfirmation = false;
    public $myProducts = [];
    public $selectedProductDetail = null;

    #[On('open-offer-modal')]
    public function open($productId)
    {
        $this->productId = $productId['productId'];
        $this->show = true;
        $this->reset(['selectedProductId', 'confirmationMessage', 'showConfirmation', 'selectedProductDetail']);
        
        // Cargar productos del usuario autenticado
        $this->myProducts = Product::where('user_id', Auth::id())
            ->where('status', 'available')
            ->get()
            ->toArray();
    }

    public function close()
    {
        $this->show = false;
    }

    public function updatedSelectedProductId($value)
    {
        if ($value) {
            $this->selectedProductDetail = Product::find($value);
        } else {
            $this->selectedProductDetail = null;
        }
    }

    public function makeOffer()
    {
        $this->validate([
            'selectedProductId' => 'required|exists:products,id'
        ]);

        // Obtener el producto solicitado
        $productRequested = Product::findOrFail($this->productId);
        
        // Crear la oferta
        Offer::create([
            'from_user_id' => Auth::id(),
            'to_user_id' => $productRequested->user_id,
            'product_offered_id' => $this->selectedProductId,
            'product_requested_id' => $this->productId,
            'status' => 'pending'
        ]);
         event(new OfferReceived($offer));
        // Mostrar confirmación
        $this->confirmationMessage = '¡Oferta enviada con éxito! Hemos notificado al usuario sobre tu interés.';
        $this->showConfirmation = true;
        
        // Cerrar automáticamente después de 3 segundos
        $this->dispatch('offer-sent');
    }

    public function render()
    {
        return view('livewire.offer-modal');
    }
}