{{-- resources/views/admin/questions/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detalle de Pregunta')
@section('subtitle', 'Pregunta y respuestas')

@section('breadcrumb')
<li>
    <div class="flex items-center">
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <a href="{{ route('admin.questions.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Preguntas</a>
        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
        <span class="text-sm font-medium text-gray-500">Detalle</span>
    </div>
</li>
@endsection

@section('actions')
<div class="flex space-x-3">
    <form action="{{ route('admin.questions.destroy', $question) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="confirmAction('¿Estás seguro de eliminar esta pregunta?', () => this.form.submit())"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <i class="fas fa-trash mr-2"></i> Eliminar
        </button>
    </form>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Pregunta y respuestas -->
    <div class="lg:col-span-2">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pregunta</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <!-- Pregunta principal -->
                <div class="border border-gray-200 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <img class="h-10 w-10 rounded-full mr-3" src="{{ $question->user->profile_photo_url }}" alt="{{ $question->user->name }}">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $question->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $question->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">{{ $question->content }}</p>
                </div>

                <!-- Respuestas -->
                <h4 class="text-lg font-medium text-gray-900 mb-4">
                    Respuestas ({{ $question->answers->count() }})
                </h4>
                
                @if($question->answers->count())
                <div class="space-y-4">
                    @foreach($question->answers as $answer)
                    <div class="border border-gray-200 rounded-lg p-4 ml-6">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center">
                                <img class="h-8 w-8 rounded-full mr-3" src="{{ $answer->user->profile_photo_url }}" alt="{{ $answer->user->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $answer->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $answer->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">{{ $answer->content }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No hay respuestas aún.</p>
                @endif

                <!-- Formulario para responder -->
                <div class="mt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Responder como administrador</h4>
                    <form action="{{ route('admin.questions.answer', $question) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="answer" rows="4" 
                                      class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                      placeholder="Escribe tu respuesta..."></textarea>
                            @error('answer')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-reply mr-2"></i> Publicar Respuesta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del producto -->
    <div class="lg:col-span-1">
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Producto</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-md object-cover mr-3" src="{{ $question->product->first_image_url }}" alt="{{ $question->product->title }}">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $question->product->title }}</h4>
                        <p class="text-xs text-gray-500">${{ number_format($question->product->price_reference, 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.products.show', $question->product) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-external-link-alt mr-2"></i> Ver producto
                </a>
            </div>
        </div>

        <!-- Información del usuario -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Usuario</h3>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center mb-4">
                    <img class="h-12 w-12 rounded-full mr-3" src="{{ $question->user->profile_photo_url }}" alt="{{ $question->user->name }}">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $question->user->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $question->user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $question->user) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user mr-2"></i> Ver perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection