<div x-data="productFormEdit()" class="mx-auto bg-white rounded-xl shadow-md overflow-hidden mb-4 max-w-5xl">
  <!-- Header -->
  <div class="bg-gray-50 px-6 py-4 border-b">
    <div class="flex justify-between items-center mb-2">
      <h1 class="text-2xl font-bold text-gray-800">
        Editar Producto
      </h1>
    </div>
  </div>

  <form wire:submit.prevent="save" id="product-form">
    <div class="p-6">
      <!-- Información básica - Solo lectura -->
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Información del producto</h2>
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Categoría - Read Only -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
              @php
                $category = \App\Models\Category::find($category_id);
                $parentCategory = $category ? $category->parent : null;
              @endphp
              @if($parentCategory && $category)
                {{ $parentCategory->name }} > {{ $category->name }}
              @elseif($category)
                {{ $category->name }}
              @else
                Categoría no especificada
              @endif
            </div>
          </div>

          <!-- Título - Read Only -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nombre del producto
            </label>
            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
              {{ $title }}
            </div>
          </div>

          <!-- Precio y Condición - Read Only -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Precio -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Precio referencial</label>
              <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
                ${{ number_format($price_reference, 0, ',', '.') }} CLP
              </div>
            </div>

            <!-- Condición -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Condición</label>
              <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 capitalize">
                @php
                  $traducciones = [
                    'new' => 'Nuevo',
                    'used' => 'Usado', 
                    'refurbished' => 'Restaurado'
                  ];
                  echo $traducciones[$condition] ?? $condition;
                @endphp
              </div>
            </div>
          </div>

          <!-- Descripción - EDITABLE -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Descripción <span class="text-red-500">*</span>
            </label>
            <textarea 
              wire:model="description" 
              rows="4"
              required
              placeholder="Describe tu producto con detalles importantes como marca, modelo, características, etc." 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            ></textarea>
            @error('description')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Ubicación - Read Only -->
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Ubicación</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Región -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Región</label>
            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
              {{ $selectedRegion ?? 'No especificada' }}
            </div>
          </div>

          <!-- Comuna -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Comuna</label>
            <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600">
              {{ $location }}
            </div>
          </div>
        </div>
      </div>

      <!-- Productos de interés - EDITABLE -->
      <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Productos de interés para permuta</h2>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-4">
            ¿Qué productos te interesaría recibir en la permuta?
            <span class="text-blue-600 font-normal">(Máximo 10 opciones)</span>
          </label>
          
          <!-- Contador y badges de selección -->
          <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-medium" 
                  :class="selectedTags.length >= 10 ? 'text-red-600' : 'text-gray-600'"
                  x-text="'Seleccionados: ' + selectedTags.length + '/10'"></span>
            <button 
              type="button" 
              @click="clearAllTags()" 
              x-show="selectedTags.length > 0"
              class="text-sm text-red-600 hover:text-red-800 font-medium"
            >
              Limpiar todos
            </button>
          </div>

          <!-- Alerta cuando se alcanza el límite -->
          <div x-show="selectedTags.length >= 10" class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex items-center">
              <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
              <span class="text-sm text-yellow-800 font-medium">
                Has alcanzado el límite máximo de 10 intereses. 
                <span x-show="selectedTags.length > 10" class="text-red-600">
                  Por favor, elimina algunos para continuar.
                </span>
              </span>
            </div>
          </div>

          <!-- Badges de tags seleccionados -->
          <div class="flex flex-wrap gap-2 mb-6 p-4 bg-gray-50 rounded-lg border" x-show="selectedTags.length > 0">
            <template x-for="(tag, index) in selectedTags" :key="index">
              <span class="bg-blue-100 text-blue-800 text-sm px-3 py-2 rounded-full font-medium flex items-center shadow-sm">
                <span x-text="tag" class="max-w-xs truncate"></span>
                <button 
                  type="button" 
                  @click="removeTag(index)" 
                  class="ml-2 rounded-full flex-shrink-0 text-blue-600 hover:text-blue-800 hover:bg-blue-200 w-5 h-5 flex items-center justify-center text-xs"
                >
                  &times;
                </button>
              </span>
            </template>
          </div>

          <!-- Sistema de selección por categorías -->
          <div class="space-y-6">
            <!-- Buscador rápido -->
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input 
                type="text" 
                x-model="tagSearch"
                @input.debounce.300ms="filterCategories()"
                placeholder="Buscar productos específicos..." 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
              />
              <button 
                x-show="tagSearch.length > 0"
                @click="tagSearch = ''; filterCategories()"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
              >
                &times;
              </button>
            </div>

            <!-- Categorías de productos -->
            <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-white">
              <template x-for="(category, categoryName) in filteredCategoryGroups" :key="categoryName">
                <div class="mb-6 last:mb-0">
                  <h3 class="font-semibold text-gray-800 mb-3 text-lg border-b pb-2" x-text="categoryName"></h3>
                  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    <template x-for="tag in category" :key="tag">
                      <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-all duration-200"
                            :class="{
                              'bg-blue-50 border-blue-300': selectedTags.includes(tag),
                              'opacity-50 cursor-not-allowed': selectedTags.length >= 10 && !selectedTags.includes(tag)
                            }">
                        <input 
                          type="checkbox" 
                          x-model="selectedTags"
                          :value="tag"
                          @change="updateLivewireTags()"
                          :disabled="selectedTags.length >= 10 && !selectedTags.includes(tag)"
                          class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3"
                        >
                        <span class="text-sm text-gray-700" x-text="tag"></span>
                      </label>
                    </template>
                  </div>
                </div>
              </template>

              <!-- Mensaje cuando no hay resultados -->
              <div x-show="Object.keys(filteredCategoryGroups).length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-search text-2xl mb-2"></i>
                <p>No se encontraron productos con ese nombre</p>
                <button 
                  type="button" 
                  @click="tagSearch = ''; filterCategories()"
                  class="text-blue-600 hover:text-blue-800 mt-2 text-sm font-medium"
                >
                  Mostrar todas las categorías
                </button>
              </div>
            </div>
          </div>

          <!-- Información adicional -->
          <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
              <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-3"></i>
              <div>
                <p class="text-sm text-blue-800">
                  <strong>Importante:</strong> Puedes seleccionar hasta 10 productos de interés. 
                  Esto ayuda a encontrar mejores oportunidades de permuta sin saturar las opciones.
                </p>
              </div>
            </div>
          </div>

          @error('tags')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Imágenes - EDITABLE - SISTEMA MEJORADO -->
      <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Imágenes del producto</h2>
        <p class="text-gray-600 mb-6">Puedes actualizar las imágenes del producto. La primera imagen será la destacada. Máximo 4 imágenes.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <!-- Área de carga de imágenes -->
          <div>
            <div 
              class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center cursor-pointer hover:border-blue-400 transition-all duration-200"
              @click="$refs.imageInput.click()"
              @drop="handleImageDrop($event)"
              @dragover.prevent
              @dragenter.prevent
              id="dropzone"
            >
              <div class="flex flex-col items-center justify-center">
                <div class="p-3 bg-blue-100 rounded-full mb-4">
                  <i class="fas fa-cloud-upload-alt text-blue-500 text-2xl"></i>
                </div>
                <p class="text-gray-700 font-medium">Haz clic o arrastra imágenes aquí</p>
                <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG. Máx. 5MB por imagen</p>
              </div>
            </div>
            
            <!-- Input de imágenes -->
            <input 
              x-ref="imageInput"
              type="file" 
              wire:model="images"
              multiple 
              accept="image/*"
              @change="handleImageSelection($event)"
              class="hidden"
            />
            
            <p class="text-xs text-gray-500 mt-3 text-center">
              Total: <span x-text="totalImages"></span> de 4 imágenes
              <span x-show="totalImages === 0" class="text-red-500 ml-2">(Se requiere al menos 1 imagen)</span>
            </p>
            
            <div x-show="totalImages >= 4" class="mt-1">
              <p class="text-xs text-red-500 text-center">Has alcanzado el límite máximo de 4 imágenes</p>
            </div>

            <!-- Alerta de validación de imágenes -->
            <div x-show="showImageError" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
              <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                <span class="text-sm text-red-700">Debes tener al menos una imagen para guardar el producto</span>
              </div>
            </div>
            
            <!-- Errores de Livewire -->
            @error('images.*')
              <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                  <span class="text-sm text-red-700">{{ $message }}</span>
                </div>
              </div>
            @enderror
            
            @error('images')
              <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                  <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                  <span class="text-sm text-red-700">{{ $message }}</span>
                </div>
              </div>
            @enderror
          </div>

          <!-- Previsualización de imágenes - SISTEMA UNIFICADO -->
          <div x-show="previewImages.length > 0">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Imágenes actuales y nuevas:</h3>
            <div class="grid grid-cols-2 gap-4">
              <template x-for="(image, index) in previewImages" :key="index">
                <div class="relative group">
                  <img 
                    :src="image.url" 
                    :alt="'Imagen ' + (index + 1)" 
                    class="w-full h-40 object-cover rounded-lg border"
                    :class="{'ring-2 ring-blue-500': index === 0}"
                  />
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center rounded-lg transition">
                    <button 
                      type="button" 
                      @click="removeImage(index, image.type)" 
                      class="text-white opacity-0 group-hover:opacity-100 transition p-1 bg-red-500 rounded-full"
                      :disabled="isRemovingImage"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                  <div x-show="index === 0" class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                    Destacada
                  </div>
                  <div x-show="image.type === 'new'" class="absolute top-2 right-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                    Nueva
                  </div>
                </div>
              </template>
            </div>
          </div>

          <!-- Mensaje cuando no hay imágenes -->
          <div x-show="previewImages.length === 0" class="col-span-2 text-center py-8 text-gray-400">
            <i class="fas fa-images text-2xl mb-2"></i>
            <p class="text-sm">No hay imágenes seleccionadas</p>
            <p class="text-xs text-red-500 mt-1">Debes agregar al menos una imagen</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Botones de acción -->
    <div class="px-6 py-4 bg-gray-50 border-t flex justify-between items-center">
      <a 
        href="{{ route('dashboard.my-products') }}" 
        class="px-5 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
      >
        <i class="fas fa-arrow-left mr-2"></i> Volver
      </a>

      <div class="flex space-x-3">
        <button 
          type="button" 
          onclick="window.location.href='{{ route('dashboard.my-products') }}'" 
          class="px-5 py-2 bg-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-400 transition"
        >
          Cancelar
        </button>
        <button 
          type="submit" 
          x-bind:disabled="totalImages === 0"
          x-bind:class="totalImages === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
          class="px-5 py-2 text-white font-medium rounded-lg transition"
        >
          <i class="fas fa-save mr-2"></i> Guardar cambios
        </button>
      </div>
    </div>
  </form>
</div>

<script>
function productFormEdit() {
    return {
        // Propiedades para el sistema de tags mejorado
        tagSearch: '',
        categoryGroups: @json($categoryGroups),
        filteredCategoryGroups: {},
        
        // Inicializar selectedTags desde los tags del producto
        selectedTags: [],
        
        // Control de imágenes - SISTEMA MEJORADO
        previewImages: [],
        totalImages: 0,
        showImageError: false,
        isRemovingImage: false,
        
        init() {
            // Inicializar tags desde los datos del producto
            this.initializeTags();
            
            // Inicializar imágenes existentes
            this.initializeExistingImages();
            
            // Inicializar categorías filtradas
            this.filteredCategoryGroups = this.categoryGroups;

            // Inicializar dropzone para arrastrar y soltar
            this.initializeDropzone();

            // Escuchar cambios en las imágenes de Livewire
            this.setupImageListeners();

            console.log('Imágenes inicializadas:', this.previewImages);
            console.log('Total de imágenes:', this.totalImages);
        },

        initializeTags() {
            // Obtener los tags del producto y convertirlos en array
            const productTags = '{{ $tags }}';
            if (productTags && productTags.trim() !== '') {
                // Separar por comas y limpiar espacios
                this.selectedTags = productTags.split(',').map(tag => tag.trim()).filter(tag => tag !== '');
            } else {
                this.selectedTags = [];
            }
            
            // Actualizar Livewire con los tags iniciales
            this.$wire.set('tags', this.selectedTags.join(','));
        },

        initializeExistingImages() {
            // Cargar imágenes existentes desde Livewire
            const existingImages = @json($existingImages);
            this.previewImages = existingImages.map(img => ({
                ...img,
                type: 'existing'
            }));
            this.updateImageCount();
        },

        updateLivewireTags() {
            // Actualizar Livewire solo cuando se modifiquen los tags
            this.$wire.set('tags', this.selectedTags.join(','));
        },

        removeTag(index) {
            this.selectedTags = this.selectedTags.filter((_, i) => i !== index);
            this.updateLivewireTags();
        },

        clearAllTags() {
            this.selectedTags = [];
            this.updateLivewireTags();
        },

        filterCategories() {
            if (!this.tagSearch.trim()) {
                this.filteredCategoryGroups = this.categoryGroups;
                return;
            }

            const searchTerm = this.tagSearch.toLowerCase().trim();
            this.filteredCategoryGroups = {};

            for (const [categoryName, tags] of Object.entries(this.categoryGroups)) {
                const filteredTags = tags.filter(tag => 
                    tag.toLowerCase().includes(searchTerm)
                );
                
                if (filteredTags.length > 0) {
                    this.filteredCategoryGroups[categoryName] = filteredTags;
                }
            }
        },

        // SISTEMA DE IMÁGENES MEJORADO
        initializeDropzone() {
            const dropzone = document.getElementById('dropzone');
            if (dropzone) {
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-blue-400', 'bg-blue-50');
                });

                dropzone.addEventListener('dragleave', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-blue-400', 'bg-blue-50');
                });
            }
        },

        handleImageDrop(event) {
            event.preventDefault();
            const dropzone = document.getElementById('dropzone');
            dropzone.classList.remove('border-blue-400', 'bg-blue-50');
            
            const files = Array.from(event.dataTransfer.files);
            this.processImageFiles(files);
        },

        handleImageSelection(event) {
            const files = Array.from(event.target.files);
            this.processImageFiles(files);
        },

        processImageFiles(files) {
            const imageFiles = files.filter(file => file.type.startsWith('image/'));
            
            const remainingSlots = 4 - this.previewImages.length;
            if (remainingSlots <= 0) {
                alert('Solo puedes subir hasta 4 imágenes.');
                return;
            }

            const filesToProcess = imageFiles.slice(0, remainingSlots);
            
            filesToProcess.forEach(file => {
                if (file.size > 5 * 1024 * 1024) {
                    alert('La imagen ' + file.name + ' es demasiado grande. El tamaño máximo es 5MB.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = e => {
                    this.previewImages.push({
                        url: e.target.result,
                        type: 'new',
                        file: file
                    });
                    this.updateImageCount();
                };
                reader.readAsDataURL(file);
            });

            // Actualizar Livewire con las nuevas imágenes
            const currentImages = this.$wire.get('images') || [];
            const newImages = [...currentImages, ...filesToProcess];
            
            // Si excedemos el límite, tomar solo las primeras 4
            if (newImages.length > 4) {
                newImages.splice(4);
            }
            
            this.$wire.set('images', newImages);
        },

        async removeImage(index, type) {
            if (this.isRemovingImage) return;
            
            this.isRemovingImage = true;
            
            try {
                if (type === 'existing') {
                    // Llamar a Livewire para eliminar imagen existente
                    await this.$wire.removeExistingImage(index);
                } else {
                    // Llamar a Livewire para eliminar imagen nueva
                    await this.$wire.removeImage(index);
                }
                
                // Remover la previsualización
                this.previewImages.splice(index, 1);
                this.updateImageCount();
                
            } catch (error) {
                console.error('Error al eliminar imagen:', error);
                alert('Error al eliminar la imagen. Por favor, intenta nuevamente.');
            } finally {
                this.isRemovingImage = false;
            }
        },

        updateImageCount() {
            this.totalImages = this.previewImages.length;
            this.showImageError = this.totalImages === 0;
        },

        setupImageListeners() {
            // Escuchar eventos de Livewire para actualizar cuando se eliminen imágenes
            Livewire.on('image-removed', () => {
                // Recargar las imágenes existentes después de una eliminación
                this.initializeExistingImages();
            });
        }
    };
}

// Inicializar cuando Livewire esté listo
document.addEventListener('livewire:init', () => {
    // Escuchar eventos de Livewire si es necesario
});
</script>