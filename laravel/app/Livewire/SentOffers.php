<?php

namespace App\Livewire;

use App\Models\Offer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SentOffers extends Component
{
    use WithPagination, AuthorizesRequests;

    public $status = 'pending';
    public $filter = 'match_general';
    public $sort = 'desc';

    public function cancel($offerId)
    {
        $offer = Offer::findOrFail($offerId);
        $this->authorize('cancel', $offer);
        
        $offer->delete();
        session()->flash('success', 'Oferta cancelada');
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
        
        $requestedCategory = $offer->productRequested->category;
        foreach ($offer->productsOffered as $productOffered) {
            if ($productOffered->category_id === $requestedCategory->id) {
                $score += 40;
                break;
            }
            
            if ($requestedCategory->parent_id && $productOffered->category->parent_id === $requestedCategory->parent_id) {
                $score += 30;
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

    private function calculateTotalOfferedValue($offer)
    {
        $total = 0;
        foreach ($offer->productsOffered as $product) {
            $total += $product->price_reference;
        }
        return $total + $offer->complementary_amount;
    }

    public function render()
    {
        $query = Offer::with([
            'toUser', 
            'productsOffered.images', 
            'productsOffered.category',
            'productRequested.images',
            'productRequested.category',
            'chat'
        ])
        ->where('from_user_id', auth()->id());

        // Filtro por estado - CORREGIDO: Separar waiting_payment de pending
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
                $offer->match_score = $this->calculateOfferMatchScore($offer);
                $offer->total_offered_value = $this->calculateTotalOfferedValue($offer);
                return $offer;
            });

        // Aplicar ordenamiento adicional si es necesario
        if ($this->filter === 'match_general') {
            $offers->setCollection($offers->getCollection()->sortByDesc('match_score'));
        } elseif ($this->filter === 'price') {
            $offers->setCollection($offers->getCollection()->sortBy('total_offered_value'));
            if ($this->sort === 'desc') {
                $offers->setCollection($offers->getCollection()->reverse());
            }
        }

        // Contadores CORREGIDOS - Asegurar que todas las claves existan
        $counters = [
            'pending' => Offer::where('from_user_id', auth()->id())->where('status', 'pending')->count(),
            'waiting_payment' => Offer::where('from_user_id', auth()->id())->where('status', 'waiting_payment')->count(),
            'accepted' => Offer::where('from_user_id', auth()->id())->where('status', 'accepted')->count(),
            'rejected' => Offer::where('from_user_id', auth()->id())->where('status', 'rejected')->count(),
        ];

        // Asegurar que todas las claves tengan valor numérico
        foreach ($counters as $key => $value) {
            $counters[$key] = (int)$value;
        }

        return view('livewire.sent-offers', compact('offers', 'counters'));
    }
}