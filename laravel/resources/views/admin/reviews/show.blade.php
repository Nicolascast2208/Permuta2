{{-- resources/views/admin/reviews/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Reseña')
@section('subtitle', 'Información completa de la reseña')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.reviews.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Reseñas</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="confirmAction('¿Estás seguro de eliminar esta reseña?', () => this.form.submit())"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-trash mr-2"></i> Eliminar
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información de la reseña -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reseña</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <!-- Calificación -->
                <div class="flex items-center mb-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400 text-2xl' : 'text-gray-300 text-2xl' }}"></i>
                        @endfor
                    </div>
                    <span class="ml-2 text-lg font-medium text-gray-900">{{ $review->rating }}/5</span>
                </div>

                <!-- Comentario -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Comentario</h4>
                    <p class="text-gray-600 bg-gray-50 p-4 rounded-md">{{ $review->comment }}</p>
                </div>

                <!-- Fechas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Fechas</h4>
                        <dl class="space-y-1">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Creada</dt>
                                <dd class="text-sm text-gray-900">{{ $review->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Actualizada</dt>
                                <dd class="text-sm text-gray-900">{{ $review->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de usuarios -->
    <div class="lg:col-span-1">
        <!-- Autor -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Autor</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full mr-3" src="{{ $review->author->profile_photo_url }}" alt="{{ $review->author->name }}">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $review->author->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $review->author->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $review->author) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user mr-2"></i> Ver perfil
                </a>
            </div>
        </div>

        <!-- Usuario reseñado -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Usuario Reseñado</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full mr-3" src="{{ $review->reviewedUser->profile_photo_url }}" alt="{{ $review->reviewedUser->name }}">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $review->reviewedUser->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $review->reviewedUser->email }}</p>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->reviewedUser->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                            @endfor
                            <span class="ml-1 text-xs text-gray-500">({{ $review->reviewedUser->rating }})</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $review->reviewedUser) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user mr-2"></i> Ver perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection