@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    {{-- Título que ya tenías --}}
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">¿CÓMO PERMUTAR?</h2>
        <p class="text-gray-800 text-sm mt-1">Cómo intercambiar y publicar de manera fácil y rápida.</p>
    </div>

    {{-- Subtítulo --}}
    <div class="text-center my-10">
        <h3 class="text-2xl font-bold">¿CÓMO PERMUTAR EN NUESTRA PLATAFORMA?</h3>
        <hr class="mt-3 border-t border-gray-200 pt-2 text-gray-400 text-center text-sm">
        <p class="text-gray-700 mt-2">Intercambiar nunca fue tan fácil, sigue estos pasos y encuentra el producto que buscas.</p>
    </div>
{{-- Pasos --}}
<div class="grid md:grid-cols-2 gap-8 mb-10">

    {{-- Paso 1 --}}
    <div class="fondo-gris shadow-lg rounded-xl p-6 relative flex flex-col md:flex-row items-stretch">
        {{-- Número en la esquina --}}
        <div class="absolute top-2 left-2 bg-orange-500 text-white font-bold rounded-full px-3 py-1">
            1
        </div>

        {{-- Imagen --}}
        <div class="bg-white rounded-xl flex items-center justify-center w-full md:w-40 mb-4 md:mb-0 md:mr-6">
            <img src="{{ asset('storage/images/paso1.webp') }}" alt="Publica" class="object-contain h-32 md:h-full w-auto p-1">
        </div>

        {{-- Texto --}}
      <div class="bg-white rounded-xl p-6 flex flex-col justify-start flex-1">
           <div class="flex items-center justify-start">
    <h3 class="text-black font-bold text-left py-2 rounded-md w-48">
       PUBLICA
    </h3>
</div>
            <p class="text-gray-600 text-sm mt-2 text-justify">
                ¿Tienes algo que ya no usas? Perfecto! El primer paso es crear una publicación.
                Sube imágenes de tu artículo, agrega descripciones, intereses y precio referencial.
                Mientras más detalles entregues, más posibilidades tienes de permutar.
            </p>
        </div>
    </div>

    {{-- Paso 2 --}}
    <div class="fondo-gris shadow-lg rounded-xl p-6 relative flex flex-col md:flex-row items-stretch">
        <div class="absolute top-2 left-2 bg-orange-500 text-white font-bold rounded-full px-3 py-1">
            2
        </div>
        <div class="bg-white rounded-xl flex items-center justify-center w-full md:w-40 mb-4 md:mb-0 md:mr-6">
            <img src="{{ asset('storage/images/paso2.webp') }}" alt="Elige intereses" class="object-contain h-32 md:h-full w-auto p-1">
        </div>
      <div class="bg-white rounded-xl p-6 flex flex-col justify-start flex-1">
           <div class="flex items-center justify-start">
    <h3 class="text-black font-bold text-left py-2 rounded-md w-48">
      ELIGE INTERESES
    </h3>
</div>
            <p class="text-gray-600 text-sm mt-2 text-justify">
                ¿Qué quieres a cambio? Puedes seleccionar productos o categorías que te interesen.
                Mientras más intereses selecciones, más fácil será encontrar coincidencias.
            </p>
        </div>
    </div>

    {{-- Paso 3 --}}
    <div class="fondo-gris shadow-lg rounded-xl p-6 relative flex flex-col md:flex-row items-stretch">
        <div class="absolute top-2 left-2 bg-orange-500 text-white font-bold rounded-full px-3 py-1">
            3
        </div>
        <div class="bg-white rounded-xl flex items-center justify-center w-full md:w-40 mb-4 md:mb-0 md:mr-6">
            <img src="{{ asset('storage/images/paso3.webp') }}" alt="Haz Match" class="object-contain h-32 md:h-full w-auto p-1">
        </div>
       <div class="bg-white rounded-xl p-6 flex flex-col justify-start flex-1">
           <div class="flex items-center justify-start">
    <h3 class="text-black font-bold text-left py-2 rounded-md w-48">
     HAZ MATCH
    </h3>
</div>
          <p class="text-gray-600 text-sm mt-2 text-justify">
                El sistema te mostrará las coincidencias ideales según lo que ofreces y buscas.
                Puedes enviar una solicitud de intercambio y recibirás notificación si logras match.
            </p>
        </div>
    </div>

    {{-- Paso 4 --}}
    <div class="fondo-gris shadow-lg rounded-xl p-6 relative flex flex-col md:flex-row items-stretch">
        <div class="absolute top-2 left-2 bg-orange-500 text-white font-bold rounded-full px-3 py-1">
            4
        </div>
        <div class="bg-white rounded-xl flex items-center justify-center w-full md:w-40 mb-4 md:mb-0 md:mr-6">
            <img src="{{ asset('storage/images/paso4.webp') }}" alt="Intercambia" class="object-contain h-32 md:h-full w-auto p-1">
        </div>
      <div class="bg-white rounded-xl p-6 flex flex-col justify-start flex-1">
           <div class="flex items-center justify-start">
    <h3 class="text-black font-bold text-left py-2 rounded-md w-48">
      INTERCAMBIA
    </h3>
</div>
           <p class="text-gray-600 text-sm mt-2 text-justify">
                Una vez que haya match y ambas partes estén interesadas, podrán conversar en mensajería privada.
                Coordinen directamente el intercambio y acuerden cómo y cuándo realizarlo.
            </p>
        </div>
    </div>

</div>



    {{-- Botón publicar --}}
    <div class="text-center mb-10">
        <a href="{{ route('products.createx') }}" class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 border-white w-36">
            + Publica tu primer producto
        </a>
    </div>

{{-- Preguntas frecuentes --}}
<div class="questions-container max-w-full mx-auto fondo-gris rounded-lg shadow overflow-hidden mb-10">

    <!-- Header amarillo -->
    <div class="bg-yellow-400 px-6 py-3">
        <div class="max-w-7xl mx-auto flex justify-center">
            <h2 class="text-xl font-semibold text-black text-center">Preguntas frecuentes</h2>
        </div>
    </div>

    <!-- Contenido -->
    <div class="px-6 py-6 max-w-7xl mx-auto space-y-4">
        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Cómo puedo permutar?</summary>
            <p class="mt-2 text-gray-600">Solo debes registrarte en la plataforma, publicar tu artículo con fotos y descripción, y esperar propuestas de otros usuarios. También puedes buscar artículos de tu interés y ofrecer tu intercambio directamente.
</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Qué se puede publicar?</summary>
            <p class="mt-2 text-gray-600">Se pueden publicar artículos nuevos o usados en buen estado: ropa, accesorios, libros, tecnología, muebles, artículos deportivos, entre otros. La idea es darle una segunda vida a lo que ya no usas.
</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">Artículos o cosas NO permitidas</summary>
            <p class="mt-2 text-gray-600">No está permitido publicar: animales, medicamentos, armas, artículos ilegales, sustancias ilícitas, productos en mal estado o que infrinjan derechos de autor. Estos serán eliminados automáticamente por el equipo de la plataforma.
</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Dónde realizar los intercambios?</summary>
            <p class="mt-2 text-gray-600">Recomendamos hacer los intercambios en lugares públicos y seguros, como plazas, cafeterías o centros comerciales. Así garantizas una experiencia confiable y transparente.
</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Tiene algún costo publicar o permutar en la plataforma?</summary>
            <p class="mt-2 text-gray-600">Publicar y permutar en nuestra plataforma es completamente gratuito. Solo debes registrarte y comenzar a intercambiar. Sólo se aplica el 8% de comisión de cada producto por permuta realizada.
</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Cómo puedo contactar a otra persona para concretar un intercambio?</summary>
            <p class="mt-2 text-gray-600">Puedes enviarle un mensaje directo desde la publicación y coordinar los detalles del intercambio en un chat privado.</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Puedo publicar artículos nuevos y usados?</summary>
            <p class="mt-2 text-gray-600">Sí, puedes publicar ambos, siempre que estén en buen estado y cumplan con las normas de la plataforma.</p>
        </details>

        <details class="bg-gray-50 rounded-lg shadow p-4">
            <summary class="cursor-pointer font-semibold">¿Qué debo hacer si el artículo recibido no corresponde a lo publicado?</summary>
            <p class="mt-2 text-gray-600">No ofrecemos garantía directa sobre los artículos, pero sí entregamos herramientas de comunicación y reportes para que tu experiencia sea segura y transparente.</p>
        </details>
    </div>
</div>


    {{-- Botón volver --}}
    <div class="text-center mb-10">
        <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-orange-700 text-white font-semibold py-2 px-6 rounded-full shadow-md hover:shadow-lg transition border-2 border-white w-36">
            Volver al inicio
        </a>
    </div>
</div>
@endsection
