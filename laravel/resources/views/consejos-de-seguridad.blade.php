@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    {{-- Encabezado --}}
   <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <div>
            <h2 class="text-3xl font-bold text-black">CONSEJOS DE SEGURIDAD</h2>
            <p class="text-gray-800 text-sm mt-1">Recomendaciones para que los usuarios realicen sus intercambios con confianza.</p>
        </div>

    </div>

    {{-- Consejos --}}
    <div class="grid md:grid-cols-3 gap-8 mb-10">
        {{-- Consejo 1 --}}
        <div class="fondo-gris shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo1.webp') }}" alt="Lugar seguro" class="h-28 mb-4 bg-white">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Elige un lugar público y seguro</h3>
            <p class="text-gray-600 text-sm">Realiza los intercambios en espacios concurridos como cafeterías, plazas o centros comerciales. Evita lugares aislados.</p>
        </div>

        {{-- Consejo 2 --}}
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo2.webp') }}" alt="Informa a alguien" class="h-28 mb-4">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Informa a alguien de confianza</h3>
            <p class="text-gray-600 text-sm">Comenta a un familiar o amigo con quién te encontrarás, o dónde te diriges en el trayecto para la permuta.</p>
        </div>

        {{-- Consejo 3 --}}
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo3.webp') }}" alt="Revisa artículo" class="h-28 mb-4">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Revisa el artículo antes de aceptar</h3>
            <p class="text-gray-600 text-sm">Verifica que el producto corresponda a la publicación y esté en el estado prometido.</p>
        </div>

        {{-- Consejo 4 --}}
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo4.webp') }}" alt="Mantén comunicación" class="h-28 mb-4">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Mantén la comunicación dentro de la plataforma</h3>
            <p class="text-gray-600 text-sm">No compartas datos personales sensibles como dirección o cuentas bancarias, usa el chat oficial.</p>
        </div>

        {{-- Consejo 5 --}}
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo5.webp') }}" alt="Confía en tu instinto" class="h-28 mb-4">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Confía en tu instinto</h3>
            <p class="text-gray-600 text-sm">Si algo no te genera seguridad o la otra persona muestra actitudes sospechosas, cancela el intercambio.</p>
        </div>

        {{-- Consejo 6 --}}
        <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center text-center">
            <img src="{{ asset('storage/images/consejo6.webp') }}" alt="Evita efectivo" class="h-28 mb-4">
            <h3 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Evita entregar dinero en efectivo</h3>
            <p class="text-gray-600 text-sm">Recuerda que la plataforma está pensada para permutas. Si acuerdas un pago adicional, hazlo con precaución.</p>
        </div>
    </div>

    {{-- Botón volver --}}
    <div class="text-center mb-10">
        <a href="#top" class="bg-orange-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-orange-700 transition">
            Volver al inicio
        </a>
    </div>
</div>
@endsection
