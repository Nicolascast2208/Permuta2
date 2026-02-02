<?php
namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $interests = '';
    public $distance = '';
    public $condition = [];
    public $userLocation = null;
    public $perPage = 12;
    public $filtersApplied = false;
    public $autoLocationAttempted = false;
    public $showMobileFilters = false;
    public $initialSearch = '';
    public $initialSearchType = '';
    public $viewMode = 'list'; // 'list' o 'grid'

    protected $listeners = [
        'location-updated' => 'refreshProducts'
    ];

    public function mount($initialSearch = null, $initialSearchType = null)
    {
        // Recuperar ubicación de la sesión si existe
        if ($location = session('user_location')) {
            $this->userLocation = [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude']
            ];
        }
        
        // Si hay búsqueda inicial desde el header, aplicarla
        if ($initialSearch) {
            $this->initialSearch = $initialSearch;
            $this->initialSearchType = $initialSearchType;
            
            if ($initialSearchType === 'want') {
                $this->search = $initialSearch;
            } else {
                $this->interests = $initialSearch;
            }
            
            $this->filtersApplied = true;
        }
        
        $this->attemptAutoLocation();
    }

    // Método para alternar filtros en móviles
    public function toggleMobileFilters()
    {
        $this->showMobileFilters = !$this->showMobileFilters;
    }

    private function attemptAutoLocation()
    {
        // Solo intentar una vez por carga de página
        if ($this->autoLocationAttempted) return;
        $this->autoLocationAttempted = true;

        // Verificar si ya hay ubicación en sesión
        if ($location = session('user_location')) {
            $this->userLocation = [
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude']
            ];
            return;
        }

        // Intentar obtener ubicación automáticamente
        $this->dispatch('request-user-location-auto');
    }

    public function refreshProducts()
    {
        $this->resetPage();
    }

    public function getUserLocation()
    {
        $this->dispatch('request-user-location'); // Dispara evento JavaScript
        $this->filtersApplied = true;
    }

    public function setUserLocation($latitude, $longitude)
    {
        $this->userLocation = [
            'latitude' => $latitude,
            'longitude' => $longitude
        ];
        session(['user_location' => $this->userLocation]); // Guardar en sesión
        $this->refreshProducts();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'category',
            'interests',
            'distance',
            'condition',
            'userLocation'
        ]);
        
        // También reseteamos la bandera de filtros aplicados
        $this->filtersApplied = false;
        
        $this->resetPage(); // paginación
    }

    public function render()
    {
        $query = Product::query()
            ->with(['user', 'category', 'images'])
            ->where('status', 'available')
            ->where('published', true)
            ->when(auth()->check(), function ($query) {
                $query->where('user_id', '!=', auth()->id());
            })
            ->when($this->search, function ($q) {
                // Buscar por título O por categoría
                $q->where(function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%')
                          ->orWhereHas('category', function ($categoryQuery) {
                              $categoryQuery->where('name', 'like', '%'.$this->search.'%');
                          });
                });
            })
            ->when($this->category, function ($q) {
                $q->where('category_id', $this->category);
            })
            ->when($this->interests, function ($q) {
                $tags = array_map('trim', explode(',', $this->interests));
                $q->where(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        if ($tag) {
                            $query->orWhere('tags', 'like', '%'.$tag.'%');
                        }
                    }
                });
            })
            ->when(!empty($this->condition), function ($q) {
                $q->whereIn('condition', $this->condition);
            });
        
        // CALCULAR DISTANCIA SIEMPRE QUE HAYA UBICACIÓN
        if ($this->userLocation) {
            $userLat = $this->userLocation['latitude'];
            $userLon = $this->userLocation['longitude'];
            
            $query->selectRaw("*, 
                (6371 * ACOS(
                    COS(RADIANS(?)) 
                    * COS(RADIANS(latitude)) 
                    * COS(RADIANS(longitude) - RADIANS(?)) 
                    + SIN(RADIANS(?)) 
                    * SIN(RADIANS(latitude))
                )) AS distance", 
                [$userLat, $userLon, $userLat]
            );
            
            // Filtrar por distancia si se seleccionó
            if ($this->distance) {
                $query->having('distance', '<', $this->distance);
            }
            
            $query->orderBy('distance');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate($this->perPage);
        $categories = Category::all();

        return view('livewire.product-list', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}