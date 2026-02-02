<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->loadCount([
            'products',
            'receivedOffers',
            'sentOffers',
            'notifications as unread_notifications_count' => function($query) {
                $query->where('read', false);
            }
        ]);
        
        return view('dashboard.index', compact('user'));
    }

    public function myProducts()
    {
        $products = Auth::user()->products()
            ->latest()
            ->paginate(10);

        return view('dashboard.my-products', compact('products'));
    }

    public function receivedOffers(Request $request)
    {
        // Marcar ofertas como leídas al entrar
        Auth::user()->unreadReceivedOffers()->update(['read_by_receiver' => true]);

        // Cambiar estado por defecto a 'pending' en lugar de 'all'
        $status = $request->get('status', 'pending');
        $filter = $request->get('filter', 'match_general');
        $sort = $request->get('sort', 'desc');

        $query = Offer::with([
            'fromUser', 
            'productsOffered.images', 
            'productsOffered.category',
            'productRequested.images',
            'productRequested.category',
            'chat'
        ])
        ->where('to_user_id', auth()->id());

        // Filtro por estado - si es 'all', no aplicar filtro
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Primero obtener las ofertas sin procesar
        $rawOffers = $query->orderBy('created_at', 'desc')->get();

        // Procesar las ofertas para calcular match score y valor
        $processedOffers = $rawOffers->map(function ($offer) {
            $offer->match_score = $this->calculateOfferMatchScore($offer);
            $offer->total_offered_value = $this->calculateTotalOfferedValue($offer);
            return $offer;
        });

        // Aplicar ordenamiento según el filtro
        if ($filter === 'match_general') {
            if ($sort === 'desc') {
                $processedOffers = $processedOffers->sortByDesc('match_score');
            } else {
                $processedOffers = $processedOffers->sortBy('match_score');
            }
        } elseif ($filter === 'price') {
            if ($sort === 'desc') {
                $processedOffers = $processedOffers->sortByDesc('total_offered_value');
            } else {
                $processedOffers = $processedOffers->sortBy('total_offered_value');
            }
        }

        // Paginar manualmente
        $page = $request->get('page', 1);
        $perPage = 10;
        $offers = new \Illuminate\Pagination\LengthAwarePaginator(
            $processedOffers->forPage($page, $perPage),
            $processedOffers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Contadores para los tabs
        $counters = [
            'all' => Offer::where('to_user_id', auth()->id())->count(),
            'pending' => Offer::where('to_user_id', auth()->id())->where('status', 'pending')->count(),
            'waiting_payment' => Offer::where('to_user_id', auth()->id())->where('status', 'waiting_payment')->count(),
            'accepted' => Offer::where('to_user_id', auth()->id())->where('status', 'accepted')->count(),
            'rejected' => Offer::where('to_user_id', auth()->id())->where('status', 'rejected')->count(),
        ];

        return view('dashboard.received-offers', compact('offers', 'status', 'filter', 'sort', 'counters'));
    }

   public function calculateOfferMatchScore($offer)
    {
        $score = 0;
        
        $requestedProduct = $offer->productRequested;
        $userCategoryName = $requestedProduct->category->name;
        $userTags = $requestedProduct->tags ? array_map('trim', explode(',', $requestedProduct->tags)) : [];
        
        // Para cada producto ofrecido, calcular el mejor match
        foreach ($offer->productsOffered as $productOffered) {
            $productScore = $this->calculateProductMatchScore(
                $requestedProduct, 
                $productOffered, 
                $userCategoryName, 
                $userTags
            );
            
            // Tomar el mejor score entre los productos ofrecidos
            if ($productScore > $score) {
                $score = $productScore;
            }
        }

        return min($score, 100);
    }

    /**
     * Calcula el match score entre dos productos usando la misma lógica que ProductController
     */
    private function calculateProductMatchScore(Product $userProduct, Product $offeredProduct, $userCategoryName, $userTags)
    {
        $score = 0;
        
        // 1. COMPATIBILIDAD POR CATEGORÍA/TAGS (Máx 70 puntos)
        $offeredTags = $offeredProduct->tags ? array_map('trim', explode(',', $offeredProduct->tags)) : [];
        $offeredCategoryName = $offeredProduct->category->name;
        
        // Verificar si mi categoría está en los tags del producto ofrecido
        if (in_array(strtolower($userCategoryName), array_map('strtolower', $offeredTags))) {
            $score += 35;
        }
        
        // Verificar si los tags de mi producto están en la categoría del producto ofrecido
        foreach ($userTags as $tag) {
            if (str_contains(strtolower($offeredCategoryName), strtolower($tag))) {
                $score += 35;
                break; // Solo una coincidencia necesaria
            }
        }
        
        // 2. COMPATIBILIDAD POR PRECIO (Máx 30 puntos)
        $userPrice = $userProduct->price_reference;
        $offeredPrice = $offeredProduct->price_reference;
        
        if ($userPrice > 0 && $offeredPrice > 0) {
            $difference = abs($userPrice - $offeredPrice);
            $percentage = ($difference / max($userPrice, $offeredPrice)) * 100;
            
            if ($percentage <= 20) $score += 30;
            elseif ($percentage <= 40) $score += 20;
            elseif ($percentage <= 60) $score += 10;
        }
        
        return min($score, 100);
    }
    public function calculateTotalOfferedValue($offer)
    {
        $total = 0;
        foreach ($offer->productsOffered as $product) {
            $total += $product->price_reference;
        }
        return $total + $offer->complementary_amount;
    }

public function sentOffers(Request $request)
{
    // Cambiar estado por defecto a 'pending' en lugar de 'all'
    $status = $request->get('status', 'pending');
    $filter = $request->get('filter', 'match_general');
    $sort = $request->get('sort', 'desc');

    $query = Offer::with([
        'toUser', 
        'productsOffered.images', 
        'productsOffered.category',
        'productRequested.images',
        'productRequested.category',
        'chat'
    ])
    ->where('from_user_id', auth()->id());

    // Filtro por estado - si es 'all', no aplicar filtro
    if ($status !== 'all') {
        $query->where('status', $status);
    }

    // Primero obtener las ofertas sin procesar
    $rawOffers = $query->orderBy('created_at', 'desc')->get();

    // Procesar las ofertas para calcular match score y valor
    $processedOffers = $rawOffers->map(function ($offer) {
        $offer->match_score = $this->calculateOfferMatchScore($offer);
        $offer->total_offered_value = $this->calculateTotalOfferedValue($offer);
        return $offer;
    });

    // Aplicar ordenamiento según el filtro
    if ($filter === 'match_general') {
        if ($sort === 'desc') {
            $processedOffers = $processedOffers->sortByDesc('match_score');
        } else {
            $processedOffers = $processedOffers->sortBy('match_score');
        }
    } elseif ($filter === 'price') {
        if ($sort === 'desc') {
            $processedOffers = $processedOffers->sortByDesc('total_offered_value');
        } else {
            $processedOffers = $processedOffers->sortBy('total_offered_value');
        }
    }

    // Paginar manualmente
    $page = $request->get('page', 1);
    $perPage = 10;
    $offers = new \Illuminate\Pagination\LengthAwarePaginator(
        $processedOffers->forPage($page, $perPage),
        $processedOffers->count(),
        $perPage,
        $page,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    // Contadores para los tabs - CORREGIDO: Incluir waiting_payment
    $counters = [
        'pending' => Offer::where('from_user_id', auth()->id())->where('status', 'pending')->count(),
        'waiting_payment' => Offer::where('from_user_id', auth()->id())->where('status', 'waiting_payment')->count(),
        'accepted' => Offer::where('from_user_id', auth()->id())->where('status', 'accepted')->count(),
        'rejected' => Offer::where('from_user_id', auth()->id())->where('status', 'rejected')->count(),
    ];

    return view('dashboard.sent-offers', compact('offers', 'status', 'filter', 'sort', 'counters'));
}

    public function questions()
    {
        // Marcar preguntas como leídas al entrar
        Question::whereHas('product', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->where('read_by_seller', false)
            ->update(['read_by_seller' => true]);

        $questions = Question::with(['product', 'user'])
            ->whereHas('product', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->rootQuestions()
            ->withCount('answers')
            ->latest()
            ->paginate(10);

        return view('dashboard.questions', compact('questions'));
    }

    public function trades()
    {
        $trades = Offer::with([
                'productRequested.images', 
                'productsOffered.images', 
                'fromUser', 
                'toUser',
                'chat'
            ])
            ->where('status', 'accepted')
            ->where(function($query) {
                $query->where('from_user_id', Auth::id())
                    ->orWhere('to_user_id', Auth::id());
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $processedTrades = $trades->getCollection()->map(function($trade) {
            $user = Auth::user();
            
            if ($user->id == $trade->from_user_id) {
                $trade->productToReceive = $trade->productRequested;
                $trade->productToGive = $trade->productsOffered->first();
                $trade->otherUser = $trade->toUser;
            } else {
                $trade->productToReceive = $trade->productsOffered->first();
                $trade->productToGive = $trade->productRequested;
                $trade->otherUser = $trade->fromUser;
            }
            
            return $trade;
        });
        
        $trades->setCollection($processedTrades);

        return view('dashboard.trades', compact('trades'));
    }

    // Método para obtener contadores (puede ser usado en el layout)
    public function getSidebarCounters()
    {
        if (!Auth::check()) {
            return [
                'unread_offers' => 0,
                'unread_questions' => 0
            ];
        }

        return [
            'unread_offers' => Auth::user()->unreadReceivedOffers()->count(),
            'unread_questions' => Question::whereHas('product', function($query) {
                $query->where('user_id', Auth::id());
            })->where('read_by_seller', false)->count()
        ];
    }
}