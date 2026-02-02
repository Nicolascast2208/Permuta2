@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Dejar Reseña a {{ $otherUser->name }}</h1>
        
        <form action="{{ route('reviews.store', $chat) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Calificación:</label>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <input type="radio" name="rating" value="{{ $i }}" 
                               class="hidden peer" id="rating{{ $i }}">
                        <label for="rating{{ $i }}" 
                               class="text-3xl cursor-pointer peer-checked:text-yellow-400 text-gray-300">
                            ★
                        </label>
                    @endfor
                </div>
                @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Comentario (opcional):</label>
                <textarea name="comment" rows="4" 
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="¿Cómo fue tu experiencia con este permutador?"></textarea>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('chat.show', $chat) }}" 
                   class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Enviar Reseña
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const stars = document.querySelectorAll('label[for^="rating"]');
            stars.forEach((star, index) => {
                if (index < this.value) {
                    star.classList.add('text-yellow-400');
                    star.classList.remove('text-gray-300');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        });
    });
</script>
@endsection