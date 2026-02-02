{{-- resources/views/admin/categories/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Categoría')
@section('subtitle', 'Información completa de la categoría')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Categorías</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        <i class="fas fa-edit mr-2"></i> Editar Categoría
    </a>
    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="confirmAction('¿Estás seguro de eliminar esta categoría?', () => this.form.submit())"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-trash mr-2"></i> Eliminar
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información de la categoría -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información de la Categoría</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-start">
                    @if($category->image)
                    <div class="flex-shrink-0 mr-6">
                        <img class="h-24 w-24 rounded-md object-cover" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                    </div>
                    @endif
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                        <p class="text-sm text-gray-500 mt-1">Slug: {{ $category->slug }}</p>
                        
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Categoría padre</h4>
                                <p class="text-sm text-gray-900">{{ $category->parent->name ?? '—' }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Productos asociados</h4>
                                <p class="text-sm text-gray-900">{{ $category->products_count }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Subcategorías</h4>
                                <p class="text-sm text-gray-900">{{ $category->children->count() }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Fecha de creación</h4>
                                <p class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subcategorías -->
        @if($category->children->count())
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Subcategorías</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($category->children as $child)
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            @if($child->image)
                            <img class="h-10 w-10 rounded-md object-cover mr-3" src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}">
                            @endif
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $child->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $child->products_count }} productos</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.categories.show', $child) }}" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Productos de la categoría -->
        @if($category->products->count())
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Productos en esta categoría</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-md object-cover mr-3" src="{{ $product->first_image_url }}" alt="{{ $product->title }}">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->title, 30) }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Estadísticas -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Estadísticas</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Total de productos</dt>
                        <dd class="text-sm text-gray-900">{{ $category->products_count }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Subcategorías</dt>
                        <dd class="text-sm text-gray-900">{{ $category->children->count() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Creada</dt>
                        <dd class="text-sm text-gray-900">{{ $category->created_at->format('d/m/Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Actualizada</dt>
                        <dd class="text-sm text-gray-900">{{ $category->updated_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection