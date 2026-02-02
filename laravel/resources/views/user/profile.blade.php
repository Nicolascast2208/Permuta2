@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Encabezado del perfil mejorado -->
    <div class="fondo-gris rounded-xl shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center">
            <!-- Foto de perfil con borde y sombra -->
            <div class="relative mb-6 md:mb-0 md:mr-8">
                <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-white shadow-lg">
                    <img 
                        src="{{ $user->profile_photo_url }}" 
                        alt="{{ $user->name }}" 
                        class="w-full h-full object-cover"
                    >
                </div>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-medium">
                    {{ $user->rating > 0 ? number_format($user->rating, 1) : 'Nuevo' }}
                </div>
            </div>
            
            <!-- Información del usuario mejorada -->
            <div class="text-center md:text-left flex-1">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $user->name }}</h1>
                    
                    <!-- Botones de acción -->
                    <div class="mt-4 md:mt-0 flex space-x-3 justify-center">
                      <!--  <button class="px-4 py-2 bg-blue-600 text-white rounded-full text-sm font-medium hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-envelope mr-2"></i> Contactar
                        </button>-->
                     <!--   <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition flex items-center">
                            <i class="fas fa-share-alt mr-2"></i> Compartir
                        </button>-->
                    </div>
                </div>
                
                <!-- Rating mejorado -->
                <div class="flex items-center justify-center md:justify-start mt-3">
                    <div class="flex mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $user->rating)
                                <span class="text-yellow-400 text-xl"><i class="fas fa-star"></i></span>
                            @else
                                <span class="text-gray-300 text-xl"><i class="fas fa-star"></i></span>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-600 text-sm">{{ $reviews->count() }} reseñas</span>
                </div>
                
                <!-- Descripción (si existiera) -->
                @if($user->description)
                    <p class="mt-4 text-gray-600 max-w-2xl">
                        <i class="fas fa-quote-left text-gray-400 mr-2"></i>
                        {{ $user->description }}
                    </p>
                @endif
                
                <!-- Estadísticas mejoradas con iconos -->
     <div class="flex flex-wrap justify-center md:justify-start items-stretch mt-6 gap-8">
  <!-- Tarjeta -->
  <div class="flex flex-col items-center text-center w-32 min-h-[140px]">
      <a href="#products" class="block">
          <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-2 hover:bg-blue-100 transition">
              <i class="fas fa-box text-blue-600 text-xl"></i>
          </div>
          <p class="text-lg font-bold text-gray-800">{{ $user->products()->count() }}</p>
          <p class="text-gray-600 text-sm">Publicaciones totales</p>
      </a>
  </div>

  <div class="flex flex-col items-center text-center w-32 min-h-[140px]">
      <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-2 hover:bg-blue-100 transition">
          <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
      </div>
      <p class="text-lg font-bold text-gray-800">{{ $completedSwaps }}</p>
      <p class="text-gray-600 text-sm">Permutas realizadas</p>
  </div>

  <div class="flex flex-col items-center text-center w-32 min-h-[140px]">
      <a href="#reviews" class="block">
          <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-2 hover:bg-blue-100 transition">
              <i class="fas fa-star text-blue-600 text-xl"></i>
          </div>
          <p class="text-lg font-bold text-gray-800">{{ $user->reviews()->count() }}</p>
          <p class="text-gray-600 text-sm">Reseñas</p>
      </a>
  </div>

  <div class="flex flex-col items-center text-center w-32 min-h-[140px]">
      <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-2">
          <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
      </div>
      <p class="text-lg font-bold text-gray-800">{{ $user->created_at->format('m/Y') }}</p>
      <p class="text-gray-600 text-sm">Miembro desde</p>
  </div>
</div>


            </div>
        </div>
    </div>

    <!-- Productos publicados mejorados -->
    <section id="products" class="mb-8 fondo-gris p-5 rounded-xl">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-boxes mr-3 text-blue-600"></i> Productos activos
            </h2>
            <span class="text-gray-600 text-sm">{{ $products->count() }} productos</span>
        </div>
        
        @if($products->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-box-open text-gray-400 text-3xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Sin productos aún</h3>
                <p class="mt-1 text-gray-500">Este usuario aún no ha publicado productos</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-md transition transform hover:-translate-y-1">
                        <a href="{{ route('products.show', $product) }}" class="block">
                            @if($product->images->isNotEmpty())
                                <div class="relative h-48">
                                    <img 
                                        src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                        alt="{{ $product->title }}" 
                                        class="w-full h-full object-cover"
                                    >
                                    @if($product->created_at->diffInDays() < 7)
                                        <span class="absolute top-3 right-3 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">Nuevo</span>
                                    @endif
                                </div>
                            @else
                                <div class="bg-gray-100 border-2 border-dashed rounded-t-xl w-full h-48 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-camera text-3xl"></i>
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('products.show', $product) }}" class="font-semibold text-gray-800 hover:text-blue-600 line-clamp-1">
                                    {{ $product->title }}
                                </a>
                                <span class="text-sm text-gray-500">{{ $product->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-gray-600 mt-2 text-sm line-clamp-2">{{ $product->description }}</p>
                 
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación para productos si está paginada -->
            @if(method_exists($products, 'links'))
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </section>

    <!-- Reseñas recibidas mejoradas -->
    <section id="reviews" class="mb-8 fondo-gris p-5 rounded-xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-star mr-3 text-yellow-500"></i> Reseñas recibidas
            </h2>
            <span class="text-gray-600 text-sm">{{ $reviews->count() }} reseñas</span>
        </div>
        
        @if($reviews->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                <div class="bg-yellow-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-star text-yellow-400 text-3xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Sin reseñas aún</h3>
                <p class="mt-1 text-gray-500">Este usuario aún no tiene reseñas</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($reviews as $review)
                    <div class="bg-white rounded-xl shadow p-6 hover:shadow-md transition">
                        <div class="flex justify-between">
         <div class="flex items-center">
    <!-- Foto del autor con enlace -->
    <a href="{{ route('user.profile', $review->author->id) }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow mr-4 hover:opacity-80 transition">
        <img 
            src="{{ $review->author->profile_photo_url }}" 
            alt="{{ $review->author->alias }}" 
            class="w-full h-full object-cover"
        >
    </a>

    <!-- Nombre del autor con enlace -->
    <div>
        <a href="{{ route('user.profile', $review->author->id) }}" class="font-semibold text-gray-800 hover:text-blue-600 transition">
            {{ $review->author->name }}
        </a>

        <div class="flex items-center mt-1">
            <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <span class="text-yellow-400 text-sm"><i class="fas fa-star"></i></span>
                    @else
                        <span class="text-gray-300 text-sm"><i class="fas fa-star"></i></span>
                    @endif
                @endfor
            </div>
            <span class="ml-2 text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
                         <!--   <div class="flex">
                                <button class="text-gray-400 hover:text-blue-600 ml-3">
                                    <i class="fas fa-flag"></i>
                                </button>
                            </div>-->
                        </div>
                        
                        <!-- Comentario de la reseña -->
                        @if($review->comment)
                            <div class="mt-4 pl-16">
                                <p class="text-gray-700 relative before:content-[''] before:absolute before:-left-4 before:top-0 before:h-full before:w-1 before:bg-blue-100 before:rounded">
                                    {{ $review->comment }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Producto relacionado -->
                        @if($review->product)
                            <div class="mt-4 pt-4 border-t border-gray-100 pl-16">
                                <p class="text-sm text-gray-600 mb-2">Sobre el producto:</p>
                                <a href="{{ route('products.show', $review->product) }}" class="flex items-center group">
                                    @if($review->product->images->isNotEmpty())
                                        <img 
                                            src="{{ asset('storage/' . $review->product->images->first()->path) }}" 
                                            alt="{{ $review->product->title }}" 
                                            class="w-12 h-12 object-cover rounded-lg mr-3"
                                        >
                                    @else
                                        <div class="bg-gray-200 border-2 border-dashed w-12 h-12 rounded-lg mr-3 flex items-center justify-center">
                                            <i class="fas fa-box text-gray-400"></i>
                                        </div>
                                    @endif
                                    <span class="text-blue-600 group-hover:text-blue-800 text-sm font-medium">
                                        {{ $review->product->title }}
                                    </span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Paginación para reseñas si está paginada -->
            @if(method_exists($reviews, 'links'))
                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
    </section>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function() {
  // Scroll suave si la página se abre con hash
  if (window.location.hash) {
    const el = document.querySelector(window.location.hash);
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' });
    }
  }

  // Scroll suave al hacer clic en enlaces internos
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
        history.pushState(null, null, this.getAttribute('href')); // actualiza la URL sin recargar
      }
    });
  });
});
</script>