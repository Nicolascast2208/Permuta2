<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReceivedOffers extends Component
{
    use WithPagination, AuthorizesRequests;

    public $status = 'pending';
    public $filter = 'match_general';
    public $sort = 'desc';

    public function acceptOffer($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $this->authorize('accept', $offer);
        
        return redirect()->route('offers.accept', $offer);
    }

    public function rejectOffer($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $this->authorize('reject', $offer);
        
        $offer->update(['status' => 'rejected']);
        session()->flash('success', 'Oferta rechazada');
        $this->resetPage();
    }

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->resetPage();
    }

    public function setFilter($newFilter, $newSort = null)
    {
        $this->filter = $newFilter;
        if ($newSort) {
            $this->sort = $newSort;
        }
        $this->resetPage();
    }

    private function calculateOfferMatchScore($offer)
    {
        $score = 0;
        
        // Verificar que el producto solicitado y sus relaciones existan
        if (!$offer->productRequested || !$offer->productRequested->category) {
            return 0;
        }
        
        $requestedCategory = $offer->productRequested->category;
        foreach ($offer->productsOffered as $productOffered) {
            if (!$productOffered->category) continue;
            
            if ($productOffered->category_id === $requestedCategory->id) {
                $score += 60;
                break;
            }
            
            if ($requestedCategory->parent_id && $productOffered->category->parent_id === $requestedCategory->parent_id) {
                $score += 50;
                break;
            }
        }

        $requestedTags = $offer->productRequested->tags ? array_map('trim', explode(',', $offer->productRequested->tags)) : [];
        foreach ($offer->productsOffered as $productOffered) {
            $offeredTags = $productOffered->tags ? array_map('trim', explode(',', $productOffered->tags)) : [];
            
            $commonTags = array_intersect(
                array_map('strtolower', $requestedTags),
                array_map('strtolower', $offeredTags)
            );
            
            if (count($commonTags) > 0) {
                $score += min(30, count($commonTags) * 10);
                break;
            }
        }

        $requestedPrice = $offer->productRequested->price_reference;
        $offeredPrice = $this->calculateTotalOfferedValue($offer);
        
        if ($requestedPrice > 0 && $offeredPrice > 0) {
            $difference = abs($requestedPrice - $offeredPrice);
            $percentage = ($difference / max($requestedPrice, $offeredPrice)) * 100;
            
            if ($percentage <= 10) $score += 30;
            elseif ($percentage <= 25) $score += 20;
            elseif ($percentage <= 50) $score += 10;
        }

        return min($score, 100);
    }

    private function calculateInterestScore($offer)
    {
        $score = 0;
        
        // Verificar que el producto solicitado exista
        if (!$offer->productRequested) {
            return 0;
        }
        
        // Tags del producto solicitado (tu producto)
        $requestedTags = $offer->productRequested->tags ? 
            array_map('trim', explode(',', $offer->productRequested->tags)) : [];
        
        // Para cada producto ofrecido, calcular coincidencia de tags
        foreach ($offer->productsOffered as $productOffered) {
            $offeredTags = $productOffered->tags ? 
                array_map('trim', explode(',', $productOffered->tags)) : [];
            
            // Contar tags en común
            $commonTags = array_intersect(
                array_map('strtolower', $requestedTags),
                array_map('strtolower', $offeredTags)
            );
            
            // Añadir puntos por cada tag en común
            $score += count($commonTags) * 20;
            
            // Bonus si hay muchos tags en común
            if (count($commonTags) >= 3) {
                $score += 20;
            }
        }
        
        // Normalizar a porcentaje (máximo 100%)
        return min($score, 100);
    }

    private function calculateTotalOfferedValue($offer)
    {
        $total = 0;
        foreach ($offer->productsOffered as $product) {
            $total += $product->price_reference ?? 0;
        }
        return $total + ($offer->complementary_amount ?? 0);
    }

    public function render()
    {
        // PRIMERO: Calcular los contadores ANTES de cualquier filtro
        $counters = [
            'pending' => Offer::where('to_user_id', auth()->id())
                            ->where('status', 'pending')
                            ->count(),
            'waiting_payment' => Offer::where('to_user_id', auth()->id())
                                    ->where('status', 'waiting_payment')
                                    ->count(),
            'accepted' => Offer::where('to_user_id', auth()->id())
                            ->where('status', 'accepted')
                            ->count(),
            'rejected' => Offer::where('to_user_id', auth()->id())
                            ->where('status', 'rejected')
                            ->count(),
        ];

        // SEGUNDO: Construir la consulta para las ofertas
        $query = Offer::with([
            'fromUser', 
            'productsOffered.images', 
            'productsOffered.category',
            'productRequested.images',
            'productRequested.category',
            'chat'
        ])
        ->where('to_user_id', auth()->id());

        // Filtro por estado
        if ($this->status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($this->status === 'waiting_payment') {
            $query->where('status', 'waiting_payment');
        } elseif ($this->status === 'accepted') {
            $query->where('status', 'accepted');
        } elseif ($this->status === 'rejected') {
            $query->where('status', 'rejected');
        }

        // Obtener ofertas paginadas
        $offers = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->through(function ($offer) {
                // Calcular scores solo si las relaciones existen
                try {
                    $offer->match_score = $this->calculateOfferMatchScore($offer);
                    $offer->interest_score = $this->calculateInterestScore($offer);
                    $offer->total_offered_value = $this->calculateTotalOfferedValue($offer);
                } catch (\Exception $e) {
                    // En caso de error, establecer valores por defecto
                    $offer->match_score = 0;
                    $offer->interest_score = 0;
                    $offer->total_offered_value = 0;
                }
                return $offer;
            });

        // Aplicar ordenamiento adicional
        if ($this->filter === 'match_general') {
            $offers->setCollection($offers->getCollection()->sortByDesc('match_score'));
        } elseif ($this->filter === 'price') {
            $offers->setCollection($offers->getCollection()->sortByDesc('total_offered_value'));
        } elseif ($this->filter === 'interest') {
            $offers->setCollection($offers->getCollection()->sortByDesc('interest_score'));
        }

        // Asegurar que todas las claves tengan valor numérico
        foreach ($counters as $key => $value) {
            $counters[$key] = (int)$value;
        }

        return view('livewire.received-offers', compact('offers', 'counters'));
    }
}