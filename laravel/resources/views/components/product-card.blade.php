<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-lg">
    <div class="relative">
        <img src="{{ $product->first_image_url }}" alt="{{ $product->title }}" class="w-full h-48 object-contain bg-gray-50 p-4">
    </div>
    
    <div class="p-4">
        <h3 class="font-semibold text-gray-800 text-lg mb-2 line-clamp-2">{{ $product->title }}</h3>
        
        <div class="flex items-center text-sm text-gray-600 mb-3">
            <span class="bg-gray-100 px-2 py-1 rounded mr-2">{{ $product->condition_name }}</span>
            <span>{{ $product->location }}</span>
        </div>
        
        <div class="mb-3">
            <p class="text-sm text-gray-700"><strong>Intereses:</strong></p>
            <div class="flex flex-wrap mt-1">
                @foreach($product->tags ? explode(',', $product->tags) : [] as $tag)
                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mr-1 mb-1">{{ trim($tag) }}</span>
                @endforeach
            </div>
        </div>
        
        <div class="flex justify-between items-center">
            <a href="{{ route('products.show', $product) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver detalles
            </a>
            <span class="text-xs text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>