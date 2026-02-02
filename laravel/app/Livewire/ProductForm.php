<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductForm extends Component
{
    use WithFileUploads;

    public $categories;
    public $categoryGroups;
    public $parentCategories;
    public $title;
    public $category_id;
    public $price_reference;
    public $description;
    public $condition = '';
    public $tags;
    public $images = [];
    public $location;
    public $paymentOption = 'free';
    public $latitude;
    public $longitude;
    public $selectedRegion;
    public $regionesComunas;
    public $acceptTerms = false;
    public $imageOrder = [];
    
    // Nueva propiedad para mostrar progreso
    public $uploadProgress = 0;
    public $isUploading = false;

    protected $rules = [
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:60',
        'price_reference' => 'required|numeric|min:10000|max:500000000',
        'description' => 'required|string',
        'condition' => 'required|in:new,used,refurbished',
        'tags' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        'location' => 'required|string',
        'paymentOption' => 'required|in:free,paid'
    ];

    protected $messages = [
        'images.*.image' => 'El archivo debe ser una imagen válida',
        'images.*.mimes' => 'Solo se permiten formatos: JPEG, PNG, JPG, WEBP',
        'images.*.max' => 'La imagen no debe pesar más de 5MB',
    ];

    public function mount()
    {
        $parentCategories = Category::with('children')->whereNull('parent_id')->get();
        
        $this->categoryGroups = [];
        foreach ($parentCategories as $category) {
            $this->categoryGroups[$category->name] = $category->children->pluck('name')->toArray();
        }
        $this->parentCategories = Category::with('children')->whereNull('parent_id')->get();
        $this->categories = Category::all();
        
        $this->regionesComunas = config('chile_regions.regiones');
        
        $this->imageOrder = [];
    }

    public function save()
    {
        if (!$this->acceptTerms) {
            $this->addError('acceptTerms', 'Debes aceptar los términos y condiciones');
            return;
        }

        $this->validate();

        try {
            // Marcar que estamos subiendo
            $this->isUploading = true;
            $this->uploadProgress = 10;
            $this->dispatch('upload-progress', ['progress' => 10]);
            
            $this->getCoordinates();
            
            $this->uploadProgress = 20;
            $this->dispatch('upload-progress', ['progress' => 20]);
            
            $status = $this->paymentOption === 'paid' ? 'pending' : 'available';
            $published = $this->paymentOption === 'free';

            // CREAR PRODUCTO PRIMERO
            $product = Product::create([
                'user_id' => auth()->id(),
                'category_id' => $this->category_id,
                'title' => $this->title,
                'price_reference' => $this->price_reference,
                'description' => $this->description,
                'condition' => $this->condition,
                'tags' => $this->tags,
                'location' => $this->location,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => $status,
                'published' => $published,
                'payment_option' => $this->paymentOption,
                'was_paid' => false,
                'publication_date' => now(),
                'expiration_date' => now()->addDays(30)
            ]);

            $this->uploadProgress = 30;
            $this->dispatch('upload-progress', ['progress' => 30]);

            // PROCESAR IMÁGENES CON MÉTODO OPTIMIZADO
            if (!empty($this->images)) {
                $this->processImagesOptimized($product);
            }

            $this->uploadProgress = 100;
            $this->dispatch('upload-progress', ['progress' => 100]);

            // Pequeña pausa para mostrar el 100%
            sleep(1);

            // Disparar evento para limpiar formulario en el frontend
            $this->dispatch('product-created');

            $this->dispatch('product-saved', [
                'productId' => $product->id, 
                'paymentOption' => $this->paymentOption
            ]);

            $this->isUploading = false;
            
            return $this->handleRedirect($product);
            
        } catch (\Exception $e) {
            Log::error("Error al guardar producto: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            $this->isUploading = false;
            
            $this->dispatch('save-error', [
                'message' => 'Error al publicar el producto: ' . $e->getMessage()
            ]);
            
            session()->flash('error', 'Error al publicar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Método optimizado para procesar imágenes
     */
    private function processImagesOptimized(Product $product)
    {
        if (!$this->images || count($this->images) === 0) {
            return;
        }

        $orderedImages = $this->getOrderedImages();
        $totalImages = count($orderedImages);
        $processed = 0;

        foreach ($orderedImages as $index => $image) {
            try {
                if (!$image->isValid()) {
                    Log::warning("Imagen inválida en índice: $index");
                    continue;
                }

                // OPCIÓN 1: Intentar procesamiento rápido con GD
                $path = $this->processImageWithGD($image, $product->id, $index);
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'order' => $index
                ]);

                $processed++;
                
                // Actualizar progreso
                $progress = 30 + (($processed / $totalImages) * 60);
                $this->uploadProgress = (int)$progress;
                $this->dispatch('upload-progress', ['progress' => (int)$progress]);

                Log::info("Imagen $index procesada: $path");

            } catch (\Exception $e) {
                Log::error("Error al procesar imagen $index: " . $e->getMessage());
                
                // Fallback: subir imagen original si falla la optimización
                try {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'order' => $index
                    ]);
                    
                    $processed++;
                    
                    // Actualizar progreso
                    $progress = 30 + (($processed / $totalImages) * 60);
                    $this->uploadProgress = (int)$progress;
                    $this->dispatch('upload-progress', ['progress' => (int)$progress]);
                    
                    Log::info("Imagen $index subida sin optimización: $path");
                } catch (\Exception $fallbackError) {
                    Log::error("Error en fallback de imagen $index: " . $fallbackError->getMessage());
                    continue;
                }
            }
        }

        if ($processed === 0) {
            throw new \Exception('No se pudieron subir las imágenes del producto');
        }
    }

    /**
     * Procesamiento rápido con GD nativo (más rápido que Intervention)
     */
    private function processImageWithGD($image, $productId, $index)
    {
        $originalPath = $image->getRealPath();
        $imageInfo = getimagesize($originalPath);
        
        if (!$imageInfo) {
            throw new \Exception('No se pudo leer la imagen');
        }

        $mime = $imageInfo['mime'];
        
        // Crear imagen desde el archivo según el tipo
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($originalPath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($originalPath);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($originalPath);
                break;
            default:
                throw new \Exception('Formato de imagen no soportado: ' . $mime);
        }

        if (!$sourceImage) {
            throw new \Exception('No se pudo crear la imagen GD');
        }

        // Obtener dimensiones originales
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calcular nuevas dimensiones (máximo 1200px en el lado más largo)
        $maxSize = 1200;
        
        if ($originalWidth > $originalHeight && $originalWidth > $maxSize) {
            $newWidth = $maxSize;
            $newHeight = (int)($originalHeight * ($maxSize / $originalWidth));
        } elseif ($originalHeight > $originalWidth && $originalHeight > $maxSize) {
            $newHeight = $maxSize;
            $newWidth = (int)($originalWidth * ($maxSize / $originalHeight));
        } else {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        }

        // Crear nueva imagen redimensionada
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG
        if ($mime === 'image/png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Redimensionar
        imagecopyresampled(
            $resizedImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Generar nombre único
        $fileName = 'product_' . $productId . '_' . uniqid() . '.webp';
        $path = 'products/' . $fileName;
        $fullPath = storage_path('app/public/' . $path);

        // Convertir a WebP con calidad 80%
        imagewebp($resizedImage, $fullPath, 80);

        // Liberar memoria
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        return $path;
    }

    /**
     * Método alternativo más simple (sin compresión pesada)
     */
    private function processImageSimple($image, $productId, $index)
    {
        // Solo redimensionar si es muy grande
        $maxSize = 1920; // 1920px máximo
        
        $manager = new ImageManager(new Driver());
        $image = $manager->read($image->getRealPath());
        
        // Redimensionar solo si es necesario
        $width = $image->width();
        $height = $image->height();
        
        if ($width > $maxSize || $height > $maxSize) {
            $image->scaleDown($maxSize, $maxSize);
        }
        
        // Guardar como JPEG con calidad 85% (más rápido que WebP)
        $fileName = 'product_' . $productId . '_' . uniqid() . '.jpg';
        $path = 'products/' . $fileName;
        
        $encodedImage = $image->toJpeg(85);
        Storage::disk('public')->put($path, $encodedImage);
        
        return $path;
    }

    /**
     * Obtener imágenes en el orden correcto
     */
    private function getOrderedImages()
    {
        if (!empty($this->imageOrder) && count($this->imageOrder) === count($this->images)) {
            $orderedImages = [];
            foreach ($this->imageOrder as $index) {
                if (isset($this->images[$index])) {
                    $orderedImages[] = $this->images[$index];
                }
            }
            return $orderedImages;
        }

        return $this->images;
    }

    /**
     * Manejar redirecciones
     */
    private function handleRedirect(Product $product)
    {
        if ($this->paymentOption === 'paid') {
            return redirect()->route('checkout.show', $product);
        }
        
        if ($this->paymentOption === 'free') {
            session(['new_product_id' => $product->id]);
            return redirect()->route('products.suggestions', $product);
        }

        session()->flash('success', 'Producto publicado correctamente');
        return redirect()->route('dashboard.my-products');
    }
    
    /**
     * Obtener coordenadas (optimizado)
     */
    private function getCoordinates()
    {
        try {
            if (empty($this->location)) {
                $this->latitude = null;
                $this->longitude = null;
                return;
            }

            // Cache simple para evitar llamadas repetitivas
            $cacheKey = 'coords_' . md5($this->location);
            
            if (cache()->has($cacheKey)) {
                $coords = cache()->get($cacheKey);
                $this->latitude = $coords['lat'];
                $this->longitude = $coords['lon'];
                return;
            }

            $response = Http::timeout(5) // Reducir timeout
                ->withHeaders([
                    'User-Agent' => 'TruMarketApp/1.0'
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $this->location . ', Chile',
                    'format' => 'json',
                    'limit' => 1
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (!empty($data)) {
                    $this->latitude = $data[0]['lat'];
                    $this->longitude = $data[0]['lon'];
                    
                    // Cache por 24 horas
                    cache()->put($cacheKey, [
                        'lat' => $this->latitude,
                        'lon' => $this->longitude
                    ], now()->addHours(24));
                    
                    Log::info("Coordenadas obtenidas para {$this->location}");
                } else {
                    $this->latitude = null;
                    $this->longitude = null;
                }
            } else {
                $this->latitude = null;
                $this->longitude = null;
            }
        } catch (\Exception $e) {
            Log::error("Error al obtener coordenadas: " . $e->getMessage());
            $this->latitude = null;
            $this->longitude = null;
        }
    }

    /**
     * Remover imagen del array temporal
     */
    public function removeImage($index)
    {
        try {
            if (isset($this->images[$index])) {
                unset($this->images[$index]);
                $this->images = array_values($this->images);
                
                if (!empty($this->imageOrder)) {
                    $this->imageOrder = array_values(array_filter($this->imageOrder, function($i) use ($index) {
                        return $i !== $index;
                    }));
                    $this->imageOrder = array_values($this->imageOrder);
                }
                
                $this->dispatch('image-removed', ['index' => $index]);
            }
        } catch (\Exception $e) {
            Log::error("Error al eliminar imagen: " . $e->getMessage());
        }
    }

    /**
     * Validación en tiempo real
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['title', 'price_reference', 'description', 'condition'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}