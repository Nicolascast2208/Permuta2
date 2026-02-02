<div>
    <!-- Botón para mostrar filtros en móviles -->
    <div class="md:hidden mb-4">
        <button 
            type="button"
            wire:click="toggleMobileFilters"
            class="w-full flex items-center justify-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold py-3 px-4 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <span>{{ $showMobileFilters ? 'Ocultar Filtros' : 'Mostrar Filtros' }}</span>
        </button>
    </div>

    <!-- Grid principal: Filtros + Contenido -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- FILTRO LATERAL IZQUIERDO -->
        <aside class="md:col-span-3 space-y-6 {{ $showMobileFilters ? 'block' : 'hidden md:block' }}">
            <div class="bg-yellow-400 shadow border-r border-t border-b rounded-tr-xl rounded-br-xl p-4">
                <h2 class="text-lg font-semibold mb-2">Filtrar por:</h2>
                <hr class="h-px bg-white border-0 my-4">
                
                <!-- Búsqueda con icono -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Buscar producto</label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.500ms="search"
                            class="w-full pl-3 pr-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 bg-white"
                            placeholder="Ej: Nintendo Switch">
                        <!-- Icono de búsqueda -->
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Subcategorías (si es una categoría padre) -->
                @if(!$category->parent_id && $category->children->count() > 0)
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Subcategoría</label>
                    <div class="space-y-2">
                        <button 
                            wire:click="selectSubcategory(null)"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ !$selectedSubcategory ? 'bg-blue-100 text-blue-800 font-medium' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            <span>Todas las subcategorías</span>
                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                {{ $productCounts['current'] ?? 0 }}
                            </span>
                        </button>
                        @foreach($category->children as $subcategory)
                        <button 
                            wire:click="selectSubcategory({{ $subcategory->id }})"
                            class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $selectedSubcategory == $subcategory->id ? 'bg-blue-100 text-blue-800 font-medium' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                            <span>{{ $subcategory->name }}</span>
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full">
                                    {{ $productCounts['subcategories'][$subcategory->id] ?? 0 }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Filtro por estado del producto -->
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Estado del producto</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="condition" value="new" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2">Nuevo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" wire:model.live="condition" value="used" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2">Usado</span>
                        </label>
                    </div>
                </div>

                <!-- Botón para limpiar filtros -->
                <div class="mb-4">
                    <button type="button" wire:click="resetFilters"
                            class="w-full flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-full transition">
                        <!-- Icono de filtros (bars-filter) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Limpiar filtros</span>
                    </button>
                </div>

                <!-- Botón para cerrar filtros en móviles -->
                <div class="md:hidden">
                    <button 
                        type="button"
                        wire:click="toggleMobileFilters"
                        class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-full transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span>Cerrar Filtros</span>
                    </button>
                </div>

            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="md:col-span-9 space-y-6">
            <!-- Migas de pan -->
            <div class="bg-white rounded-lg shadow p-6">
                <nav class="flex items-center text-sm">
                    <a href="{{ route('categories.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Inicio
                    </a>
                    <span class="text-gray-600 mx-2">/</span>
                    @if($category->parent)
                    <a href="{{ route('categories.show', $category->parent->slug) }}" class="text-blue-600 hover:text-blue-800">
                        {{ $category->parent->name }}
                    </a>
                    <span class="text-gray-600 mx-2">/</span>
                    @endif
                    <span class="text-gray-900 font-medium">{{ $category->name }}</span>
                </nav>
            </div>

            <!-- Controles de vista -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-600">
                    {{ $products->total() }} productos encontrados
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Vista:</span>
                    <div class="flex bg-white rounded-lg border border-gray-200 p-1">
                        <button 
                            wire:click="$set('viewMode', 'list')"
                            class="p-2 rounded-md transition-colors {{ $viewMode === 'list' ? 'bg-blue-100 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}"
                            title="Vista de lista">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                        <button 
                            wire:click="$set('viewMode', 'grid')"
                            class="p-2 rounded-md transition-colors {{ $viewMode === 'grid' ? 'bg-blue-100 text-blue-600' : 'text-gray-500 hover:text-gray-700' }}"
                            title="Vista de cuadrícula">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Productos -->
            @if ($products->isEmpty())
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600">No se encontraron productos en esta categoría</p>
                    <button wire:click="resetFilters" class="mt-4 text-blue-600 hover:text-blue-800 underline">
                        Limpiar filtros
                    </button>
                </div>
            @else
                <!-- Vista de Lista -->
                @if($viewMode === 'list')
                    <!-- Tarjetas estilo ficha -->
                    <div class="grid grid-cols-1 gap-6">
                        @foreach ($products as $product)
                            <div class="bg-white rounded-lg shadow-md flex flex-col md:flex-row overflow-hidden">
                                <!-- Imagen -->
                                <div class="w-full md:w-1/3 flex items-center justify-center fondo-gris p-4">
                                    <img src="{{ $product->first_image_url }}" alt="{{ $product->title }}" class="w-full max-h-70 object-cover rounded-lg">
                                </div>

                                <!-- Detalles -->
                                <div class="w-full md:w-2/3 p-4 flex flex-col justify-between fondo-gris">
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <h2 class="text-2xl font-bold text-gray-800">{{ $product->title }}</h2>
                                            <div class="flex space-x-4 texto-naranjo">
                                                <!-- Compartir con menú desplegable -->
                                                <div class="relative" x-data="{ open: false }">
                                                    <button 
                                                        @click="open = !open"
                                                        class="hover:text-black relative z-10">
                                                        <i class="fa-solid fa-share-nodes"></i>
                                                    </button>
                                                    
                                                    <!-- Menú desplegable -->
                                                    <div 
                                                        x-show="open"
                                                        @click.away="open = false"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                        x-transition:enter-end="opacity-100 transform scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 transform scale-100"
                                                        x-transition:leave-end="opacity-0 transform scale-95"
                                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-20"
                                                        style="display: none;"
                                                    >
                                                        <div class="p-2">
                                                            <!-- Facebook -->
                                                            <a 
                                                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $product)) }}&quote={{ urlencode($product->title) }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-md mb-1"
                                                            >
                                                                <i class="fab fa-facebook text-blue-600 w-5 mr-2"></i>
                                                                Facebook
                                                            </a>
                                                            
                                                            <!-- WhatsApp -->
                                                            <a 
                                                                href="https://wa.me/?text={{ urlencode($product->title . ' ' . route('products.show', $product)) }}"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 rounded-md mb-1"
                                                            >
                                                                <i class="fab fa-whatsapp text-green-500 w-5 mr-2"></i>
                                                                WhatsApp
                                                            </a>
                                                            
                                                            <!-- Instagram (compartir via mensaje directo) -->
                                                            <a 
                                                                href="https://www.instagram.com/direct/inbox/"
                                                                target="_blank"
                                                                rel="noopener noreferrer"
                                                                class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 rounded-md mb-1"
                                                            >
                                                                <i class="fab fa-instagram text-pink-500 w-5 mr-2"></i>
                                                                Instagram
                                                            </a>
                                                            
                                                            <!-- Correo -->
                                                            <a 
                                                                href="mailto:?subject={{ rawurlencode('Mira este producto: ' . $product->title) }}&body={{ rawurlencode('Hola, te comparto este producto: ' . $product->title . '\n\n' . route('products.show', $product)) }}"
                                                                class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-md mb-1"
                                                            >
                                                                <i class="fas fa-envelope text-red-500 w-5 mr-2"></i>
                                                                Correo
                                                            </a>
                                                            
                                                            <!-- Copiar enlace -->
                                                            <button 
                                                                @click="
                                                                    navigator.clipboard.writeText('{{ route('products.show', $product) }}');
                                                                    open = false;
                                                                "
                                                                class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                                                            >
                                                                <i class="fas fa-link text-gray-500 w-5 mr-2"></i>
                                                                Copiar enlace
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2 mb-3">
                                            <div class="bg-white p-2 rounded-lg text-sm text-gray-600">
                                                <strong>Estado:</strong> {{ $product->condition_name }}
                                            </div>
                                            <div class="bg-white p-2 rounded-lg text-sm text-gray-600">
                                                <strong>Ubicación:</strong> {{ $product->location }}
                                            </div>
                                            <div class="bg-white p-2 rounded-lg text-sm text-gray-600">
                                                <strong>Publicado:</strong> {{ $product->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <div class="my-3">
                                            <p class="mb-2"><strong class="text-gray-700">Intereses:</strong></p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($product->tags ? explode(',', $product->tags) : [] as $tag)
                                                    <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-lg font-medium break-words">{{ trim($tag) }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="bg-white p-2 rounded-lg text-sm text-gray-600">
                                            <strong class="text-gray-700">Descripción:</strong><br>
                                            <p class="text-gray-700 text-sm mt-2">
                                                {{ Str::limit($product->description, 150) }}
                                            </p>  
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="mt-4 flex flex-wrap gap-4 justify-center items-center">
                                        <button 
                                            onclick="window.location='{{ route('products.show', $product) }}'"
                                            class="btn-outline-orange font-semibold py-2 px-6 rounded-full shadow-sm transition w-36">
                                            Ver detalles
                                        </button>
                                        
                                        @auth
                                            @if(auth()->id() !== $product->user_id)
                                                <button onclick="window.location='{{ route('offers.create', $product) }}'"
                                                        class="btn-orange font-semibold py-2 px-6 rounded-full shadow-sm transition w-36 text-center flex items-center justify-center gap-2">
                                                    Ofertar
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="btn-orange font-semibold py-2 px-6 rounded-full shadow-sm transition w-36 text-center flex items-center justify-center gap-2">
                                                Ofertar
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Vista de Cuadrícula -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $product)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full">
                                <!-- Imagen -->
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center p-4">
                                    <img src="{{ $product->first_image_url }}" alt="{{ $product->title }}" class="w-full h-full object-cover rounded-lg">
                                </div>

                                <!-- Contenido -->
                                <div class="p-4 flex-grow">
                                    <!-- Header con título y compartir -->
                                    <div class="flex justify-between items-start mb-3">
                                        <h3 class="text-lg font-bold text-gray-800 line-clamp-2 flex-grow">{{ $product->title }}</h3>
                                        <div class="relative" x-data="{ open: false }">
                                            <button 
                                                @click="open = !open"
                                                class="text-orange-500 hover:text-orange-700 ml-2">
                                                <i class="fa-solid fa-share-nodes text-sm"></i>
                                            </button>
                                            
                                            <!-- Menú desplegable compartir -->
                                            <div 
                                                x-show="open"
                                                @click.away="open = false"
                                                class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-10"
                                                style="display: none;"
                                            >
                                                <div class="p-2 space-y-1">
                                                    <a 
                                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $product)) }}"
                                                        target="_blank"
                                                        class="flex items-center px-2 py-1 text-xs text-gray-700 hover:bg-blue-50 rounded"
                                                    >
                                                        <i class="fab fa-facebook text-blue-600 w-4 mr-2"></i>
                                                        Facebook
                                                    </a>
                                                    <a 
                                                        href="https://wa.me/?text={{ urlencode($product->title . ' ' . route('products.show', $product)) }}"
                                                        target="_blank"
                                                        class="flex items-center px-2 py-1 text-xs text-gray-700 hover:bg-green-50 rounded"
                                                    >
                                                        <i class="fab fa-whatsapp text-green-500 w-4 mr-2"></i>
                                                        WhatsApp
                                                    </a>
                                                    <button 
                                                        @click="navigator.clipboard.writeText('{{ route('products.show', $product) }}'); open = false;"
                                                        class="flex items-center w-full px-2 py-1 text-xs text-gray-700 hover:bg-gray-100 rounded"
                                                    >
                                                        <i class="fas fa-link text-gray-500 w-4 mr-2"></i>
                                                        Copiar enlace
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información básica -->
                                    <div class="space-y-2 mb-3">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fa-solid fa-tag mr-2 text-orange-500"></i>
                                            <span>{{ $product->condition_name }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fa-solid fa-location-dot mr-2 text-orange-500"></i>
                                            <span>{{ $product->location }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fa-solid fa-calendar mr-2 text-orange-500"></i>
                                            <span>{{ $product->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    <!-- Intereses -->
                                    @if($product->tags)
                                        <div class="mb-3">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice(explode(',', $product->tags), 0, 3) as $tag)
                                                    <span class="bg-yellow-300 text-black text-xs px-2 py-1 rounded-lg font-medium">{{ trim($tag) }}</span>
                                                @endforeach
                                                @if(count(explode(',', $product->tags)) > 3)
                                                    <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-lg">+{{ count(explode(',', $product->tags)) - 3 }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Descripción -->
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                </div>

                                <!-- Botones -->
                                <div class="p-4 pt-0 mt-auto">
                                    <div class="flex flex-col space-y-2">
                                        <!-- Botón Ver Detalles -->
                                        <button 
                                            onclick="window.location='{{ route('products.show', $product) }}'"
                                            class="btn-outline-orange font-semibold py-2 px-4 rounded-full shadow-sm transition w-full text-center text-sm">
                                            Ver Detalles
                                        </button>

                                        <!-- Botón Ofertar -->
                                        @auth
                                            @if(auth()->id() !== $product->user_id)
                                                <button onclick="window.location='{{ route('offers.create', $product) }}'"
                                                        class="btn-orange font-semibold py-2 px-4 rounded-full shadow-sm transition w-full text-center text-sm">
                                                    Ofertar
                                                </button>
                                            @else
                                                <button disabled class="btn-gray opacity-70 cursor-not-allowed w-full text-sm py-2">
                                                    <i class="fa-solid fa-info-circle mr-2"></i>
                                                    Tu producto
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}" class="btn-orange w-full text-center text-sm py-2">
                                                Ofertar
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $products->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>
</div>