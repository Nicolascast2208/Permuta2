@extends('layouts.app')

@section('title', 'Permutadores - ' . config('app.name'))

@section('content')
<div class="container py-8">
    <!-- Encabezado de la página -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Nuestra Comunidad de Permutadores</h1>
        <p class="text-gray-600 mt-2">Conoce a los usuarios que hacen posible los intercambios en Permuta2</p>
    </div>

    <!-- Filtros y búsqueda (opcional para futuras implementaciones) -->
    <div class="mb-6 flex justify-between items-center">
        <p class="text-gray-600">
            Mostrando <span class="font-semibold">{{ $users->count() }}</span> 
            {{ $users->count() === 1 ? 'permutador' : 'permutadores' }}
        </p>
        <!-- Espacio para futuros filtros -->
        <div></div>
    </div>

    @if($users->isEmpty())
        <!-- Estado vacío -->
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-gray-400 text-3xl"></i>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay permutadores registrados</h3>
            <p class="mt-1 text-gray-500">Aún no hay usuarios en la plataforma</p>
        </div>
    @else
        <!-- Grid de usuarios -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Foto de perfil -->
                    <div class="relative">
                        <div class="h-48 bg-gradient-to-r from-blue-50 to-purple-50 flex items-center justify-center">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                <img 
                                    src="{{ $user->profile_photo_url }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-full h-full object-cover"
                                >
                            </div>
                        </div>
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium">
                            {{ $user->rating > 0 ? number_format($user->rating, 1) : 'Nuevo' }}
                        </div>
                    </div>
                    
                    <!-- Información del usuario -->
                    <div class="p-5">
                        <!-- Nombre -->
                        <h2 class="text-xl font-bold text-gray-800 text-center mb-2">{{ $user->name }}</h2>
                        
                        <!-- Rating -->
                        <div class="flex justify-center mb-4">
                            <div class="flex">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($user->rating))
                                        <span class="text-yellow-400"><i class="fas fa-star"></i></span>
                                    @elseif($i == ceil($user->rating) && ($user->rating - floor($user->rating)) > 0)
                                        <span class="text-yellow-400"><i class="fas fa-star-half-alt"></i></span>
                                    @else
                                        <span class="text-gray-300"><i class="fas fa-star"></i></span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-gray-600 text-sm ml-2">
                                ({{ $user->reviews()->count() }})
                            </span>
                        </div>
                        
                        <!-- Estadísticas -->
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="text-center bg-gray-50 rounded-lg p-2">
                                <div class="text-blue-600 mb-1">
                                    <i class="fas fa-box"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $user->products()->count() }}</p>
                                <p class="text-xs text-gray-600">Productos</p>
                            </div>
                            <div class="text-center bg-gray-50 rounded-lg p-2">
                                <div class="text-blue-600 mb-1">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <p class="text-lg font-bold text-gray-800">{{ $user->created_at->format('m/Y') }}</p>
                                <p class="text-xs text-gray-600">Miembro</p>
                            </div>
                        </div>
                        
                        <!-- Botón de acción -->
                        <a 
                            href="{{ route('user.profile', $user) }}" 
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-medium py-2 px-4 rounded-lg transition"
                        >
                            Ver perfil
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        @if($users->hasPages())
            <div class="mt-8">
                {{ $users->links() }}
            </div>
        @endif
    @endif
</div>

<style>
.fas.fa-star-half-alt {
    transform: scale(0.9);
}
</style>
@endsection