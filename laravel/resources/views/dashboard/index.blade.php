@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto">


    <!-- Grid principal -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Perfil del usuario -->
        <div class="fondo-gris rounded-xl shadow overflow-hidden md:col-span-1">
   <div class="fondo-gris px-4 py-2 flex items-center justify-center gap-2">
    <!-- Foto de perfil -->
    <div class="relative w-30 h-30 rounded-full overflow-hidden border-4 border-white shadow-lg flex-shrink-0">
        <img src="{{ $user->profile_photo_url }}" 
             alt="{{ $user->alias }}" 
             class="w-full h-full object-cover">
    </div>

    <!-- Info de usuario -->
    <div class="ml-4 flex flex-col">
        <!-- Nombre e ID -->
        <h2 class="text-lg font-bold text-black">{{ $user->name }}</h2>
        <p class="text-sm text-gray-600">ID usuario: {{ $user->id }}</p>

        <!-- Estrellas de reputación -->
        <div class="mt-1 flex items-center">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= $user->rating)
                    <span class="text-yellow-400 text-lg">★</span>
                @else
                    <span class="text-gray-300 text-lg">★</span>
                @endif
            @endfor
            <span class="ml-2 text-sm text-black">{{ number_format($user->rating, 1) }}</span>
        </div>
          <a href="{{ route('user.profile', $user->id) }}#reviews">Ver Reseñas</a>
     </div>
    </div>



            <!-- Info de estadísticas y contacto -->
<div class="p-6">
    <!-- Estadísticas -->
<div class="grid grid-cols-3 text-center mb-6 bg-white px-3 py-3 rounded-2xl divide-x divide-gray-300">
    <div class="px-2">
        <p class="text-2xl font-bold">{{ $user->products_count }}</p>
        <p class="text-gray-600 text-sm">Productos</p>
    </div>
    <div class="px-2">
        <p class="text-2xl font-bold">{{ $user->received_offers_count }}</p>
        <p class="text-gray-600 text-sm">Ofertas Recibidas</p>
    </div>
    <div class="px-2">
        <p class="text-2xl font-bold">{{ $user->sent_offers_count }}</p>
        <p class="text-gray-600 text-sm">Ofertas Enviadas</p>
    </div>
</div>

    

<hr class="w-full mx-auto border-t-2 border-gray-300 mb-3 ">
                <!-- Contacto -->
                <div class="mb-6">
                    <h3 class="font-semibold text-lg mb-2">Información de contacto</h3>
                    <p class="text-gray-700 flex items-center bg-white px-2 py-2 rounded-xl">
                        <i class="fas fa-phone-alt mr-2"></i>
                        {{ $user->phone ?? 'Sin teléfono' }}
                    </p>
                    <p class="text-gray-700 flex items-center mt-2 bg-white px-2 py-2 rounded-xl">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $user->location ?? 'Sin ubicación' }}
                    </p>
                </div>

                <!-- Botón Editar Perfil -->
                <div class="flex justify-center">
                    <a href="{{ route('profile.edit') }}" 
                       class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-2 px-10 rounded-full shadow-md hover:shadow-lg transition border-2 border-white">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <!-- Contenido principal del dashboard -->
        <div class="md:col-span-2 space-y-6">
            <!-- Accesos rápidos -->
<div class="fondo-gris rounded-lg shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
        
        <a href="{{ route('products.createx') }}" 
           class="bg-white hover:bg-yellow-50 rounded-lg p-4 transition border border-yellow-200 flex items-center space-x-4">
            <div class="text-blue-600 flex-shrink-0">
                <i class="fas fa-plus text-3xl"></i>
            </div>
            <div class="border-l border-gray-300 pl-4">
                <h3 class="font-semibold">Publicar</h3>
                <p class="text-sm text-gray-600">Crea tus publicaciones</p>
            </div>
        </a>

        <a href="{{ route('dashboard.my-products') }}" 
           class="bg-white hover:bg-gray-50 rounded-lg p-4 transition border border-gray-200 flex items-center space-x-4">
            <div class="text-blue-600 flex-shrink-0">
                <i class="fas fa-box text-3xl"></i>
            </div>
            <div class="border-l border-gray-300 pl-4">
                <h3 class="font-semibold">Mis Productos</h3>
                <p class="text-sm text-gray-600">Administra tus publicaciones</p>
            </div>
        </a>

        <a href="{{ route('dashboard.trades') }}" 
           class="bg-white hover:bg-gray-50 rounded-lg p-4 transition border border-gray-200 flex items-center space-x-4">
            <div class="text-blue-600 flex-shrink-0">
                <i class="fas fa-exchange-alt text-3xl"></i>
            </div>
            <div class="border-l border-gray-300 pl-4">
                <h3 class="font-semibold">Mensajes</h3>
                <p class="text-sm text-gray-600">Historial de tus permutas</p>
            </div>
        </a>

        <a href="{{ route('dashboard.questions') }}" 
           class="bg-white hover:bg-gray-50 rounded-lg p-4 transition border border-gray-200 flex items-center space-x-4">
            <div class="text-blue-600 flex-shrink-0">
                <i class="fas fa-message text-3xl"></i>
            </div>
            <div class="border-l border-gray-300 pl-4">
                <h3 class="font-semibold">Preguntas</h3>
                <p class="text-sm text-gray-600">Historial de preguntas sobre tus productos</p>
            </div>
        </a>

    </div>
</div>


            <!-- Actividad reciente -->
<div class="fondo-gris rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-center mb-2">Actividad Reciente</h2>
    <hr class="border-gray-300 mb-4">

    <div class="space-y-4">
        @forelse(auth()->user()->notifications()->latest()->take(3)->get() as $notification)
            <div class="flex items-start space-x-4 border-b border-gray-200 pb-3 last:border-b-0">
                <div class="text-blue-600 flex-shrink-0">
                    @switch($notification->type)
                        @case('product_created')
                            <i class="fas fa-box-open text-xl"></i>
                            @break
                        @case('offer_received')
                            <i class="fas fa-envelope-open-text text-xl"></i>
                            @break
                        @case('trade_accepted')
                            <i class="fas fa-check-circle text-xl"></i>
                            @break
                        @default
                            <i class="fas fa-bell text-xl"></i>
                    @endswitch
                </div>
                <div>
                    <p class="text-sm text-gray-700">
                        {{ $notification->message }}
                    </p>
                    <p class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-center">No hay actividad reciente</p>
        @endforelse
    </div>
</div>

        </div>
    </div>
</div>
@endsection
