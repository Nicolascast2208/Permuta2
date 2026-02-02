<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductFormEdit extends Component
{
    use WithFileUploads;

    public $isEdit = true;
    public $product;

    public $categories;
    public $parentCategories;
    public $categoryGroups = [];

    public $regionesComunas = [];
    public $selectedRegion;

    public $title;
    public $category_id;
    public $price_reference;
    public $description;
    public $condition = '';
    public $tags;
    public $location;

    public $images = [];
    public $existingImages = [];
    public $removedImages = [];
    public $paymentOption = 'free';

    protected function rules()
    {
        return [
            'description' => 'required|string',
            'tags' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $tagsArray = $value ? explode(',', $value) : [];
                    $tagsArray = array_filter(array_map('trim', $tagsArray));
                    
                    if (count($tagsArray) > 10) {
                        $fail('No puedes seleccionar más de 10 intereses.');
                    }
                    if (count($tagsArray) === 0) {
                        $fail('Debes seleccionar al menos un producto de interés.');
                    }
                },
            ],
            'images.*' => 'image|max:5120', // Actualizado a 5MB
        ];
    }

    protected $messages = [
        'description.required' => 'La descripción es obligatoria.',
        'tags.required' => 'Debes seleccionar al menos un producto de interés.',
        'images.*.image' => 'Los archivos deben ser imágenes válidas.',
        'images.*.max' => 'Las imágenes no deben pesar más de 5MB.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;

        // Categorías
        $this->parentCategories = Category::with('children')->whereNull('parent_id')->get();
        $this->categories = Category::all();

        // Para autocompletado de tags
        foreach ($this->parentCategories as $parent) {
            $this->categoryGroups[$parent->name] = $parent->children->pluck('name')->toArray();
        }

        // Inicializar regionesComunas
        $this->regionesComunas = config('chile_regions.regiones');

        // Datos del producto
        $this->title = $product->title;
        $this->category_id = $product->category_id;
        $this->price_reference = $product->price_reference;
        $this->description = $product->description;
        $this->condition = $product->condition;
        $this->tags = $product->tags;
        $this->location = $product->location;

        // Detectar región por comuna
        foreach ($this->regionesComunas as $region => $comunas) {
            if (in_array($product->location, $comunas)) {
                $this->selectedRegion = $region;
                break;
            }
        }

        // Imágenes existentes para el preview
        $this->loadExistingImages();
    }

    private function loadExistingImages()
    {
        $this->existingImages = $this->product->images->map(function($img) {
            return [
                'id' => $img->id,
                'url' => Storage::url($img->path),
            ];
        })->toArray();
    }

    public function save()
    {
        $this->validate();

        try {
            // Validar que hay al menos una imagen (existente o nueva)
            $totalImages = count($this->existingImages) + count($this->images);
            if ($totalImages === 0) {
                $this->addError('images', 'Debe haber al menos una imagen.');
                return;
            }

            // Validar que no se exceda el límite de imágenes
            if ($totalImages > 4) {
                $this->addError('images', 'No puedes tener más de 4 imágenes.');
                return;
            }

            // Actualizar la descripción y los tags
            $this->product->update([
                'description' => $this->description,
                'tags' => $this->tags,
            ]);

            // Procesar imágenes eliminadas
            if (!empty($this->removedImages)) {
                $imagesToDelete = ProductImage::whereIn('id', $this->removedImages)->get();
                foreach ($imagesToDelete as $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
                $this->removedImages = []; // Limpiar después de procesar
            }

            // Procesar nuevas imágenes (máximo 4)
            if ($this->images && count($this->images) > 0) {
                $availableSlots = 4 - count($this->existingImages);
                $imagesToUpload = array_slice($this->images, 0, $availableSlots);
                
                foreach ($imagesToUpload as $image) {
                    if (is_object($image) && method_exists($image, 'store')) {
                        $path = $image->store('products', 'public');
                        ProductImage::create([
                            'product_id' => $this->product->id,
                            'path' => $path
                        ]);
                    }
                }
            }

            // Limpiar imágenes temporales
            $this->images = [];

            session()->flash('success', 'Producto actualizado correctamente');
            return redirect()->route('dashboard.my-products');
            
        } catch (\Exception $e) {
            Log::error("Error al actualizar producto: " . $e->getMessage());
            session()->flash('error', 'Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    public function removeExistingImage($index)
    {
        if (isset($this->existingImages[$index])) {
            $this->removedImages[] = $this->existingImages[$index]['id'];
            unset($this->existingImages[$index]);
            $this->existingImages = array_values($this->existingImages);
            
            // Disparar evento para que Alpine.js actualice el contador
            $this->dispatch('image-removed');
        }
    }

    public function removeImage($index)
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
            
            // Disparar evento para que Alpine.js actualice el contador
            $this->dispatch('image-removed');
        }
    }

    public function render()
    {
        return view('livewire.product-form-edit');
    }
}