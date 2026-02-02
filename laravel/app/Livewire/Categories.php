<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Collection;

class Categories extends Component
{
    use WithPagination;

    public $search = '';
    public $condition = [];
    public $category;
    public $selectedSubcategory = null;
    public $productCounts = [];
    public $showMobileFilters = false;
    public $viewMode = 'list'; // 'list' o 'grid'

    public function mount($category)
    {
        if (is_string($category)) {
            $this->category = Category::where('slug', $category)->firstOrFail();
        } else {
            $this->category = $category;
        }
        
        // Calcular conteos iniciales
        $this->calculateProductCounts();
    }

    public function toggleMobileFilters()
    {
        $this->showMobileFilters = !$this->showMobileFilters;
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'condition', 'selectedSubcategory'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->condition = [];
        $this->selectedSubcategory = null;
        $this->resetPage();
    }

    public function selectSubcategory($subcategoryId)
    {
        $this->selectedSubcategory = $subcategoryId;
        $this->resetPage();
        $this->calculateProductCounts();
    }

    // Método para calcular los conteos de productos
    private function calculateProductCounts()
    {
        $this->productCounts = [];
        
        // Conteo para la categoría actual (todas las subcategorías)
        $categoryIds = $this->getCategoryIds();
        $this->productCounts['current'] = Product::whereIn('category_id', $categoryIds)
            ->where('status', 'available')
            ->count();

        // Conteo para cada subcategoría
        if (!$this->category->parent_id && $this->category->children->count() > 0) {
            foreach ($this->category->children as $subcategory) {
                $this->productCounts['subcategories'][$subcategory->id] = Product::where('category_id', $subcategory->id)
                    ->where('status', 'available')
                    ->count();
            }
        }
    }

    // Método para obtener los IDs de categoría según la selección
    private function getCategoryIds()
    {
        if ($this->selectedSubcategory) {
            return [$this->selectedSubcategory];
        } else if ($this->category->parent_id) {
            return [$this->category->id];
        } else {
            return array_merge(
                [$this->category->id],
                $this->category->children->pluck('id')->toArray()
            );
        }
    }

    public function render()
    {
        $query = Product::with(['user', 'images', 'category'])
            ->where('status', 'available');

        $categoryIds = $this->getCategoryIds();
        $query->whereIn('category_id', $categoryIds);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('tags', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->condition)) {
            $query->whereIn('condition', $this->condition);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('livewire.categories', [
            'products' => $products,
        ]);
    }
}