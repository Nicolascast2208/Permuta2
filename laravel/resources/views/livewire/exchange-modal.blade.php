<div>
    @if ($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
            x-data="{ open: true }"  
            x-show="open"
            x-cloak
            @keydown.escape.window="open = false">

            <div class="bg-gray-200 rounded-lg shadow-lg w-full max-w-3xl" 
                 x-on:click.away="open = false; $wire.closeModal()"> 
                @if($showConfirmation)
                    <div class="p-6 text-center">
                        <div class="text-green-500 text-5xl mb-4">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">¡Oferta Enviada!</h3>
                        <p class="text-gray-700 mb-4">
                            Hemos notificado al usuario sobre tu interés. 
                            Te contactaremos cuando responda a tu oferta.
                        </p>
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Cerrar
                        </button>
                    </div>
                @else
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="text-xl font-semibold">Ofertar por: {{ $productDesired->title }}</h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                            &times;
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row items-center justify-between mb-6">
                            <!-- Producto deseado -->
                            <div class="w-full md:w-5/12 border rounded-lg p-4 bg-white">
                                <h4 class="font-bold text-lg mb-3 text-center text-blue-600">Producto que deseas</h4>
                                <div class="flex flex-col items-center">
                                    @if($productDesired->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $productDesired->images->first()->path) }}" 
                                             alt="{{ $productDesired->title }}"
                                             class="w-32 h-32 object-contain mb-3">
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-32 mb-3"></div>
                                    @endif
                                    <h5 class="font-semibold text-center">{{ $productDesired->title }}</h5>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Estado:</strong> {{ ucfirst($productDesired->condition) }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <strong>Ubicación:</strong> {{ $productDesired->location }}
                                    </p>
                                </div>
                            </div>

                            <!-- Flechas de intercambio -->
                            <div class="my-6 md:my-0 flex flex-col items-center justify-center">
                                <div class="hidden md:block">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </div>
                                <div class="md:hidden">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-500 mt-2">INTERCAMBIO</span>
                            </div>

                            <!-- Producto ofrecido -->
                            <div class="w-full md:w-5/12 border rounded-lg p-4 bg-white">
                                <h4 class="font-bold text-lg mb-3 text-center text-green-600">Producto que ofreces</h4>
                                @if($selectedProductDetail)
                                    <div class="flex flex-col items-center">
                                        @if($selectedProductDetail->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $selectedProductDetail->images->first()->path) }}" 
                                                 alt="{{ $selectedProductDetail->title }}"
                                                 class="w-32 h-32 object-contain mb-3">
                                        @else
                                            <div class="bg-gray-200 border-2 border-dashed rounded-xl w-32 h-32 mb-3"></div>
                                        @endif
                                        <h5 class="font-semibold text-center">{{ $selectedProductDetail->title }}</h5>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <strong>Estado:</strong> {{ ucfirst($selectedProductDetail->condition) }}
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-4 text-gray-500">
                                        Selecciona un producto para ofrecer
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 font-medium">Selecciona el producto que quieres ofrecer:</label>
                            <select wire:model="selectedOfferProduct"
                                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">-- Selecciona un producto --</option>
                                @foreach($myProducts as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-center space-x-4">
                            <button wire:click="closeModal" 
                                    class="text-white font-semibold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 bg-gray-300 hover:bg-gray-400">
                                Cancelar
                            </button>
                            <button wire:click.prevent="submitOffer"
                                    wire:loading.attr="disabled"
                                    class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 border-white disabled:opacity-50"
                                    >
                                <span wire:loading.remove>Enviar Oferta</span>
                                <span wire:loading><i class="fas fa-spinner fa-spin"></i> Enviando...</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>