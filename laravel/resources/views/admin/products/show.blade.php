{{-- resources/views/admin/products/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Producto')
@section('subtitle', 'Información completa del producto')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Productos</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-edit mr-2"></i> Editar Producto
    </a>
    @if($product->status == 'pending')
    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <i class="fas fa-check mr-2"></i> Aprobar
        </button>
    </form>
    @endif
    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="confirmAction('¿Estás seguro de eliminar este producto?', () => this.form.submit())"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-trash mr-2"></i> Eliminar
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del producto -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Producto</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->title }}</h1>
                
                <!-- Galería de imágenes -->
                @if($product->images->count())
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Imágenes del producto</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($product->images as $image)
                        <div class="aspect-w-1 aspect-h-1">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Imagen del producto" class="rounded-lg object-cover">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Descripción -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Descripción</h4>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>

                <!-- Detalles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Detalles</h4>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Precio referencial</dt>
                                <dd class="text-sm text-gray-900">${{ number_format($product->price_reference, 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Condición</dt>
                                <dd class="text-sm text-gray-900">{{ $product->condition_name }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Categoría</dt>
                                <dd class="text-sm text-gray-900">{{ $product->category->name ?? 'Sin categoría' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Ubicación</dt>
                                <dd class="text-sm text-gray-900">{{ $product->location }}</dd>
                            </div>
                        </dl>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Estado</h4>
                        <dl class="space-y-2">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Estado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : ($product->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $product->status }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Publicado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->published ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->published ? 'Sí' : 'No' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Pago realizado</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->was_paid ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->was_paid ? 'Sí' : 'No' }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Fecha de publicación</dt>
                                <dd class="text-sm text-gray-900">{{ $product->publication_date->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Fecha de expiración</dt>
                                <dd class="text-sm text-gray-900">{{ $product->expiration_date->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Tags -->
                @if($product->tags)
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Etiquetas</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $product->tags) as $tag)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ trim($tag) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Preguntas y respuestas -->
        @if($product->questions->count())
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Preguntas y Respuestas</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-4">
                    @foreach($product->questions->where('parent_id', null) as $question)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full" src="{{ $question->user->profile_photo_url }}" alt="{{ $question->user->name }}">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $question->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $question->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mb-3">{{ $question->content }}</p>
                        
                        @foreach($question->answers as $answer)
                        <div class="ml-6 pl-4 border-l-2 border-gray-200 py-2">
                            <div class="flex justify-between items-start mb-1">
                                <div class="flex items-center">
                                    <img class="h-6 w-6 rounded-full" src="{{ $answer->user->profile_photo_url }}" alt="{{ $answer->user->name }}">
                                    <div class="ml-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $answer->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $answer->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">{{ $answer->content }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Información del usuario y acciones -->
    <div class="lg:col-span-1">
        <!-- Información del usuario -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Usuario</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <img class="h-12 w-12 rounded-full" src="{{ $product->user->profile_photo_url }}" alt="{{ $product->user->name }}">
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $product->user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $product->user->email }}</p>
                        <p class="text-xs text-gray-400">{{ $product->user->alias }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.users.show', $product->user) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-user mr-2"></i> Ver perfil del usuario
                    </a>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones Rápidas</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-3">
                    @if($product->status == 'pending')
                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-check mr-2"></i> Aprobar Producto
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.products.toggle-published', $product) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-eye{{ $product->published ? '-slash' : '' }} mr-2"></i> 
                            {{ $product->published ? 'Ocultar' : 'Publicar' }} Producto
                        </button>
                    </form>

                    <a href="{{ route('admin.products.edit', $product) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-edit mr-2"></i> Editar Producto
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection