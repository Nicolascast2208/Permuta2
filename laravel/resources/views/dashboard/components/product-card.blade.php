<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-md">
    <!-- Imagen del producto -->
    <div class="relative">
        <img 
            src="{{ asset('storage/' . ($product->images->first()->path ?? 'default-product.jpg')) }}" 
            alt="{{ $product->title }}"
            class="w-full h-48 object-contain bg-gray-50 p-4"
        >
        
        <!-- Badge de estado -->
@php
    $statusLabels = [
        'available' => 'Activo',
        'pending' => 'Pendiente',
        'expired' => 'Vencido',
        'paired' => 'Permutado'
    ];
@endphp

<div class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-semibold
    @if($product->status === 'active') bg-green-100 text-green-800
    @elseif($product->status === 'pending') bg-yellow-100 text-yellow-800
    @elseif($product->status === 'expired') bg-red-100 text-red-800
    @elseif($product->status === 'paired') bg-blue-100 text-blue-800
    @else bg-gray-100 text-gray-800 @endif">
    {{ $statusLabels[$product->status] ?? ucfirst($product->status) }}
</div>
        
        <!-- Badge de expiración -->
        <div class="absolute bottom-3 left-3 bg-white bg-opacity-90 px-3 py-1 rounded-full text-xs shadow-sm">
            <span class="font-medium">Expira:</span> {{ $product->expiration_date->format('d/m/Y') }}
        </div>
    </div>

    <div class="p-4">
        <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-2">
            {{ $product->title }}
        </h3>
        
        <!-- Información adicional -->
        <div class="flex items-center text-sm text-gray-600 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            <span>Categoría: {{ $product->category->name ?? 'General' }}</span>
        </div>
        
        <!-- Botones de acción -->
        <div class="flex flex-col gap-3">
    @if($product->status === 'paired' || $product->status === 'permutado')
        {{-- Únicamente botón de Mis Permutas --}}
        <a href="{{ route('dashboard.trades') }}"
           class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-full transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
           </svg>
           <span>Ver Mis Permutas</span>
        </a>
                {{-- Botón Necesito Ayuda --}}
        <a href="{{ route('dashboard.trades') }}"
           class="flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-full transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0M12 9v2m0 4h.01" />
           </svg>
           <span>Necesito Ayuda</span>
        </a>
    @else
        {{-- Botón Editar --}}
        <a href="{{ route('products.edit', $product) }}"
           class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-full transition">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
           </svg>
           <span>Editar</span>
        </a>

        {{-- Botón Pagar Publicación si está pendiente --}}
        @if($product->status === 'pending')
            <a href="{{ route('checkout.show', $product) }}"
               class="flex items-center justify-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-full transition">
               <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
               </svg>
               <span>Pagar Publicación</span>
            </a>
        @endif

        {{-- Botón Eliminar --}}
        <form action="{{ route('products.destroy', $product) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-full transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Eliminar</span>
            </button>
        </form>
    @endif
</div>
    </div>
</div>