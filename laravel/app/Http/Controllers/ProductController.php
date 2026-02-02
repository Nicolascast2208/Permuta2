<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\ProductCreatedEmail;
class ProductController extends Controller
{
    use AuthorizesRequests;

public function index(Request $request)
{
    $query = Product::with(['user', 'images', 'category'])
        ->where('status', 'available');
    
    // Filtrar por categoría si se proporciona
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }
    
    // Resto de la lógica de filtrado...
    
    $products = $query->paginate(12);
    
    $categories = Category::all();
    
  $searchQuery = $request->input('q');
    $searchType = $request->input('type', 'want');
    
    $categories = Category::all();
    
    // Pasar los parámetros de búsqueda a la vista
    return view('products.index', compact('categories', 'searchQuery', 'searchType'));
}

public function show(Product $product)
{
    $product->load([
        'user' => function ($q) {
            $q->withCount(['products', 'completedPermutas', 'reviews']);
        },
        'category',
        'images',
        'questions.user',
        'questions.answers.user'
    ]);

    // Obtener productos relacionados (misma categoría o tags similares)
    $relatedProducts = Product::where('status', 'available')
        ->where('published', true)
        ->where('id', '!=', $product->id) // Excluir el producto actual
        ->where(function($query) use ($product) {
            // Misma categoría
            $query->where('category_id', $product->category_id);
            
            // O misma categoría padre (si existe)
            if ($product->category && $product->category->parent_id) {
                $query->orWhereHas('category', function($q) use ($product) {
                    $q->where('parent_id', $product->category->parent_id);
                });
            }
            
            // O tags similares
            if ($product->tags) {
                $tags = array_map('trim', explode(',', $product->tags));
                foreach ($tags as $tag) {
                    if (!empty(trim($tag))) {
                        $query->orWhere('tags', 'like', '%' . trim($tag) . '%');
                    }
                }
            }
        })
        ->with(['images', 'category', 'user'])
        ->inRandomOrder()
        ->limit(3) // Un poco más para tener variedad
        ->get();

    return view('products.show', compact('product', 'relatedProducts'));
}

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        
        // Preparar los grupos de categorías para el formulario
        $categoryGroups = [];
        foreach ($categories as $category) {
            $categoryGroups[$category->name] = $category->children->pluck('name')->toArray();
        }
        
        return view('products.create', compact('categories', 'categoryGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'price_reference' => 'required|numeric|min:0',
            'description' => 'required|string',
            'condition' => 'required|in:new,used,refurbished',
            'tags' => 'required|string',
            'images.*' => 'image|max:2048',
            'location' => 'required|string',
            'payment_option' => 'required|in:free,paid'
        ]);

        // Obtener coordenadas geográficas
        $coordinates = $this->getCoordinates($validated['location']);

        $product = Auth::user()->products()->create([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'price_reference' => $validated['price_reference'],
            'description' => $validated['description'],
            'condition' => $validated['condition'],
            'tags' => $validated['tags'],
            'location' => $validated['location'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
            'status' => $validated['payment_option'] === 'paid' ? 'available' : 'pending',
            'published' => $validated['payment_option'] === 'paid',
           
        ]);

        
         Auth::user()->notify(new ProductCreatedEmail($product));
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        if ($validated['payment_option'] === 'paid') {
            return redirect()->route('payment.init', $product);
        }

        return redirect()->route('dashboard.my-products')
            ->with('success', 'Producto publicado pendiente de aprobación');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        $categories = Category::with('children')->whereNull('parent_id')->get();
        
        // Preparar los grupos de categorías para el formulario
        $categoryGroups = [];
        foreach ($categories as $category) {
            $categoryGroups[$category->name] = $category->children->pluck('name')->toArray();
        }
        
        return view('products.edit', compact('product', 'categories', 'categoryGroups'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'price_reference' => 'required|numeric|min:0',
            'description' => 'required|string',
            'condition' => 'required|in:new,used,refurbished',
            'tags' => 'required|string',
            'images.*' => 'image|max:2048',
            'location' => 'required|string',
            'delete_images' => 'sometimes|array'
        ]);

        // Obtener nuevas coordenadas si la ubicación cambió
        if ($product->location !== $validated['location']) {
            $coordinates = $this->getCoordinates($validated['location']);
            $validated['latitude'] = $coordinates['latitude'];
            $validated['longitude'] = $coordinates['longitude'];
        }

        $product->update($validated);

        // Eliminar imágenes seleccionadas
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = $product->images()->findOrFail($imageId);
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        // Añadir nuevas imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Eliminar imágenes del storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $product->delete();

        return redirect()->route('dashboard.my-products')
            ->with('success', 'Producto eliminado correctamente');
    }
    
    public function myProducts(Request $request)
    {
        // Obtener parámetros de filtro
        $search = $request->input('search');
        $category = $request->input('category');
        $status = $request->input('status');
        $distance = $request->input('distance');
        $userLocation = $request->session()->get('user_location');
        
        // Consulta base para los productos del usuario autenticado
        $products = Product::where('user_id', auth()->id())
            ->with(['category', 'images'])
            ->latest();
        
        // Aplicar filtros
        if ($search) {
            $products->where('title', 'like', '%' . $search . '%');
        }
        
        if ($category) {
            $products->where('category_id', $category);
        }
        
        if ($status) {
            $products->where('status', $status);
        }
        
        // Filtro por distancia si hay ubicación del usuario
        if ($distance && $userLocation) {
            $products->selectRaw("*, 
                (6371 * ACOS(
                    COS(RADIANS(?)) 
                    * COS(RADIANS(latitude)) 
                    * COS(RADIANS(longitude) - RADIANS(?)) 
                    + SIN(RADIANS(?)) 
                    * SIN(RADIANS(latitude))
                )) AS distance", 
                [
                    $userLocation['latitude'], 
                    $userLocation['longitude'], 
                    $userLocation['latitude']
                ]
            )
            ->having('distance', '<', $distance)
            ->orderBy('distance');
        } else {
            $products->selectRaw("*, NULL as distance");
        }
        
        // Obtener categorías para el dropdown
        $categories = Category::all();
        
        // Paginación
        $products = $products->paginate(12);
        
        return view('dashboard.my-products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * Obtiene las coordenadas geográficas de una ubicación
     */
    private function getCoordinates($location)
    {
        try {
            if (empty($location)) {
                return ['latitude' => null, 'longitude' => null];
            }

            $response = Http::get('https://nominatim.openstreetmap.org/search', [
                'q' => $location . ', Chile',
                'format' => 'json',
                'limit' => 1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data)) {
                    return [
                        'latitude' => $data[0]['lat'],
                        'longitude' => $data[0]['lon']
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::error("Error al obtener coordenadas: " . $e->getMessage());
        }

        return ['latitude' => null, 'longitude' => null];
    }
    
    public function getUserLocation(Request $request)
    {
        try {
            // Intentar obtener de la sesión primero
            if ($location = $request->session()->get('user_location')) {
                return response()->json([
                    'success' => true,
                    'location' => $location
                ]);
            }

            // Si no hay en sesión, usar la API de Google
            $response = Http::post('https://www.googleapis.com/geolocation/v1/geolocate?key=' . config('services.google_maps.key'));
            
            if ($response->successful()) {
                $data = $response->json();
                $location = [
                    'latitude' => $data['location']['lat'],
                    'longitude' => $data['location']['lng'],
                    'accuracy' => $data['accuracy']
                ];
                
                $request->session()->put('user_location', $location);
                
                return response()->json([
                    'success' => true,
                    'location' => $location
                ]);
            }
            
            return response()->json(['success' => false], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

public function showSuggestions()
{
    // Obtener el ID del producto recién creado desde la sesión
    $productId = session('new_product_id');
    
    if (!$productId) {
        return redirect()->route('dashboard.my-products')
            ->with('error', 'No se encontró un producto recién creado');
    }
    
    // Cargar el producto con sus relaciones
    $product = Product::with(['category', 'images'])->findOrFail($productId);
    
    // Verificar que el usuario es el propietario del producto
    if (Auth::id() !== $product->user_id) {
        abort(403, 'No tienes permiso para ver estas sugerencias');
    }
        // Limpiar la sesión después de usarla
    session()->forget('new_product_id');
    
    return view('products.suggestions', compact('product'));
}
public function getSuggestions(Product $product)
{
    // Obtener productos sugeridos
    $suggestions = Product::query()
        ->with(['user', 'category', 'images'])
        ->where('status', 'available')
        ->where('published', true)
        ->where('user_id', '!=', $product->user_id)
        ->where(function ($query) use ($product) {
            // Coincidencia por categoría
            $query->where('category_id', $product->category_id)
                  ->orWhereHas('category', function ($q) use ($product) {
                      $q->where('parent_id', $product->category->parent_id);
                  });

            // Coincidencia por tags
            if ($product->tags) {
                $tags = array_map('trim', explode(',', $product->tags));
                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'like', '%' . $tag . '%');
                }
            }
        })
        ->inRandomOrder()
        ->limit(6)
        ->get();

    // Calcular distancias si hay ubicación del usuario
    if ($userLocation = session('user_location')) {
        $userLat = $userLocation['latitude'];
        $userLon = $userLocation['longitude'];

        $suggestions->each(function ($item) use ($userLat, $userLon) {
            if ($item->latitude && $item->longitude) {
                $item->distance = $this->calculateDistance($userLat, $userLon, $item->latitude, $item->longitude);
            }
        });
    }
    // Asegurar que los accessors estén disponibles en la respuesta JSON
    $suggestions->each(function ($item) {
        $item->append(['first_image_url', 'condition_name']);
    });

    return response()->json($suggestions);
}
 public function getEnhancedSuggestions(Product $product)
    {
        // Obtener la categoría hija del producto actual
        $userCategoryName = $product->category->name;
        $userTags = $product->tags ? array_map('trim', explode(',', $product->tags)) : [];
        
        // Buscar productos que coincidan con la lógica de permuta
        $suggestions = Product::query()
            ->with(['user', 'category', 'images'])
            ->where('status', 'available')
            ->where('published', true)
            ->where('user_id', '!=', $product->user_id)
            ->where(function ($query) use ($userCategoryName, $userTags) {
                // CONDICIÓN 1: Mis tags/intereses vs Categoría hija de otros productos
                if (!empty($userTags)) {
                    $query->orWhereHas('category', function ($q) use ($userTags) {
                        $q->whereIn('name', $userTags);
                    });
                }
                
                // CONDICIÓN 2: Mi categoría hija vs Tags de otros productos
                if ($userCategoryName) {
                    $query->orWhere('tags', 'like', '%' . $userCategoryName . '%');
                }
            })
            ->get()
            ->map(function ($suggestion) use ($product, $userCategoryName, $userTags) {
                // Calcular compatibilidad
                $suggestion->match_score = $this->calculateSimpleMatchScore($product, $suggestion, $userCategoryName, $userTags);
                return $suggestion;
            })
            ->filter(function ($suggestion) {
                // Solo productos con al menos 40% de match
                return $suggestion->match_score >= 40;
            })
            ->sortByDesc('match_score')
            ->take(6)
            ->values();

        // Asegurar accessors
        $suggestions->each(function ($item) {
            $item->append(['first_image_url', 'condition_name']);
        });

        return response()->json($suggestions);
    }

    private function calculateSimpleMatchScore(Product $userProduct, Product $suggestion, $userCategoryName, $userTags)
    {
        $score = 0;
        
        // 1. COMPATIBILIDAD POR CATEGORÍA/TAGS (Máx 70 puntos)
        $suggestionTags = $suggestion->tags ? array_map('trim', explode(',', $suggestion->tags)) : [];
        $suggestionCategoryName = $suggestion->category->name;
        
        // Verificar si mi categoría está en los tags del otro producto
        if (in_array(strtolower($userCategoryName), array_map('strtolower', $suggestionTags))) {
            $score += 35;
        }
        
        // Verificar si los tags del otro producto están en mi categoría
        foreach ($userTags as $tag) {
            if (str_contains(strtolower($suggestionCategoryName), strtolower($tag))) {
                $score += 35;
                break; // Solo una coincidencia necesaria
            }
        }
        
        // 2. COMPATIBILIDAD POR PRECIO (Máx 30 puntos)
        $userPrice = $userProduct->price_reference;
        $suggestionPrice = $suggestion->price_reference;
        
        if ($userPrice > 0 && $suggestionPrice > 0) {
            $difference = abs($userPrice - $suggestionPrice);
            $percentage = ($difference / max($userPrice, $suggestionPrice)) * 100;
            
            if ($percentage <= 20) $score += 30;
            elseif ($percentage <= 40) $score += 20;
            elseif ($percentage <= 60) $score += 10;
        }
        
        return min($score, 100);
    }
public function apiShow(Product $product)
{
    // Cargar todas las relaciones necesarias
    $product->load(['user', 'category', 'images']);
    
    // Obtener las URLs completas de todas las imágenes
    $formattedImages = $product->images->map(function ($image) {
        return [
            'id' => $image->id,
            'url' => asset('storage/' . $image->path),
            'path' => $image->path
        ];
    });
    
    // Asegurar que los accessors estén disponibles
    $product->append(['first_image_url', 'condition_name']);
    
    // Preparar la respuesta con las imágenes formateadas
    $response = $product->toArray();
    $response['images'] = $formattedImages;
    
    // Asegurar que first_image_url esté presente
    if (!isset($response['first_image_url'])) {
        $response['first_image_url'] = $product->images->count() > 0 
            ? asset('storage/' . $product->images->first()->path)
            : asset('images/default-product.png');
    }
    
    return response()->json($response);
}

private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return $miles * 1.609344; // Convertir a km
}

}