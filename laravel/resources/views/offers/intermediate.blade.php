@extends('layouts.app')

@section('title', 'Preguntas Intermedias - Permuta2')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-5xl">
    <!-- Header -->
<div class="relative mb-8">
    <!-- Botón alineado a la izquierda -->
    <div class="absolute left-0 top-0">
        <button 
            onclick="history.back()" 
            class="inline-flex items-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-lg shadow-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 mr-1" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor">
                <path stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2" 
                      d="M15 19l-7-7 7-7" />
            </svg>
            Volver atrás
        </button>
    </div>

    <!-- Bloque centrado -->
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Preguntas Intermedias</h1>
        <p class="text-gray-600">Resuelve dudas sobre el proceso de permuta</p>
    </div>
</div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Información del Trueque -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Detalles del Trueque</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Producto Solicitado -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">
                            @if(auth()->id() === $offer->from_user_id)
                                Producto que solicitas
                            @else
                                Tu producto
                            @endif
                        </h3>
                    </div>
                    
                    <div class="flex items-start">
                        @if($offer->productRequested && $offer->productRequested->first_image_url)
                            <img src="{{ $offer->productRequested->first_image_url }}" 
                                 alt="{{ $offer->productRequested->title }}" 
                                 class="w-16 h-16 object-cover rounded-lg mr-4 shadow-sm">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            @if($offer->productRequested)
                                <p class="font-semibold text-gray-800">{{ $offer->productRequested->title }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    @if(auth()->id() === $offer->from_user_id)
                                        Dueño: {{ $offer->toUser->name ?? 'Usuario desconocido' }}
                                    @else
                                        Tu producto
                                    @endif
                                </p>
                                <div class="mt-2 flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $offer->productRequested->was_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $offer->productRequested->was_paid ? 'Pagado' : 'Pendiente de pago' }}
                                    </span>
                                </div>
                            @else
                                <p class="text-red-500">Producto no disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Productos Ofrecidos -->
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">
                            @if(auth()->id() === $offer->from_user_id)
                                Tus productos ofrecidos
                            @else
                                Productos que te ofrecen
                            @endif
                        </h3>
                    </div>
                    
                    @if($offer->productsOffered->count() > 0)
                        <div class="space-y-3">
                            @foreach($offer->productsOffered as $productOffered)
                                <div class="flex items-start border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    @if($productOffered->first_image_url)
                                        <img src="{{ $productOffered->first_image_url }}" 
                                             alt="{{ $productOffered->title }}" 
                                             class="w-12 h-12 object-cover rounded-lg mr-3 shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded-lg mr-3 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $productOffered->title }}</p>
                                        <div class="mt-1 flex items-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $productOffered->was_paid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $productOffered->was_paid ? 'Pagado' : 'Pendiente de pago' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-500 text-sm">No hay productos ofrecidos</p>
                    @endif
                    
                    <!-- Información adicional de la oferta -->
                    @if($offer->complementary_amount > 0 || $offer->comment)
                        <div class="mt-4 pt-3 border-t border-gray-200">
                            @if($offer->complementary_amount > 0)
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Complemento en dinero:</span>
                                    <span class="font-semibold text-green-600">${{ number_format($offer->complementary_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            
                            @if($offer->comment)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 mb-1">Comentario:</p>
                                    <p class="text-sm text-gray-800 bg-gray-50 p-2 rounded-lg">{{ $offer->comment }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Estado del Trueque -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Estado del Trueque</h2>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    @if($offer->status === 'waiting_payment')
                        @php
                            $unpaidOfferedProducts = $offer->productsOffered->filter(function($product) {
                                return !$product->was_paid;
                            });
                            $userHasUnpaidProducts = $unpaidOfferedProducts->count() > 0 && auth()->id() === $offer->from_user_id;
                            $requestedProductUnpaid = !$offer->productRequested->was_paid && auth()->id() === $offer->to_user_id;
                        @endphp

                        @if($userHasUnpaidProducts)
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-yellow-700 font-medium">
                                Tienes {{ $unpaidOfferedProducts->count() }} producto(s) pendiente(s) de pago para completar el trueque.
                            </p>
                        @elseif($requestedProductUnpaid)
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-yellow-700 font-medium">Debes pagar la comisión del producto solicitado para completar el trueque.</p>
                        @else
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-yellow-700 font-medium">Esperando que el otro usuario complete su pago.</p>
                        @endif
                    @elseif($offer->status === 'accepted')
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-green-700 font-medium">¡Trueque aceptado! Ambos productos han sido pagados.</p>
                    @else
                        <p class="text-gray-700">Estado: {{ $offer->status_name }}</p>
                    @endif
                </div>
                
                <div class="mt-4 md:mt-0">
                    @if($offer->status === 'waiting_payment')
                        @php
                            $unpaidOfferedProducts = $offer->productsOffered->filter(function($product) {
                                return !$product->was_paid;
                            });
                        @endphp

                        @if($unpaidOfferedProducts->count() > 0 && auth()->id() === $offer->from_user_id)
                            <a href="{{ route('checkout.commission-offered', $offer) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pagar Comisiones ({{ $unpaidOfferedProducts->count() }})
                            </a>
                        @elseif(!$offer->productRequested->was_paid && auth()->id() === $offer->to_user_id)
                            <a href="{{ route('checkout.commission', $offer) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Pagar Comisión
                            </a>
                        @endif
                    @elseif($offer->status === 'accepted' && $offer->chat)
                        <a href="{{ route('chat.show', $offer->chat) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            Ir al Chat
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Formulario para Nueva Pregunta -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Hacer una nueva pregunta</h2>
            <form action="{{ route('intermediate.questions.store', $offer) }}" method="POST" id="questionForm">
                @csrf
                <div class="mb-4">
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Escribe tu pregunta</label>
            <textarea name="question" id="question" rows="3" 
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
          placeholder="Ej: ¿Cómo coordinaríamos el intercambio de productos?" 
          oninput="validateIntermediateInput(this, 'questionError')"></textarea>
                    <div class="text-xs text-gray-500 mt-1">Por tu seguridad, no compartas información de contacto (teléfono, email, redes sociales, usuarios con @).</div>
                    @error('question')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p id="questionError" class="mt-1 text-sm text-red-600 hidden">No puedes enviar información de contacto en las preguntas.</p>
                </div>
                <div>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" 
         class="h-5 w-5 rotate-90" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor">
        <path stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
    </svg>
                        Enviar Pregunta 
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Lista de Preguntas Existentes -->
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Preguntas y Respuestas</h2>
            
            @if($offer->intermediateQuestions->count() > 0)
                <div class="space-y-6">
                    @foreach($offer->intermediateQuestions as $question)
                        <div class="bg-gray-50 rounded-lg p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $question->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $question->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-700 pl-7">{{ $question->question }}</p>
                            
                            @if($question->is_answered)
                                <div class="mt-4 pl-7">
                                    <div class="flex items-center mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <p class="font-medium text-gray-800">{{ $question->answerer->name }} respondió</p>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                                        <p class="text-gray-700">{{ $question->answer }}</p>
                                    </div>
                                </div>
                            @else
                                <!-- Formulario para responder (solo el otro usuario puede responder) -->
                                @if(auth()->id() !== $question->user_id && 
                                   (auth()->id() === $offer->from_user_id || auth()->id() === $offer->to_user_id))
                                    <form action="{{ route('intermediate.questions.answer', $question) }}" method="POST" class="mt-4 pl-7" id="answerForm-{{ $question->id }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="answer-{{ $question->id }}" class="block text-sm font-medium text-gray-700 mb-1">Tu respuesta</label>
                                       <textarea name="answer" id="answer-{{ $question->id }}" rows="2" 
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
          placeholder="Escribe tu respuesta aquí..." 
          oninput="validateIntermediateInput(this, 'answerError-{{ $question->id }}')" 
          required></textarea>
                                            <div class="text-xs text-gray-500 mt-1">No incluyas información de contacto como teléfonos, emails o redes sociales.</div>
                                            @error('answer')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p id="answerError-{{ $question->id }}" class="mt-1 text-sm text-red-600 hidden">No puedes enviar información de contacto en las respuestas.</p>
                                        </div>
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                            Responder
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600">No hay preguntas aún. Sé el primero en preguntar.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Lista de palabras prohibidas (misma que en config/forbidden_words.php)
const FORBIDDEN_WORDS = [
    // Correos y derivados
    'gmail', 'hotmail', 'outlook', 'live', 'yahoo', 'icloud', 'proton',
    'zoho', 'mail', 'correo', 'email', 'e-mail', 'inbox', 'com', 'cl',
    
    // Telefonía
    'telefono', 'teléfono', 'fono', 'celular', 'movil', 'móvil',
    'whatsapp', 'wasap', 'wsp', 'wp', 'cel', 'tel', 'guasap', 'uatza',
    'wasapp', 'instagran', 'g-mail', 'insta.gram',
    
    // Redes sociales
    'instagram', 'insta', 'ig', 'facebook', 'fb', 'messenger', 'twitter',
    'x', 'tiktok', 'telegram', 'tg', 'discord', 'linkedin', 'face', 'meta',
    'snap', 'snapchat', 'line', 'wechat', 'viber', 'signal',
    
    // Plataformas de contacto directo
    'zoom', 'meet', 'teams', 'skype', 'facetime',
    
    // Pagos e información bancaria
    'rut', 'banco', 'transferencia', 'paypal', 'mercadopago', 'mercado',
    'cuenta', 'transfiere', 'alias', 'datos', 'bancarios',
    
    // Direcciones y contacto físico
    'direccion', 'dirección', 'domicilio', 'oficina', 'local', 'sucursal',
    
    // Variantes "disfraz"
    'arroba', 'at', 'dot', 'punto', 'guion', 'dash', 'dm', 'md', 'inbox',
    'pv', 'priv', 'deletreado', 'llamada', 'llamadas', 'llamar', 'llamame',
    'llamarme', 'numero', 'número', 'contacto',
    
    // Palabras de frases (separadas)
    'escríbelo', 'separado', 'espacios', 'símbolos', 'palabras',
    'interno', 'datos', 'transferirte', 'dame', 'fuera', 'app', 'página',
    'afuera', 'mejor', 'medio', 'otro', 'lado', 'por'
];

// Frases completas prohibidas
const FORBIDDEN_PHRASES = [
    'escríbelo separado', 'con espacios', 'sin símbolos', 'en palabras',
    'g m a i l', 'guasap', 'uatsap', 'insta_gram', 'x interno',
    'te mando los datos', 'para transferirte', 'dame tu rut',
    'fuera de acá', 'fuera de la app', 'fuera de la página',
    'hablemos afuera', 'mejor por otro lado', 'por otro medio'
];

// Variantes comunes
const FORBIDDEN_VARIANTS = {
    'gmail': ['g m a i l', 'g.mail', 'g-mail', 'g*mail', 'gmail'],
    'whatsapp': ['wasap', 'guasap', 'uatza', 'wsp', 'whats app', 'whatsapp'],
    'instagram': ['insta', 'ig', 'insta gram', 'insta.gram', 'insta_gram'],
    'facebook': ['fb', 'face', 'face book', 'facebook'],
    'telefono': ['fono', 'tel', 'tele fono', 'telefono', 'teléfono'],
    'correo': ['mail', 'e-mail', 'email', 'correo']
};

// Función para normalizar texto (eliminar acentos, símbolos)
function normalizeText(text) {
    return text.toLowerCase()
        .replace(/[áäàâ]/g, 'a')
        .replace(/[éëèê]/g, 'e')
        .replace(/[íïìî]/g, 'i')
        .replace(/[óöòô]/g, 'o')
        .replace(/[úüùû]/g, 'u')
        .replace(/[ñ]/g, 'n')
        .replace(/[^a-z0-9\s]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

// Función para verificar palabras prohibidas
function checkForbiddenWords(text) {
    const normalizedText = normalizeText(text);
    
    // Verificar palabras individuales
    for (const word of FORBIDDEN_WORDS) {
        const normalizedWord = normalizeText(word);
        
        // Verificar como palabra completa
        if (new RegExp('\\b' + normalizedWord + '\\b', 'i').test(normalizedText)) {
            return `No puedes usar la palabra "${word}" en tu mensaje.`;
        }
        
        // Verificar también sin límites de palabra
        if (normalizedText.includes(normalizedWord)) {
            return `No puedes usar la palabra "${word}" en tu mensaje.`;
        }
    }
    
    // Verificar frases completas
    for (const phrase of FORBIDDEN_PHRASES) {
        const normalizedPhrase = normalizeText(phrase);
        if (normalizedText.includes(normalizedPhrase)) {
            return `No puedes usar la frase "${phrase}" en tu mensaje.`;
        }
    }
    
    // Verificar variantes comunes
    for (const [baseWord, variants] of Object.entries(FORBIDDEN_VARIANTS)) {
        for (const variant of variants) {
            const normalizedVariant = normalizeText(variant);
            if (normalizedText.includes(normalizedVariant)) {
                return `No puedes incluir información de contacto (${baseWord}) en tu mensaje.`;
            }
        }
    }
    
    // Verificar patrones de números de teléfono
    const phonePattern = /(\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/;
    if (phonePattern.test(text)) {
        return 'No puedes enviar números de teléfono.';
    }
    
    // Verificar patrones de email
    const emailPattern = /[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/;
    if (emailPattern.test(text)) {
        return 'No puedes enviar direcciones de correo electrónico.';
    }
    
    // Verificar URLs
    const urlPattern = /(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+\.[a-zA-Z]{2,}(\/[a-zA-Z0-9-]*)*/;
    if (urlPattern.test(text)) {
        return 'No puedes enviar enlaces.';
    }
    
    return null;
}

// Función mejorada para validar preguntas y respuestas intermedias
function validateIntermediateInput(textarea, errorElementId) {
    const value = textarea.value;
    const errorElement = document.getElementById(errorElementId);
    
    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
    if (value.includes('@')) {
        const isQuestion = textarea.id === 'question' || textarea.id.startsWith('answer-');
        const message = isQuestion 
            ? 'No puedes usar el símbolo @ en las preguntas.' 
            : 'No puedes usar el símbolo @ en las respuestas.';
        
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
        textarea.classList.add('border-red-500');
        return false;
    }
    
    // Validar palabras prohibidas
    const forbiddenError = checkForbiddenWords(value);
    if (forbiddenError) {
        errorElement.textContent = forbiddenError;
        errorElement.classList.remove('hidden');
        textarea.classList.add('border-red-500');
        return false;
    }
    
    // Quitar espacios para validar emails
    const cleanValue = value.replace(/\s+/g, '');
    
    // Expresiones regulares mejoradas para detectar información de contacto
    const emailPattern = /[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/i;
    const phonePattern = /\+?\d{1,3}[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;
    const socialPattern = /(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i;
    const whatsappPattern = /whatsapp\s*[:.]?\s*\+?\d{1,3}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/i;
    
    // Lista de dominios de redes sociales
    const socialDomains = [
        'facebook', 'twitter', 'instagram', 'whatsapp',
        'tiktok', 'telegram', 'snapchat', 'linkedin',
        'youtube', 'discord', 'reddit', 'pinterest'
    ];
    
    // Palabras clave de contacto
    const contactKeywords = [
        'contacto', 'contáctame', 'escríbeme', 'llámame', 
        'whatsapp', 'telegram', 'instagram', 'facebook',
        'twitter', 'snapchat', 'tiktok', 'red social'
    ];
    
    let errorMessage = '';
    
    // Validación en cascada (igual que en el backend)
    if (emailPattern.test(cleanValue)) {
        errorMessage = 'No puedes enviar direcciones de correo electrónico.';
        textarea.classList.add('border-red-500');
    } else if (phonePattern.test(value)) {
        errorMessage = 'No puedes enviar números de teléfono.';
        textarea.classList.add('border-red-500');
    } else if (socialPattern.test(value)) {
        errorMessage = 'No puedes enviar enlaces a redes sociales.';
        textarea.classList.add('border-red-500');
    } else if (whatsappPattern.test(value)) {
        errorMessage = 'No puedes compartir información de WhatsApp.';
        textarea.classList.add('border-red-500');
    } else {
        // Detectar dominios de redes sociales sin URL completa
        const lowerValue = value.toLowerCase();
        let foundSocialDomain = false;
        
        for (const domain of socialDomains) {
            if (lowerValue.includes(domain)) {
                if (value.match(/\d|http|\.com|\.net|\.org/i)) {
                    foundSocialDomain = true;
                    break;
                }
            }
        }
        
        if (foundSocialDomain) {
            errorMessage = 'No puedes compartir información de redes sociales.';
            textarea.classList.add('border-red-500');
        } else {
            // Detectar palabras clave de contacto combinadas con símbolos
            for (const keyword of contactKeywords) {
                if (lowerValue.includes(keyword)) {
                    const hasHttp = value.includes('http://') || value.includes('https://');
                    const hasDotCom = value.includes('.com') || value.includes('.net') || value.includes('.org');
                    const hasNumbers = /\d/.test(value);
                    
                    if (hasHttp || hasDotCom || hasNumbers) {
                        errorMessage = 'No puedes solicitar contacto directo.';
                        textarea.classList.add('border-red-500');
                        break;
                    }
                }
            }
            
            if (!errorMessage) {
                textarea.classList.remove('border-red-500');
            }
        }
    }
    
    // Mostrar u ocultar mensaje de error
    if (errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('hidden');
        return false;
    } else {
        errorElement.classList.add('hidden');
        return true;
    }
}

// Validación antes de enviar el formulario de pregunta principal
document.addEventListener('DOMContentLoaded', function() {
    const questionForm = document.getElementById('questionForm');
    if (questionForm) {
        questionForm.addEventListener('submit', function(e) {
            const textarea = document.getElementById('question');
            if (!validateIntermediateInput(textarea, 'questionError')) {
                e.preventDefault();
                textarea.focus();
            }
        });
    }
    
    // Para cada formulario de respuesta
    @foreach($offer->intermediateQuestions as $question)
        @if(!$question->is_answered && auth()->id() !== $question->user_id && 
           (auth()->id() === $offer->from_user_id || auth()->id() === $offer->to_user_id))
            const answerForm{{ $question->id }} = document.getElementById('answerForm-{{ $question->id }}');
            if (answerForm{{ $question->id }}) {
                answerForm{{ $question->id }}.addEventListener('submit', function(e) {
                    const textarea = document.getElementById('answer-{{ $question->id }}');
                    if (!validateIntermediateInput(textarea, 'answerError-{{ $question->id }}')) {
                        e.preventDefault();
                        textarea.focus();
                    }
                });
            }
        @endif
    @endforeach
    
    // Validación en tiempo real para dar retroalimentación inmediata
    document.querySelectorAll('textarea[id^="answer-"], textarea#question').forEach(textarea => {
        textarea.addEventListener('input', function() {
            let errorElementId;
            if (this.id === 'question') {
                errorElementId = 'questionError';
            } else if (this.id.startsWith('answer-')) {
                const questionId = this.id.replace('answer-', '');
                errorElementId = 'answerError-' + questionId;
            }
            
            if (errorElementId) {
                validateIntermediateInput(this, errorElementId);
            }
        });
    });
});
</script>

<style>
.border-red-500 {
    border-color: #ef4444;
}
</style>
@endsection