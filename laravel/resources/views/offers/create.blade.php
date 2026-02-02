@extends('layouts.app')

@section('title', 'Realizar Oferta - Permuta2')
@section('content')
<div class="container py-8" x-data="offerApp()">
    <!-- Encabezado principal -->
    <header class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Realizar Oferta de Permuta</h1>
        <p class="text-gray-600 mt-2">Intercambia productos de forma inteligente y segura con otros usuarios</p>
    </header>

    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Barra de progreso dinámica - MODIFICADA PARA CAMBIAR DE COLOR -->
        <div class="bg-gray-200 h-2 w-full">
            <div class="h-2 transition-all duration-500 ease-out" 
                 :class="progressBarColor"
                 :style="`width: ${Math.min(100, (totalOffer / productValue) * 100)}%`"></div>
        </div>

        <form action="{{ route('offers.store') }}" method="POST" class="flex flex-col lg:flex-row" id="offerForm">
            @csrf
            <input type="hidden" name="product_requested_id" value="{{ $product->id }}">
            
            <!-- Columna principal (formulario) -->
            <div class="flex-1 p-6">
                <!-- Producto deseado -->
                <section class="mb-8" aria-labelledby="desired-product-heading">
                    <div class="flex items-center mb-4">
                        <div class="bg-blue-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h2 id="desired-product-heading" class="text-xl font-semibold text-gray-800">Producto que deseas</h2>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg border p-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="w-full md:w-1/4 flex justify-center">
                                <img src="{{ $product->first_image_url }}" alt="{{ $product->title }}" 
                                     class="w-full h-40 object-contain rounded-lg bg-white p-2 border">
                            </div>
                            <div class="w-full md:w-3/4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->title }}</h3>
                                <div class="flex flex-wrap gap-2 my-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ $product->condition_name }}
                                    </span>
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ $product->location }}
                                    </span>
                                </div>
                                <p class="text-gray-700 text-sm mb-3">{{ Str::limit($product->description, 150) }}</p>
                                <div class="mt-3">
                                    <span class="text-lg font-bold text-blue-600">
                                        Valor referencial: ${{ number_format($product->price_reference, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Tus productos para ofrecer -->
                <section class="mb-8" aria-labelledby="your-products-heading">
                    <div class="flex items-center mb-4">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h2 id="your-products-heading" class="text-xl font-semibold text-gray-800">Tus productos para ofrecer</h2>
                       
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-4">Selecciona los productos que deseas intercambiar. El sistema te indicará si tu oferta está dentro del rango aceptable.</p>
                    
                    @if($myProducts->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <p class="text-yellow-800">No tienes productos disponibles para ofertar.</p>
                        <a href="{{ route('products.createx') }}" class="inline-flex items-center mt-2 text-blue-600 hover:underline font-medium">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear un nuevo producto
                        </a>
                    </div>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($myProducts as $myProduct)
                        <div class="border rounded-lg overflow-hidden transition-all duration-200 hover:shadow-md relative"
                             :class="{ 
                                'ring-2 ring-blue-500 ring-opacity-50 border-blue-300': isProductSelected({{ $myProduct->id }})
                             }">
                            <!-- Badge de selección -->
                            <div x-show="isProductSelected({{ $myProduct->id }})" class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center z-10">
                                <span x-text="getProductIndex({{ $myProduct->id }})"></span>
                            </div>
                            
                            <div class="flex items-start p-3">
                                <input type="checkbox" 
                                       value="{{ $myProduct->id }}" 
                                       x-model="selectedProducts"
                                       class="mt-1 mr-3 h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                       id="product-{{ $myProduct->id }}"
                                       name="products_offered[]">
                                <label for="product-{{ $myProduct->id }}" class="flex-1 cursor-pointer flex items-start">
                                    <div class="flex-shrink-0 w-16 h-16 bg-white border rounded overflow-hidden">
                                        <img src="{{ $myProduct->first_image_url }}" 
                                             alt="{{ $myProduct->title }}"
                                             class="w-full h-full object-contain">
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="font-medium text-gray-900 text-sm leading-tight">{{ $myProduct->title }}</h4>
                                        <p class="text-xs text-gray-600 mt-1">{{ $myProduct->condition_name }}</p>
                                        <p class="text-sm font-semibold text-green-600 mt-1">
                                            ${{ number_format($myProduct->price_reference, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </section>

                <!-- Indicador de Equilibrio de Oferta -->
                <section class="mb-8" aria-labelledby="balance-indicator-heading" x-show="selectedProducts.length > 0">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h2 id="balance-indicator-heading" class="text-xl font-semibold text-gray-800">Estado de tu Oferta</h2>
                    </div>
                    
                    <div class="bg-white rounded-lg border p-6">
                        <!-- Barra de progreso visual - MODIFICADA PARA CAMBIAR DE COLOR -->
                        <div class="mb-6">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Tu oferta</span>
                                <span class="text-sm font-medium text-gray-700">Producto deseado</span>
                            </div>
                            
                            <div class="relative h-4 bg-gray-200 rounded-full overflow-hidden">
                                <!-- Rango aceptable (25%) -->
                                <div class="absolute inset-0 bg-green-100" 
                                     :style="`left: ${acceptableRange.min}%; right: ${100 - acceptableRange.max}%`"></div>
                                
                                <!-- Indicador de tu oferta - MODIFICADO PARA CAMBIAR DE COLOR -->
                                <div class="absolute h-4 transition-all duration-500 ease-out"
                                     :class="progressBarColor"
                                     :style="`width: ${Math.min(100, (totalOffer / productValue) * 100)}%`"></div>
                                
                                <!-- Línea del valor del producto -->
                                <div class="absolute top-0 bottom-0 w-0.5 bg-blue-600 ml-1"
                                     style="left: 100%"></div>
                            </div>
                            
                            <!-- Marcadores -->
                            <div class="flex justify-between mt-1 text-xs text-gray-600">
                                <span x-text="formatCurrency(0)"></span>
                                <span x-text="`Rango ±25%`" class="text-green-600 font-medium"></span>
                                <span x-text="formatCurrency(productValue )"></span>
                            </div>
                        </div>
                        
                        <!-- Estado actual -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="block text-sm text-gray-600 mb-1">Tu oferta</span>
                                <span class="block text-xl font-bold text-green-600" x-text="formatCurrency(totalOffer)"></span>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="block text-sm text-gray-600 mb-1">Diferencia</span>
                                <span class="block text-lg font-bold" 
                                      :class="offerStatus.color"
                                      x-text="offerStatus.text"></span>
                            </div>
                            
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="block text-sm text-gray-600 mb-1">Producto deseado</span>
                                <span class="block text-xl font-bold text-blue-600" x-text="formatCurrency(productValue)"></span>
                            </div>
                        </div>
                        
                        <!-- Mensaje de estado -->
                        <div class="mt-4 p-3 rounded-lg text-center" :class="offerStatus.bgColor">
                            <p class="font-medium" :class="offerStatus.textColor" x-text="offerStatus.message"></p>
                        </div>
                    </div>
                </section>

                <!-- Complemento con dinero -->
                <section class="mb-8" aria-labelledby="money-heading" x-show="selectedProducts.length > 0">
                    <div class="flex items-center mb-4">
                        <div class="bg-yellow-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 id="money-heading" class="text-xl font-semibold text-gray-800">Complementar con dinero</h2>
                    </div>
                    
                    <div class="bg-white rounded-lg border p-5">
                        <label for="complementary_amount" class="block font-medium text-gray-700 mb-2">
                            ¿Deseas agregar dinero para equilibrar la oferta?
                        </label>
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">$</span>
                                <input type="number" 
                                       id="complementary_amount" 
                                       name="complementary_amount" 
                                       x-model="complementaryAmount"
                                       @input="updateDifference()"
                                       min="0"
                                       class="pl-8 pr-4 py-2 border rounded-lg w-full md:w-48 focus:ring-blue-500 focus:border-blue-500 transition"
                                       placeholder="0">
                            </div>
                            
                            <!-- Sugerencias de montos -->
                            <div class="flex flex-wrap gap-2">
                                <template x-for="suggestion in moneySuggestions" :key="suggestion">
                                    <button type="button" 
                                            @click="complementaryAmount = suggestion"
                                            class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition"
                                            :class="complementaryAmount == suggestion ? 'bg-blue-100 text-blue-700 border border-blue-300' : ''">
                                        $<span x-text="new Intl.NumberFormat('es-CL').format(suggestion)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2" x-show="complementaryAmount > 0">
                            Agregando <span x-text="formatCurrency(complementaryAmount)"></span> a tu oferta.
                        </p>
                        <p class="text-xs text-blue-600 mt-2" x-show="complementaryAmount > 0">
                            Tu oferta total será: <span x-text="formatCurrency(totalOffer)"></span>
                        </p>
                    </div>
                </section>

                <!-- Resumen de oferta -->
                <section class="mb-8" aria-labelledby="offer-summary-heading">
                    <div class="flex items-center mb-4">
                        <div class="bg-purple-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h2 id="offer-summary-heading" class="text-xl font-semibold text-gray-800">Resumen de tu oferta</h2>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg border p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Valor de productos seleccionados -->
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <h3 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Valor de tus productos
                                </h3>
                                <p class="text-2xl font-bold text-green-600" x-text="formatCurrency(totalValue)"></p>
                                <p class="text-xs text-gray-500 mt-1" x-show="selectedProducts.length > 0">
                                    <span x-text="selectedProducts.length"></span> producto(s) seleccionado(s)
                                </p>
                            </div>
                            
                            <!-- Complemento con dinero -->
                            <div class="bg-white p-4 rounded-lg shadow-sm" x-show="complementaryAmount > 0">
                                <h3 class="font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Complemento en dinero
                                </h3>
                                <p class="text-2xl font-bold text-yellow-600" x-text="formatCurrency(complementaryAmount)"></p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Agregado a tu oferta
                                </p>
                            </div>
                        </div>
                        
                        <!-- Balance -->
                        <div class="border-t pt-4 mt-4" x-show="hasSelection">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Valor total de tu oferta:</span>
                                    <span class="text-xl font-bold" 
                                          :class="totalOffer >= productValue ? 'text-green-600' : 'text-orange-600'"
                                          x-text="formatCurrency(totalOffer)"></span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Valor del producto deseado:</span>
                                    <span class="text-lg font-medium text-blue-600" x-text="formatCurrency(productValue)"></span>
                                </div>
                                
                                <div class="flex justify-between items-center pt-3 border-t font-medium">
                                    <span class="text-gray-700">Diferencia:</span>
                                    <span class="text-lg" 
                                          :class="totalOffer >= productValue ? 'text-green-600' : 'text-orange-600'"
                                          x-text="formatCurrency(Math.abs(totalOffer - productValue)) + ' ' + 
                                          (totalOffer >= productValue ? 'a tu favor' : 'en contra')"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Mensaje inicial -->
                        <div x-show="!hasSelection" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-blue-700 text-sm">
                                    Selecciona al menos un producto para ver el resumen de tu oferta.
                                </span>
                            </div>
                        </div>
                        
                        <!-- Advertencia de diferencia de precio -->
                        <div x-show="hasSelection && showWarning" x-cloak class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="h-6 w-6 text-yellow-500 mr-3 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <h3 class="font-medium text-yellow-800">Diferencia significativa de valor</h3>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        La diferencia entre tu oferta y el valor del producto es superior al 25%. 
                                        Para continuar, por favor confirma que deseas proceder con esta oferta.
                                    </p>
                                    <div class="mt-3 flex items-center">
                                        <input type="checkbox" id="confirm_difference" name="confirm_difference" 
                                               x-model="confirmedDifference"
                                               class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="confirm_difference" class="ml-2 block text-sm text-yellow-700">
                                            Confirmo que estoy consciente de la diferencia de valor y deseo proceder
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Comentarios -->
                <section class="mb-8" aria-labelledby="comments-heading">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-2 rounded-full mr-3">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <h2 id="comments-heading" class="text-xl font-semibold text-gray-800">Mensaje para el vendedor</h2>
                    </div>
                    
                    <div class="bg-white rounded-lg border p-1">
                        <label for="comment" class="sr-only">Mensaje para el vendedor</label>
                        <textarea id="comment" name="comment" rows="4" 
                                  class="w-full px-4 py-2 border-0 rounded-lg focus:ring-0 focus:outline-none" 
                                  placeholder="Explica por qué tu oferta es interesante o añade detalles sobre el intercambio... (Opcional)"
                                  oninput="validateNoContactInfo(this, 'commentError')"></textarea>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Un mensaje amable aumenta las posibilidades de que tu oferta sea aceptada.</p>
                    <p id="commentError" class="mt-1 text-sm text-red-600 hidden">No puedes enviar información de contacto como teléfonos, emails o redes sociales en los comentarios.</p>
                </section>

                <!-- Botones de acción -->
                <div class="flex flex-col-reverse sm:flex-row gap-4 justify-end pt-6 border-t">
                    <a href="{{ route('products.show', $product) }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-800 font-medium rounded-lg hover:bg-gray-200 transition text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center shadow-md shadow-blue-100"
                            :disabled="selectedProducts.length === 0 || (hasSelection && showWarning && !confirmedDifference) || isSubmitting">
                        <svg x-show="!isSubmitting" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Enviando oferta...' : 'Enviar oferta'"></span>
                    </button>
                </div>
            </div>
            
            <!-- Columna lateral (perfil del usuario) -->
            <div class="w-full lg:w-80 xl:w-96 bg-gray-50 p-6 border-l">
                <div class="sticky top-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información del vendedor
                    </h2>
                    
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border">
                        <div class="p-6">
                            <div class="flex flex-col items-center">
                                <a href="{{ route('user.profile', $product->user->id) }}" class="block relative mb-5">
                                    <div class="w-28 h-28 rounded-full bg-gradient-to-r from-blue-100 to-purple-100 p-1 shadow-lg">
                                        <img src="{{ $product->user->profile_photo_url }}" 
                                             alt="{{ $product->user->alias }}"
                                             class="w-full h-full rounded-full object-cover border-4 border-white">
                                    </div>
                                </a>
                                <a href="{{ route('user.profile', $product->user->id) }}" class="block mb-1">
                                    <h3 class="text-xl font-bold text-gray-900 text-center">{{ $product->user->name }}</h3>
                                </a>
                                <p class="text-sm text-gray-500 mb-6">Miembro desde {{ $product->user->created_at->format('d/m/Y') }}</p>
                                
                                <!-- Rating -->
                                <div class="mb-6 w-full bg-gray-50 rounded-lg p-4 border">
                                    <div class="flex flex-col items-center">
                                        @if($product->user->rating > 0)
                                            <div class="flex text-yellow-400 mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($product->user->rating))
                                                        <!-- Estrella completa -->
                                                        <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                    @elseif($i == ceil($product->user->rating) && ($product->user->rating - floor($product->user->rating)) > 0)
                                                        <!-- Media estrella -->
                                                        <div class="relative">
                                                            <svg class="w-6 h-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                            </svg>
                                                            <div class="absolute top-0 left-0 overflow-hidden" style="width: {{ ($product->user->rating - floor($product->user->rating)) * 100 }}%">
                                                                <svg class="w-6 h-6 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- Estrella vacía -->
                                                        <svg class="w-6 h-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-lg font-semibold text-gray-700">{{ number_format($product->user->rating, 1) }}/5.0</span>
                                            <p class="text-xs text-gray-500 mt-1">Valoración de usuarios</p>
                                        @else
                                            <span class="text-sm font-medium text-gray-500">Sin valoraciones aún</span>
                                            <p class="text-xs text-gray-400 mt-1">Este usuario aún no ha recibido calificaciones</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Resumen de la acción -->
                    <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <h3 class="font-medium text-blue-800 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ¿Qué sucede después?
                        </h3>
                        <ul class="text-sm text-blue-700 space-y-2">
                            <li class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold mr-2">1</span>
                                <span>Envías tu oferta al vendedor</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold mr-2">2</span>
                                <span>El vendedor recibe una notificación</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold mr-2">3</span>
                                <span>Puede aceptar, rechazar</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-200 text-blue-800 rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold mr-2">4</span>
                                <span>¡Acuerdan el intercambio!</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Overlay de carga -->
<div x-show="isSubmitting" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl max-w-md w-full p-8 text-center">
        <div class="flex justify-center mb-4">
            <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Enviando oferta...</h3>
        <p class="text-gray-600">
            Por favor espera mientras procesamos tu oferta. Serás redirigido automáticamente.
        </p>
    </div>
</div>

<script>
function offerApp() {
    return {
        selectedProducts: [],
        complementaryAmount: 0,
        productValue: {{ $product->price_reference }},
        showWarning: false,
        confirmedDifference: false,
        differencePercentage: 0,
        isSubmitting: false,
        productPrices: {!! json_encode($myProducts->pluck('price_reference', 'id')) !!},
        
        get hasSelection() {
            return this.selectedProducts.length > 0 || this.complementaryAmount > 0;
        },
        
        get totalValue() {
            return this.selectedProducts.reduce((total, id) => {
                const price = Number(this.productPrices[id]) || 0;
                return total + price;
            }, 0);
        },
        
        get totalOffer() {
            return this.totalValue + Number(this.complementaryAmount || 0);
        },
        
        // NUEVA PROPIEDAD: Color de la barra de progreso según el estado
        get progressBarColor() {
            if (!this.hasSelection) {
                return 'bg-gray-400';
            }
            
            const percentage = (this.totalOffer / this.productValue) * 100;
            
            // Si está dentro del rango aceptable (75% - 125%)
            if (percentage >= 75 && percentage <= 125) {
                return 'bg-green-500';
            }
            // Si está un poco fuera del rango (50% - 75% o 125% - 150%)
            else if ((percentage >= 50 && percentage < 75) || (percentage > 125 && percentage <= 150)) {
                return 'bg-yellow-500';
            }
            // Si está muy fuera del rango (menos del 50% o más del 150%)
            else {
                return 'bg-red-500';
            }
        },
        
        // Rango aceptable (25%)
        get acceptableRange() {
            const min = (this.productValue * 0.75) / (this.productValue * 1.25) * 100;
            const max = 100 - min;
            return { min, max };
        },
        
        // Estado de la oferta
        get offerStatus() {
            if (!this.hasSelection) {
                return {
                    text: 'Sin oferta',
                    color: 'text-gray-600',
                    bgColor: 'bg-gray-100',
                    textColor: 'text-gray-700',
                    message: 'Selecciona productos para comenzar tu oferta'
                };
            }
            
            if (this.differencePercentage <= 10) {
                return {
                    text: 'Excelente oferta',
                    color: 'text-green-600',
                    bgColor: 'bg-green-100',
                    textColor: 'text-green-700',
                    message: '¡Tu oferta está muy cerca del valor del producto!'
                };
            } else if (this.differencePercentage <= 25) {
                return {
                    text: 'Oferta aceptable',
                    color: 'text-blue-600',
                    bgColor: 'bg-blue-100',
                    textColor: 'text-blue-700',
                    message: 'Tu oferta está dentro del rango aceptable (±25%)'
                };
            } else {
                return {
                    text: 'Fuera de rango',
                    color: 'text-red-600',
                    bgColor: 'bg-red-100',
                    textColor: 'text-red-700',
                    message: 'Tu oferta excede el 25% de diferencia. Confirma para proceder.'
                };
            }
        },
        
        // Sugerencias de montos basadas en la diferencia
        get moneySuggestions() {
            if (!this.hasSelection) return [10000, 25000, 50000, 100000];
            
            const difference = this.productValue - this.totalValue;
            if (difference <= 0) return [10000, 25000, 50000];
            
            // Calcular sugerencias basadas en la diferencia
            const suggestions = [];
            const step = Math.max(10000, Math.round(difference * 0.25 / 10000) * 10000);
            
            for (let i = 1; i <= 3; i++) {
                const amount = Math.round((step * i) / 1000) * 1000;
                if (amount <= difference * 1.5) {
                    suggestions.push(amount);
                }
            }
            
            return suggestions.length > 0 ? suggestions : [10000, 25000, 50000];
        },
        
        formatCurrency(value) {
            if (isNaN(value)) return '$0';
            return '$' + new Intl.NumberFormat('es-CL').format(Math.round(value));
        },
        
        isProductSelected(productId) {
            return this.selectedProducts.includes(productId.toString());
        },
        
        // Función corregida para obtener el índice del producto
        getProductIndex(productId) {
            const index = this.selectedProducts.indexOf(productId.toString());
            return index >= 0 ? index + 1 : 0;
        },
        
        updateDifference() {
            if (!this.hasSelection) {
                this.differencePercentage = 0;
                this.showWarning = false;
                return;
            }
            
            const difference = Math.abs(this.totalOffer - this.productValue);
            this.differencePercentage = (difference / this.productValue) * 100;
            
            // Mostrar advertencia si la diferencia es mayor al 25%
            this.showWarning = this.differencePercentage > 25;
        },
        
        init() {
            // Observar cambios en los productos seleccionados
            this.$watch('selectedProducts', () => {
                this.updateDifference();
                // Si cambian los productos, resetear la confirmación
                if (this.differencePercentage <= 25) {
                    this.confirmedDifference = false;
                }
            });
            
            this.$watch('complementaryAmount', () => {
                this.updateDifference();
                if (this.differencePercentage <= 25) {
                    this.confirmedDifference = false;
                }
            });
            
            // Inicializar la diferencia
            this.updateDifference();
        }
    };
}

// Función de validación para evitar información de contacto
function validateNoContactInfo(textarea, errorElementId) {
    const value = textarea.value;
    const errorElement = document.getElementById(errorElementId);
    
    // Expresiones regulares para detectar información de contacto
    const phoneRegex = /(\+?\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;
    const emailRegex = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
    const urlRegex = /(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i;
    
    if (phoneRegex.test(value) || emailRegex.test(value) || urlRegex.test(value)) {
        errorElement.classList.remove('hidden');
        textarea.classList.add('border-red-500');
        return false;
    } else {
        errorElement.classList.add('hidden');
        textarea.classList.remove('border-red-500');
        return true;
    }
}

// Validación antes de enviar el formulario
document.addEventListener('DOMContentLoaded', function() {
    const offerForm = document.getElementById('offerForm');
    if (offerForm) {
        offerForm.addEventListener('submit', function(e) {
            const commentTextarea = document.getElementById('comment');
            if (commentTextarea && !validateNoContactInfo(commentTextarea, 'commentError')) {
                e.preventDefault();
                // Hacer scroll al campo de comentarios
                commentTextarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                commentTextarea.focus();
                
                // Mostrar alerta
                alert('Por favor elimina la información de contacto (teléfonos, emails o redes sociales) del comentario antes de enviar la oferta.');
            }
        });
    }
});
</script>

<style>
.border-red-500 {
    border-color: #ef4444 !important;
}
</style>

@endsection