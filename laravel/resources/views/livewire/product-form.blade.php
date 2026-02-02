<div x-data="productWizard()" x-cloak class="mx-auto bg-white rounded-xl shadow-md overflow-hidden mb-4 max-w-5xl">
  <!-- Header con indicador de pasos -->
  <div class="bg-gray-50 px-6 py-4 border-b">
    <div class="flex justify-between items-center mb-2">
      <h1 class="text-2xl font-bold text-gray-800">
        Publicar Nuevo Producto
      </h1>
      <span class="text-sm font-medium text-gray-600">Paso <span x-text="currentStep"></span> de 4</span>
    </div>
    
    <!-- Barra de progreso -->
    <div class="w-full bg-gray-200 rounded-full h-2.5">
      <div class="bg-blue-600 h-2.5 rounded-full" :style="'width: ' + (currentStep * 25) + '%'"></div>
    </div>
  </div>

  <form wire:submit.prevent="save" id="product-form">
    <!-- Contenedor de pasos -->
    <div class="p-6">
      <!-- Paso 1: Categoría -->
      <div x-show="currentStep === 1" x-transition>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Selecciona una categoría</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Selección de categoría padre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría principal</label>
            <select 
              x-model="selectedParentCategory"
              @change="loadSubcategories()"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Seleccione una categoría</option>
              @foreach($parentCategories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          
          <!-- Selección de subcategoría -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Subcategoría</label>
            <select 
              wire:model="category_id"
              :disabled="!selectedParentCategory"
              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
            >
              <option value="">Primero seleccione una categoría</option>
              <template x-for="subcategory in subcategories" :key="subcategory.id">
                <option :value="subcategory.id" x-text="subcategory.name"></option>
              </template>
            </select>
          </div>
        </div>
      </div>

      <!-- Paso 2: Información básica -->
      <div x-show="currentStep === 2" x-transition>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Información del producto</h2>
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Título -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del producto o servicio</label>
            <input 
              type="text" 
              wire:model="title" 
              maxlength="60"
              required 
              placeholder="Ej. Televisor LED 32 pulgadas Samsung" 
              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
            />
            <div class="flex justify-between text-xs text-gray-500 mt-1">
              <span>Máximo 60 caracteres</span>
              <span x-text="60 - ($wire.get('title')?.length || 0)"></span>
            </div>
            @error('title')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Precio y Condición -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Precio -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Precio referencial</label>
              <div class="relative">
                <span class="absolute left-3 top-3 text-gray-500">$</span>
                <input 
                  type="number" 
                  wire:model="price_reference" 
                  required 
                  min="15000"
                  max="500000000"
                  placeholder="15000" 
                  class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                />
              </div>
              @error('price_reference')
                <p class="text-red-500 text-sm mt-1">El precio debe estar entre $10.000 y $500.000.000 CLP</p>
              @enderror
              <p class="text-xs text-gray-500 mt-1">El precio debe estar entre $10.000 y $500.000.000 CLP</p>
            </div>

            <!-- Condición -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Condición</label>
              <select 
                wire:model="condition" 
                required 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Seleccione condición</option>
                <option value="new">Nuevo</option>
                <option value="used">Usado</option>
                <option value="refurbished">Restaurado</option>
              </select>
              @error('condition')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción <span class="text-red-500">*</span></label>
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

      <!-- Paso 3: Ubicación y Tags -->
      <div x-show="currentStep === 3" x-transition>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Ubicación e intereses</h2>
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Región y Comuna -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Región -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Región</label>
              <select 
                x-model="selectedRegion"
                @change="comunas = selectedRegion ? regionesComunas[selectedRegion] : []; $wire.set('selectedRegion', selectedRegion);"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Seleccione una región</option>
                @foreach($regionesComunas as $region => $comunas)
                  <option value="{{ $region }}">{{ $region }}</option>
                @endforeach
              </select>
            </div>

            <!-- Comuna -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Comuna</label>
              <select 
                wire:model="location" 
                required 
                :disabled="!selectedRegion"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent disabled:bg-gray-100 disabled:cursor-not-allowed"
              >
                <option value="">Seleccione una comuna</option>
                <template x-for="comuna in comunas">
                  <option x-text="comuna" :value="comuna"></option>
                </template>
              </select>
              @error('location')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- PRODUCTOS DE INTERÉS -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-4">
              ¿Qué productos te interesaría recibir en la permuta?
              <span class="text-blue-600 font-normal">(Selecciona varias opciones)</span>
            </label>
            
            <!-- Contador y badges de selección -->
            <div class="flex items-center justify-between mb-4">
              <span class="text-sm text-gray-600" x-text="'Seleccionados: ' + selectedTags.length"></span>
              <button 
                type="button" 
                @click="clearAllTags()" 
                x-show="selectedTags.length > 0"
                class="text-sm text-red-600 hover:text-red-800 font-medium"
              >
                Limpiar todos
              </button>
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
                               :class="{'bg-blue-50 border-blue-300': selectedTags.includes(tag)}">
                          <input 
                            type="checkbox" 
                            x-model="selectedTags"
                            :value="tag"
                            @change="updateLivewireTags()"
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
          </div>
        </div>
      </div>

      <!-- Paso 4: Imágenes - ACTUALIZADO CON ARRASTRE -->
      <div x-show="currentStep === 4" x-transition>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Imágenes del producto</h2>
        <p class="text-gray-600 mb-6">Arrastra las imágenes para cambiar el orden. La primera imagen será la destacada. Puedes subir hasta 4 imágenes.</p>

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
                <p class="text-xs text-gray-500 mt-2">Formatos: JPG, PNG, WEBP. Máx. 5MB por imagen</p>
              </div>
            </div>
            <input 
              x-ref="imageInput"
              type="file" 
              wire:model="images" 
              multiple 
              accept="image/*"
              @change="handleImageSelection($event)"
              class="hidden"
            />
            <p class="text-xs text-gray-500 mt-3 text-center" x-text="'Total: ' + (previewImages.length) + ' de 4 imágenes'"></p>
            <p x-show="previewImages.length >= 4" class="text-xs text-red-500 mt-1 text-center">Has alcanzado el límite máximo de 4 imágenes</p>
          </div>

          <!-- Previsualización de imágenes CON ARRASTRE -->
          <div x-show="previewImages.length > 0">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Vista previa (arrastra para cambiar el orden):</h3>
            <div class="grid grid-cols-2 gap-4" 
                 x-sortable="previewImages"
                 @sortable-updated="updateImageOrder">
              <template x-for="(src, index) in previewImages" :key="index">
                <div class="relative group cursor-move"
                     :draggable="true"
                     @dragstart="dragStart(index)"
                     @dragover.prevent="dragOver(index)"
                     @dragenter="dragEnter(index)"
                     @dragleave="dragLeave(index)"
                     @drop="drop(index)"
                     :class="{
                       'border-2 border-blue-500': draggedOverIndex === index,
                       'ring-2 ring-blue-500': index === 0
                     }">
                  <img 
                    :src="src" 
                    :alt="'Preview ' + (index + 1)" 
                    class="w-full h-40 object-cover rounded-lg border"
                  />
                  <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center rounded-lg transition">
                    <button 
                      type="button" 
                      @click="removeImage(index)" 
                      class="text-white opacity-0 group-hover:opacity-100 transition p-1 bg-red-500 rounded-full"
                      :disabled="isRemovingImage"
                    >
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                  <div x-show="index === 0" class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                    Destacada
                  </div>
                  <div class="absolute top-2 right-2 bg-gray-800 bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                    <span x-text="index + 1"></span>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>

        <!-- Términos y condiciones -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <label class="flex items-start space-x-3">
            <input 
              type="checkbox" 
              wire:model="acceptTerms"
              x-model="acceptTerms"
              class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            >
            <span class="text-sm text-gray-700">
              Acepto los <a href="{{ route('terms') }}" target="_blank" class="text-blue-600 hover:underline">términos y condiciones</a> 
              y las <a href="{{ route('privacy') }}" target="_blank" class="text-blue-600 hover:underline">políticas de privacidad</a>
            </span>
          </label>
          @error('acceptTerms')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>
      </div>
    </div>

    <!-- Navegación entre pasos -->
    <div class="px-6 py-4 bg-gray-50 border-t flex flex-col sm:flex-row sm:justify-between gap-2 sm:gap-0">
      <!-- Botón Anterior -->
      <button 
        type="button" 
        @click="currentStep--" 
        x-show="currentStep > 1"
        class="px-5 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition"
      >
        <i class="fas fa-arrow-left mr-2"></i> Anterior
      </button>
      <div x-show="currentStep === 1"></div>

      <!-- Botón Siguiente -->
      <template x-if="currentStep < 4">
        <button 
          type="button" 
          @click="currentStep++" 
          :disabled="!isStepValid(currentStep)"
          :class="isStepValid(currentStep) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
          class="px-5 py-2 text-white font-medium rounded-lg transition mt-2 sm:mt-0"
        >
          Siguiente <i class="fas fa-arrow-right ml-2"></i>
        </button>
      </template>

      <!-- Botones Confirmar / Previsualizar / Publicar -->
      <template x-if="currentStep === 4">
        <div class="flex flex-col sm:flex-row sm:space-x-3 gap-2 mt-2 sm:mt-0">
          <button 
            type="button" 
            @click="showPreviewModal = true" 
            :disabled="!isStepValid(4)"
            :class="isStepValid(4) ? 'bg-gray-600 hover:bg-gray-700' : 'bg-gray-400 cursor-not-allowed'"
            class="px-5 py-2 text-white font-medium rounded-lg transition"
          >
            Previsualizar
          </button>
          <button 
            type="button" 
            @click="showModal = true" 
            :disabled="!isStepValid(4) || !acceptTerms"
            :class="isStepValid(4) && acceptTerms ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
            class="px-5 py-2 text-white font-medium rounded-lg transition"
          >
            PUBLICAR
          </button>
        </div>
      </template>
    </div>
  </form>

  <!-- Modal de previsualización -->
  <div 
    x-show="showPreviewModal" 
    x-cloak 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
  >
    <div class="bg-white rounded-xl shadow-xl w-full max-w-6xl mx-auto max-h-[90vh] overflow-y-auto">
      <!-- Encabezado con estilo personalizado -->
      <div class="col-span-12 rounded-lg px-6 py-6 mb-4 bg-gradient-to-br from-yellow-200 to-yellow-400">
        <h2 class="text-2xl md:text-2xl font-semibold text-black">DETALLES DEL PRODUCTO<br><span class="text-gray-900">(PREVISUALIZACIÓN)</span></h2>
      </div>

      <div class="container mx-auto p-4">
        <!-- Grid principal -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
          
          <!-- Columna principal -->
          <div class="md:col-span-12 space-y-6">
            <!-- Galería + info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white shadow-lg rounded-xl p-6 border border-yellow-200">
              
              <!-- Galería -->
              <div class="space-y-4">
                <img 
                  x-bind:src="previewImages[0] || '/storage/images/default-product.png'" 
                  alt="Vista previa" 
                  class="w-full h-96 object-cover rounded-lg border"
                >
              </div>

              <!-- Información del producto -->
              <div class="space-y-4">
                <!-- Categoría -->
                <div>
                  <template x-if="selectedParentCategory && subcategories.length > 0">
                    <span class="inline-block bg-yellow-400 text-xs text-black px-3 py-1 rounded-full font-medium" 
                          x-text="subcategories.find(sc => sc.id == $wire.get('category_id'))?.name || 'Categoría'">
                    </span>
                  </template>
                </div>

                <!-- Título -->
                <h1 class="text-2xl md:text-3xl font-regular text-gray-900 leading-tight" 
                    x-text="$wire.get('title') || 'Sin título'">
                </h1>

                <!-- Precio -->
                <div class="mt-4">
                  <div class="flex items-end gap-4">
                    <div>
                      <div class="text-4xl md:text-5xl font-semibold text-gray-900" 
                           x-text="'$' + ($wire.get('price_reference') ? new Intl.NumberFormat('es-CL').format($wire.get('price_reference')) : '0')">
                      </div>
                      <div class="text-xs text-gray-500">Precio Referencial</div>
                    </div>
                  </div>
                </div>

                <!-- Tags/Intereses -->
                <div class="my-3">
                  <p class="mb-2"><strong class="text-gray-700">Intereses:</strong></p>
                  <div class="flex flex-wrap gap-1">
                    <template x-for="tag in selectedTags" :key="tag">
                      <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-full font-medium break-words" 
                            x-text="tag">
                      </span>
                    </template>
                    <template x-if="selectedTags.length === 0">
                      <span class="text-gray-500 text-sm">No se han seleccionado intereses</span>
                    </template>
                  </div>
                </div>

                <!-- Ubicación y fecha -->
                <div class="mt-4 flex flex-col gap-2 text-sm text-gray-600">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt w-4 h-4"></i>
                    <span><strong>Ubicación:</strong> <span x-text="$wire.get('location') || 'No especificada'"></span></span>
                  </div>
                  <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-alt w-4 h-4"></i>
                    <span><strong>Fecha de publicación:</strong> <span x-text="new Date().toLocaleDateString('es-CL')"></span></span>
                  </div>
                </div>

                <!-- Condición -->
                <div class="flex items-center gap-2">
                  <div class="text-sm text-gray-600"><strong>Condición:</strong></div>
                  <div class="font-regular text-gray-600">
                    <span x-show="$wire.get('condition') === 'new'">Nuevo</span>
                    <span x-show="$wire.get('condition') === 'used'">Usado</span>
                    <span x-show="$wire.get('condition') === 'refurbished'">Restaurado</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Descripción del producto -->
            <div class="bg-white rounded-lg shadow overflow-hidden border border-yellow-200">
              <div class="bg-yellow-300 px-6 py-3">
                <div class="max-w-7xl mx-auto flex justify-start">
                  <h2 class="text-xl font-semibold text-black text-left">Descripción</h2>
                </div>
              </div>
              <div class="px-6 py-6 max-w-7xl mx-auto">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line" 
                   x-text="$wire.get('description') || 'Sin descripción'">
                </p>
              </div>
            </div>

            <!-- Sección de imágenes adicionales -->
            <div x-show="previewImages.length > 1" class="bg-white rounded-lg shadow overflow-hidden border border-yellow-200">
              <div class="bg-yellow-300 px-6 py-3">
                <h2 class="text-xl font-semibold text-black text-left">Imágenes adicionales</h2>
              </div>
              <div class="px-6 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <template x-for="(src, index) in previewImages" :key="index">
                    <template x-if="index > 0">
                      <div class="relative">
                        <img :src="src" :alt="'Imagen ' + (index + 1)" 
                             class="w-full h-32 object-cover rounded-lg border">
                        <div class="absolute bottom-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                          <span x-text="index + 1"></span>
                        </div>
                      </div>
                    </template>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Botones de acción -->
        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
          <div class="text-sm text-gray-600">
            <i class="fas fa-eye mr-2"></i>
            Esta es una previsualización. Revisa que toda la información sea correcta antes de publicar.
          </div>
          
          <div class="flex flex-col sm:flex-row gap-3">
            <button 
              type="button" 
              @click="showPreviewModal = false" 
              class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium"
            >
              <i class="fas fa-edit mr-2"></i>
              Seguir Editando
            </button>
            <button 
              type="button" 
              @click="showPreviewModal = false; showModal = true;" 
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
            >
              <i class="fas fa-rocket mr-2"></i>
              Continuar con la Publicación
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de métodos de publicación -->
  <div 
    x-show="showModal" 
    x-cloak 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
  >
    <div 
      class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-auto"
      @click.away="showModal = false"
    >
      <h2 class="text-xl font-bold mb-2" x-text="paymentOption === 'free' ? 'Publicación Gratuita' : 'Publicación Destacada'"></h2>
      <p class="text-sm text-gray-600 mb-6" x-text="paymentOption === 'free' ? 'Tu publicación será visible pero tendrá menor prioridad en los resultados de búsqueda. Ideal para probar la plataforma.' : 'Tu publicación tendrá mayor visibilidad y aparecerá en lugares destacados. Se aplicará una comisión del 8% al aceptar un trueque.'"></p>

      <div class="space-y-4 mb-6">
        <label class="flex items-center p-4 border rounded-xl hover:bg-gray-50 cursor-pointer transition"
          :class="{'border-blue-500 bg-blue-50': paymentOption === 'free'}">
          <input 
            type="radio" 
            name="paymentOption"
            x-model="paymentOption"
            value="free" 
            class="h-5 w-5 text-blue-600 mr-3"
          >
          <div>
            <span class="font-medium">Pagar al permutar</span>
            <p class="text-xs text-gray-500 mt-1">Menor visibilidad pago al permutar 5% valor producto</p>
          </div>
        </label>
        
        <label class="flex items-center p-4 border rounded-xl hover:bg-gray-50 cursor-pointer transition"
          :class="{'border-blue-500 bg-blue-50': paymentOption === 'paid'}">
          <input 
            type="radio" 
            name="paymentOption"
            x-model="paymentOption"
            value="paid" 
            class="h-5 w-5 text-blue-600 mr-3"
          >
          <div>
            <span class="font-medium">Pago Inmediato</span>
            <p class="text-xs text-gray-500 mt-1">Mayor visibilidad pago único de 1.000 Pesos</p>
          </div>
        </label>
      </div>

      <div class="bg-gray-50 p-4 rounded-xl mb-6">
        <h3 class="font-medium text-gray-800 mb-2">Resumen:</h3>
        <template x-if="paymentOption === 'free'">
          <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              Publicación gratuita
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              Menor visibilidad en resultados
            </li>
            <li class="flex items-center">
              <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
              Pago al permutar 8% valor producto
            </li>
          </ul>
        </template>
        <template x-if="paymentOption === 'paid'">
          <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              Mayor visibilidad y destacado
            </li>
            <li class="flex items-center">
              <i class="fas fa-check-circle text-green-500 mr-2"></i>
              Aparece en secciones destacadas
            </li>
            <li class="flex items-center">
              <i class="fas fa-info-circle text-blue-500 mr-2"></i>
              Pago único de 1.000 Pesos
            </li>
          </ul>
        </template>
      </div>

      <div class="flex justify-end space-x-3">
        <button 
          type="button" 
          @click="showModal = false" 
          class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition"
          :disabled="isLoading"
        >
          Cancelar
        </button>
        <button 
          type="button" 
          @click="submitForm()" 
          class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center justify-center min-w-[100px]"
          :disabled="isLoading"
        >
          <span x-show="!isLoading">Confirmar</span>
          <span x-show="isLoading">
            <i class="fas fa-spinner fa-spin mr-2"></i>
            Procesando...
          </span>
        </button>
      </div>
    </div>
  </div>

  <!-- Spinner de carga global -->
  <div 
    x-show="isLoading" 
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
      <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
      <span class="text-gray-700">Procesando publicación...</span>
    </div>
  </div>
</div>

<script>
function productWizard() {
    return {
        currentStep: 1,
        showModal: false,
        showPreviewModal: false,
        previewImages: [],
        paymentOption: 'free',
        regionesComunas: @json($regionesComunas),
        selectedRegion: '{{ $selectedRegion ?? '' }}',
        comunas: [],
        acceptTerms: false,
        selectedTags: @json(!empty($tags) ? explode(',', $tags) : []),
        tagInput: '',
        filteredTags: [],
        selectedParentCategory: '',
        subcategories: [],
        parentCategories: @json($parentCategories),
        isLoading: false,
        isRemovingImage: false,

        // Nuevas propiedades para el sistema de tags mejorado
        tagSearch: '',
        categoryGroups: @json($categoryGroups),
        filteredCategoryGroups: {},

        // Nuevas propiedades para drag and drop
        draggedIndex: null,
        draggedOverIndex: null,

        init() {
            // Inicializar comunas si hay una región seleccionada
            if (this.selectedRegion && this.regionesComunas[this.selectedRegion]) {
                this.comunas = this.regionesComunas[this.selectedRegion];
            }
            
            // Inicializar paymentOption desde Livewire si existe
            this.paymentOption = '{{ $paymentOption ?? 'free' }}';
            
            // Inicializar tags desde Livewire
            this.selectedTags = this.selectedTags.length > 0 ? this.selectedTags : [];

            // Inicializar categorías filtradas
            this.filteredCategoryGroups = this.categoryGroups;

            // Sincronizar acceptTerms con Livewire
            this.$watch('acceptTerms', (value) => {
                this.$wire.set('acceptTerms', value);
            });
                        // Escuchar eventos de progreso de Livewire
            Livewire.on('upload-progress', (data) => {
                this.uploadProgress = data.progress;
                console.log('Progreso de subida:', data.progress + '%');
            });

            // Escuchar cuando comienza la subida
            this.$watch('$wire.isUploading', (value) => {
                if (value) {
                    this.showUploadProgress = true;
                } else {
                    // Ocultar después de 2 segundos
                    setTimeout(() => {
                        this.showUploadProgress = false;
                        this.uploadProgress = 0;
                    }, 2000);
                }
            });
            // Escuchar eventos de Livewire para limpiar formulario
            Livewire.on('product-created', () => {
                this.resetForm();
            });

            Livewire.on('product-saved', (data) => {
                console.log('Producto guardado exitosamente:', data);
                this.isLoading = false;
                this.showModal = false;
                // Limpiar formulario después de guardar
                this.resetForm();
            });

            Livewire.on('save-error', (data) => {
                console.error('Error al guardar:', data);
                this.isLoading = false;
                alert('Error: ' + data.message);
            });

            // Inicializar dropzone
            this.initializeDropzone();
        },

        // Nuevo método para resetear el formulario
        resetForm() {
            console.log('Reseteando formulario...');
            
            // Resetear estado de Alpine
            this.currentStep = 1;
            this.previewImages = [];
            this.selectedTags = [];
            this.selectedParentCategory = '';
            this.subcategories = [];
            this.selectedRegion = '';
            this.comunas = [];
            this.acceptTerms = false;
            this.paymentOption = 'free';
            this.tagSearch = '';
            this.filteredCategoryGroups = this.categoryGroups;
            
            // Limpiar inputs de archivo
            if (this.$refs.imageInput) {
                this.$refs.imageInput.value = '';
            }
            
            // Forzar reset de Livewire
            setTimeout(() => {
                if (typeof this.$wire.resetForm === 'function') {
                    this.$wire.resetForm();
                }
            }, 100);
        },

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

        // Métodos para drag and drop de imágenes
        dragStart(index) {
            this.draggedIndex = index;
        },

        dragOver(index) {
            this.draggedOverIndex = index;
        },

        dragEnter(index) {
            this.draggedOverIndex = index;
        },

        dragLeave(index) {
            if (this.draggedOverIndex === index) {
                this.draggedOverIndex = null;
            }
        },

        drop(targetIndex) {
            if (this.draggedIndex === null || this.draggedIndex === targetIndex) {
                this.draggedIndex = null;
                this.draggedOverIndex = null;
                return;
            }

            // Reordenar previewImages
            const movedItem = this.previewImages.splice(this.draggedIndex, 1)[0];
            this.previewImages.splice(targetIndex, 0, movedItem);

            // Reordenar images de Livewire
            const currentImages = this.$wire.get('images') || [];
            if (currentImages.length > 0) {
                const movedFile = currentImages.splice(this.draggedIndex, 1)[0];
                currentImages.splice(targetIndex, 0, movedFile);
                this.$wire.set('images', currentImages);
            }

            // Actualizar el orden en Livewire
            this.updateLivewireImageOrder();

            this.draggedIndex = null;
            this.draggedOverIndex = null;
        },

        updateLivewireImageOrder() {
            // Enviar el nuevo orden a Livewire
            const order = this.previewImages.map((_, index) => index);
            this.$wire.set('imageOrder', order);
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
                    this.previewImages.push(e.target.result);
                    
                    // Actualizar Livewire con el archivo
                    const currentImages = this.$wire.get('images') || [];
                    const newImages = [...currentImages, file];
                    
                    if (newImages.length > 4) {
                        newImages.splice(4);
                    }
                    
                    this.$wire.set('images', newImages);
                    
                    // Actualizar orden
                    this.updateLivewireImageOrder();
                };
                reader.readAsDataURL(file);
            });
        },

        async removeImage(index) {
            if (this.isRemovingImage) return;
            
            this.isRemovingImage = true;
            
            try {
                // Remover la previsualización
                this.previewImages.splice(index, 1);
                
                // Obtener las imágenes actuales de Livewire
                const currentImages = this.$wire.get('images') || [];
                
                // Remover la imagen correspondiente del array de Livewire
                if (currentImages.length > index) {
                    currentImages.splice(index, 1);
                    this.$wire.set('images', currentImages);
                }
                
                // Actualizar orden
                this.updateLivewireImageOrder();
                
                // Llamar al método removeImage de Livewire si existe
                if (typeof this.$wire.removeImage === 'function') {
                    await this.$wire.removeImage(index);
                }
            } catch (error) {
                console.error('Error al eliminar imagen:', error);
                alert('Error al eliminar la imagen. Por favor, intenta nuevamente.');
            } finally {
                this.isRemovingImage = false;
            }
        },

        loadSubcategories() {
            this.subcategories = [];
            this.$wire.set('category_id', '');
            
            if (this.selectedParentCategory) {
                const parent = this.parentCategories.find(cat => cat.id == this.selectedParentCategory);
                if (parent) {
                    this.subcategories = parent.children;
                }
            }
        },

        isStepValid(step) {
            switch(step) {
                case 1:
                    return this.$wire.get('category_id');
                case 2:
                    const price = Number(this.$wire.get('price_reference'));
                    const title = this.$wire.get('title') || '';
                    return title.length > 0 && 
                           title.length <= 60 &&
                           this.$wire.get('price_reference') && 
                           price >= 10000 && 
                           price <= 500000000 &&
                           this.$wire.get('condition') &&
                           this.$wire.get('description') && 
                           this.$wire.get('description').length > 0;
                case 3:
                    const hasLocation = this.$wire.get('location');
                    const hasTags = this.selectedTags.length > 0;
                    return hasLocation && hasTags;
                case 4:
                    return this.previewImages.length > 0 && this.acceptTerms;
                default:
                    return false;
            }
        },

        // Métodos para el sistema de tags mejorado
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

        updateLivewireTags() {
            // Actualizar Livewire solo cuando se modifiquen los tags
            this.$wire.set('tags', this.selectedTags.join(','));
        },

        clearAllTags() {
            this.selectedTags = [];
            this.$wire.set('tags', '');
        },

        removeTag(index) {
            this.selectedTags = this.selectedTags.filter((_, i) => i !== index);
            this.updateLivewireTags();
        },
        
        async submitForm() {
            try {
                this.isLoading = true;
                
                // Mostrar progreso de subida
                this.showUploadProgress = true;
                this.uploadProgress = 0;
                
                // Establecer la opción de pago en Livewire
                await this.$wire.set('paymentOption', this.paymentOption);
                
                // Pequeña pausa para asegurar que Livewire procese el cambio
                await new Promise(resolve => setTimeout(resolve, 100));
                
                console.log('Enviando formulario con paymentOption:', this.paymentOption);
                
                // Disparar el envío del formulario
                this.$wire.save();
                
            } catch (error) {
                console.error('Error al confirmar publicación:', error);
                this.isLoading = false;
                this.showUploadProgress = false;
                alert('Hubo un error al procesar la publicación. Por favor, intenta nuevamente.');
            }
        }
    };
}

// Manejar redirecciones de Livewire
document.addEventListener('livewire:init', () => {
    Livewire.on('redirect', (url) => {
        window.location.href = url;
    });
});
</script>