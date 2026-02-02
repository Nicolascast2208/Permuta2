@extends('layouts.app')

@section('content')
<!-- Incluir Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="min-h-screen fondo-gris py-8 rounded-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="text-center mb-12">
            <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto transform transition-all duration-500 hover:scale-105">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">¡Perfecto! Tu producto está listo</h1>
            </div>
        </div>

        <!-- Sección de Matches -->
        <div class="mb-12">
            <!-- Header de Matches -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center buscador text-white px-6 py-3 rounded-full shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-semibold">Matches Encontrados</span>
                </div>
                <p class="text-gray-600 mt-3">Productos que coinciden con tus intereses y categoría</p>
            </div>

            <!-- Loading State -->
            <div id="suggestions-loading" class="flex flex-col items-center justify-center h-64 bg-white rounded-2xl shadow-md">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mb-4"></div>
                <p class="text-gray-600">Buscando los mejores matches para ti...</p>
                <p class="text-sm text-gray-500 mt-1">Analizando categorías, ubicaciones y preferencias</p>
            </div>

            <!-- Grid de Matches -->
            <div id="suggestions-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 opacity-0 transition-opacity duration-500">
                <!-- Las sugerencias se cargarán aquí -->
            </div>

            <!-- Estado Vacío -->
            <div id="suggestions-empty" class="hidden bg-white rounded-2xl shadow-xl p-8 text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Aún no hay matches perfectos</h3>
                <p class="text-gray-500 mb-6">Tu producto es nuevo en el mercado. Te notificaremos cuando aparezcan matches.</p>
                <div class="space-y-3">
                    <button id="refresh-empty" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition-colors">
                        Buscar de nuevo
                    </button>
                    <a href="{{ route('products.index') }}" class="block w-full bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2 rounded-lg font-medium transition-colors">
                        Explorar mercado
                    </a>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="bg-white rounded-2xl shadow-lg p-6 max-w-2xl mx-auto border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">¿Qué te gustaría hacer ahora?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('dashboard.my-products') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-xl p-4 text-center transition-all hover:shadow-md">
                    <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-300">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">Ver mis productos</span>
                </a>
                
                <a href="{{ route('products.createx') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-xl p-4 text-center transition-all hover:shadow-md">
                    <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-300">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">Publicar otro</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-xl p-4 text-center transition-all hover:shadow-md">
                    <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-300">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">Explorar mercado</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal para detalles del producto -->
<div id="product-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-6xl max-h-[95vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 id="modal-title" class="text-2xl font-bold text-gray-800"></h3>
                    <p id="modal-category" class="text-gray-600"></p>
                </div>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600 transition-colors p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="modal-content" class="space-y-6">
                <!-- Contenido cargado dinámicamente -->
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                <button id="propose-trade" class="flex-1 bg-blue-600 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Proponer Permuta
                </button>
                <button id="close-modal-btn" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-medium transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.match-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #d0300c;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
}

.product-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.product-card:hover {
    transform: translateY(-4px);
    border-color: #ff6900;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.match-score {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.price-match {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

/* Estilos Swiper para el modal */
.swiper-container {
    width: 100%;
    border-radius: 0.5rem;
}

.gallery-top {
    height: 400px;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #fff;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Estilos para el zoom */
.image-zoom-container {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.zoom-image {
    cursor: crosshair;
    transition: none;
    transform-origin: 0 0;
    will-change: transform;
}

.thumbs-container {
    width: 100%;
    overflow-x: auto;
}

.thumbs-container::-webkit-scrollbar {
    height: 4px;
}

.thumbs-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.thumbs-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.thumbs-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.thumb-item {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
}

.thumb-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.thumb-item.active {
    border-color: #fbbf24 !important;
}

.swiper-button-next,
.swiper-button-prev {
    background: rgba(255, 255, 255, 0.8);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background: white;
}

.swiper-button-next:after,
.swiper-button-prev:after {
    display: none;
}

.swiper-pagination-bullet {
    background: white;
    opacity: 0.6;
}

.swiper-pagination-bullet-active {
    background: white;
    opacity: 1;
}

.swiper-counter {
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<!-- Incluir Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
// Función para formatear moneda chilena
function formatChileanCurrency(amount) {
    return new Intl.NumberFormat('es-CL', {
        style: 'currency',
        currency: 'CLP',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

document.addEventListener('DOMContentLoaded', function() {
    const suggestionsContainer = document.getElementById('suggestions-container');
    const suggestionsLoading = document.getElementById('suggestions-loading');
    const suggestionsEmpty = document.getElementById('suggestions-empty');
    const refreshEmptyButton = document.getElementById('refresh-empty');
    const productModal = document.getElementById('product-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content');
    const proposeTradeButton = document.getElementById('propose-trade');
    const closeModalButtons = document.querySelectorAll('#close-modal, #close-modal-btn');
    const defaultImageUrl = '{{ asset("images/default-product.png") }}';
    
    let currentProductId = null;
    let swiperInstance = null;
    
    // Cargar sugerencias iniciales
    loadSuggestions();
    
    // Event listeners
    refreshEmptyButton.addEventListener('click', loadSuggestions);
    
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            productModal.classList.add('hidden');
            // Destruir Swiper al cerrar el modal
            if (swiperInstance) {
                swiperInstance.destroy(true, true);
                swiperInstance = null;
            }
        });
    });
    
    // Función para cargar sugerencias
    function loadSuggestions() {
        suggestionsLoading.classList.remove('hidden');
        suggestionsContainer.classList.add('opacity-0');
        suggestionsEmpty.classList.add('hidden');
        
        fetch(`/api/products/{{ $product->id }}/suggestions`)
            .then(response => response.json())
            .then(suggestions => {
                suggestionsLoading.classList.add('hidden');
                
                if (suggestions.length > 0) {
                    renderSuggestions(suggestions);
                    suggestionsContainer.classList.remove('opacity-0');
                } else {
                    suggestionsEmpty.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading suggestions:', error);
                suggestionsLoading.classList.add('hidden');
                suggestionsEmpty.classList.remove('hidden');
            });
    }
    
    // Función para renderizar sugerencias
function renderSuggestions(suggestions) {
    suggestionsContainer.innerHTML = '';
    
    suggestions.forEach((product, index) => {
        // Asegúrate de que match_score esté disponible
        const matchScore = product.match_score || 0; // Valor por defecto
        const priceDifference = calculatePriceDifference(product);
        const formattedPrice = formatChileanCurrency(product.price_reference);
        
        const productCard = document.createElement('div');
        productCard.className = 'product-card bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full';
        productCard.innerHTML = `
            <div class="relative">
                <img class="h-48 w-full object-cover" src="${product.first_image_url}" alt="${product.title}" onerror="this.src='${defaultImageUrl}'">
                <div class="match-badge">
                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                    </svg>
                    ${matchScore}% Match
                </div>
                    <div class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-sm">
                        ${formattedPrice}
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="font-semibold text-gray-800 text-lg leading-tight">${product.title}</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full ml-2 flex-shrink-0">
                            ${product.category.name}
                        </span>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">${product.description}</p>
                    
                    <!-- Tags/Intereses del producto -->
                    ${product.tags ? `
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Intereses:</p>
                        <div class="flex flex-wrap gap-1">
                            ${product.tags.split(',').map(tag => `
                                <span class="bg-yellow-300 text-black text-xs-2  px-2 py-1 rounded-lg mr-1 font-medium">${tag.trim()}</span>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            ${product.condition_name}
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            ${product.location}
                        </div>
                    </div>
                    
                    <!-- Barras de progreso para match -->
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Coincidencia general:</span>
                            <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                <div class="match-score h-1.5 rounded-full" style="width: ${matchScore}%"></div>
                            </div>
                        </div>
                        ${priceDifference ? `
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Similitud de precio:</span>
                            <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                <div class="price-match h-1.5 rounded-full" style="width: ${Math.max(0, 100 - priceDifference.percentage)}%"></div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                    
                    <button onclick="openProductModal(${product.id})" class="w-full bg-blue-600 hover:from-orange-600 hover:to-orange-700 text-white py-3 px-4 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg mt-auto">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Ver Match Perfecto
                    </button>
                </div>
            `;
            suggestionsContainer.appendChild(productCard);
        });
    }
    
    // Calcular diferencia de precio
    function calculatePriceDifference(suggestionProduct) {
        const userPrice = {{ $product->price_reference }};
        const suggestionPrice = suggestionProduct.price_reference;
        const difference = Math.abs(userPrice - suggestionPrice);
        const percentage = (difference / Math.max(userPrice, suggestionPrice)) * 100;
        
        return {
            difference: difference,
            percentage: percentage,
            type: suggestionPrice > userPrice ? 'higher' : 'lower'
        };
    }
    
    // Función para abrir modal de producto
    window.openProductModal = function(productId) {
        currentProductId = productId;
        
        fetch(`/api/products/${productId}`)
            .then(response => response.json())
            .then(product => {
                // Destruir Swiper anterior si existe
                if (swiperInstance) {
                    swiperInstance.destroy(true, true);
                    swiperInstance = null;
                }
                
                const priceDifference = calculatePriceDifference(product);
                const formattedPrice = formatChileanCurrency(product.price_reference);
                
                modalTitle.textContent = product.title;
                
                // Construir HTML para Swiper gallery

let slidesHTML = '';
let thumbsHTML = '';

// Usar las imágenes del producto (ahora vienen formateadas desde la API)
const images = product.images || [];
const totalImages = images.length;

// Si no hay imágenes, usar la primera imagen
if (totalImages === 0) {
    const imageUrl = product.first_image_url;
    slidesHTML += `
        <div class="swiper-slide relative">
            <div class="image-zoom-container relative overflow-hidden" style="height: 400px;">
                <img 
                    src="${imageUrl}" 
                    alt="${product.title}" 
                    class="zoom-image w-full h-full object-cover"
                    data-zoom-image="${imageUrl}"
                    onerror="this.src='${defaultImageUrl}'"
                >
            </div>
        </div>
    `;
} else {
    images.forEach((image, index) => {
        const imageUrl = image.url || image;
        slidesHTML += `
            <div class="swiper-slide relative">
                <div class="image-zoom-container relative overflow-hidden" style="height: 400px;">
                    <img 
                        src="${imageUrl}" 
                        alt="${product.title}" 
                        class="zoom-image w-full h-full object-cover"
                        data-zoom-image="${imageUrl}"
                        onerror="this.src='${defaultImageUrl}'"
                    >
                </div>
            </div>
        `;
        
        thumbsHTML += `
            <div class="thumb-item cursor-pointer border-2 ${index === 0 ? 'border-yellow-400' : 'border-transparent'} hover:border-yellow-400 rounded-lg transition-all duration-200 flex-shrink-0" data-index="${index}">
                <img 
                    src="${imageUrl}" 
                    alt="${product.title}"
                    class="w-16 h-16 object-cover rounded-lg"
                    onerror="this.src='${defaultImageUrl}'"
                >
            </div>
        `;
    });
}
                
                modalContent.innerHTML = `
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <!-- Galería de imágenes con Swiper -->
                            <div class="swiper-container gallery-top relative rounded-lg overflow-hidden">
                                <div class="swiper-wrapper">
                                    ${slidesHTML}
                                </div>
                                
                                <!-- Botones de navegación -->
                                <div class="swiper-button-next bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <div class="swiper-button-prev bg-white/80 hover:bg-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </div>
                                
                                <!-- Contador de imágenes -->
                                ${totalImages > 1 ? `
                                <div class="absolute top-4 right-4 bg-black/50 text-white px-2 py-1 rounded-full text-sm z-10">
                                    <span class="swiper-counter">1</span>/<span class="total-images">${totalImages}</span>
                                </div>
                                ` : ''}
                            </div>

                            <!-- Miniaturas -->
                            ${totalImages > 1 ? `
                            <div class="thumbs-container mt-4">
                                <div class="flex space-x-2 justify-center overflow-x-auto py-2">
                                    ${thumbsHTML}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Detalles del Match</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Precio:</span>
                                        <span class="font-medium">${formattedPrice}</span>
                                    </div>
                                    ${priceDifference ? `
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Diferencia de precio:</span>
                                        <span class="font-medium ${priceDifference.percentage <= 20 ? 'text-green-600' : priceDifference.percentage <= 50 ? 'text-yellow-600' : 'text-red-600'}">
                                            ${priceDifference.type === 'higher' ? '+' : '-'}${formatChileanCurrency(priceDifference.difference)} (${priceDifference.percentage.toFixed(0)}%)
                                        </span>
                                    </div>
                                    ` : ''}
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Categoría:</span>
                                        <span class="font-medium">${product.category.name}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Condición:</span>
                                        <span class="font-medium">${product.condition_name}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Ubicación:</span>
                                        <span class="font-medium">${product.location}</span>
                                    </div>
                                </div>
                            </div>
                            
                            ${product.tags ? `
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Intereses del producto</h4>
                                <div class="flex flex-wrap gap-2">
                                    ${product.tags.split(',').map(tag => `
                                        <span class="bg-yellow-300 text-black text-xs-2 px-2 py-1 rounded-lg mr-1 font-medium">${tag.trim()}</span>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                            
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-2">Descripción</h4>
                                <p class="text-gray-600 leading-relaxed">${product.description}</p>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Publicado por</h4>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 font-semibold text-sm">${product.user.name.charAt(0)}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">${product.user.name}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Inicializar Swiper si hay imágenes
                if (totalImages > 0) {
                    setTimeout(() => {
                        swiperInstance = new Swiper('.gallery-top', {
                            spaceBetween: 10,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            loop: false,
                            speed: 300,
                        });

                        // Actualizar contador de imágenes
                        const counterElement = modalContent.querySelector('.swiper-counter');
                        if (counterElement && totalImages > 1) {
                            swiperInstance.on('slideChange', function () {
                                counterElement.textContent = swiperInstance.activeIndex + 1;
                                updateActiveThumb(swiperInstance.activeIndex);
                            });
                        }

                        // Funcionalidad para miniaturas
                        function updateActiveThumb(activeIndex) {
                            modalContent.querySelectorAll('.thumb-item').forEach((thumb, index) => {
                                if (index === activeIndex) {
                                    thumb.classList.add('border-yellow-400');
                                    thumb.classList.remove('border-transparent');
                                } else {
                                    thumb.classList.remove('border-yellow-400');
                                    thumb.classList.add('border-transparent');
                                }
                            });
                        }

                        // Event listeners para miniaturas
                        modalContent.querySelectorAll('.thumb-item').forEach((thumb, index) => {
                            thumb.addEventListener('click', function() {
                                swiperInstance.slideTo(index);
                                updateActiveThumb(index);
                            });
                        });

                        // Inicializar zoom
                        initZoom();
                    }, 100);
                }
                
                productModal.classList.remove('hidden');
            });
    };
    
    // Inicializar zoom
    function initZoom() {
        const zoomContainers = document.querySelectorAll('.image-zoom-container');
        
        zoomContainers.forEach(container => {
            const image = container.querySelector('.zoom-image');
            let isZoomed = false;
            const zoomLevel = 2;
            
            let rafId = null;

            function handleMouseMove(e) {
                if (!isZoomed) return;
                
                if (rafId) {
                    cancelAnimationFrame(rafId);
                }
                
                rafId = requestAnimationFrame(() => {
                    const rect = container.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    const percentX = (x / rect.width) * 100;
                    const percentY = (y / rect.height) * 100;

                    const moveX = (percentX - 50) * (zoomLevel - 1);
                    const moveY = (percentY - 50) * (zoomLevel - 1);

                    image.style.transform = `scale(${zoomLevel}) translate(${-moveX}%, ${-moveY}%)`;
                });
            }

            function handleMouseEnter() {
                isZoomed = true;
                image.style.transform = `scale(${zoomLevel})`;
                image.style.cursor = 'zoom-out';
            }

            function handleMouseLeave() {
                isZoomed = false;
                if (rafId) {
                    cancelAnimationFrame(rafId);
                    rafId = null;
                }
                image.style.transform = 'scale(1)';
                image.style.cursor = 'crosshair';
            }

            const passiveOptions = { passive: true };
            
            container.addEventListener('mouseenter', handleMouseEnter, passiveOptions);
            container.addEventListener('mouseleave', handleMouseLeave, passiveOptions);
            container.addEventListener('mousemove', handleMouseMove, passiveOptions);

            container.addEventListener('click', function(e) {
                if (!isZoomed) {
                    handleMouseEnter();
                    handleMouseMove(e);
                } else {
                    handleMouseLeave();
                }
            }, passiveOptions);
        });
    }
    
    // Proponer permuta
    proposeTradeButton.addEventListener('click', () => {
        if (currentProductId) {
            window.location.href = `/ofertar/${currentProductId}`;
        }
    });
});
</script>
@endpush