@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    {{-- Encabezado --}}
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado m">
        <div>
            <h2 class="text-3xl font-bold text-black">NOSOTROS</h2>
            <p class="text-gray-800 text-sm mt-1">
                Transformamos el intercambio en una experiencia simple y digital.
            </p>
        </div>
    </div>

    {{-- Sobre Permuta2 --}}
    <div class="grid md:grid-cols-2 gap-8 mb-12 items-center fondo-gris p-6 rounded-xl mt-4">
        <div class="p-5">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Sobre Permuta2</h3>
            <hr class="mt-8 border-t border-gray-400 pt-6 text-gray-400 text-center text-sm">
            <p class="text-gray-600 text-sm leading-relaxed mb-3">
                En Permuta2 creemos que cada objeto puede tener más de una vida. Somos una plataforma digital pensada para quienes deseen intercambiar o truequear bienes, nuevos u usados, de forma sencilla, segura y sin límites.
            </p>
            <p class="text-gray-600 text-sm leading-relaxed mb-3">
                Nuestro propósito es redefinir la manera de consumir, impulsando una economía circular que disminuya el desperdicio y promueva el consumo responsable. Aquí cualquier persona puede ofrecer lo que ya no necesita a otros usuarios y concretar intercambios en un ambiente de confianza y comunicación directa.
            </p>
            <p class="text-gray-600 text-sm leading-relaxed mb-3">
                Lo que nos distingue es la combinación del dinamismo de una interfaz digital con la interacción en una comunidad real. Estamos frente a una experiencia moderna y amigable, en donde los procesos son fáciles de ejecutar, sin que eso implique perder transparencia ni seguridad.
            </p>
            <p class="text-gray-600 text-sm leading-relaxed">
                En Permuta2 trabajamos cada día para ofrecer herramientas que hagan del trueque una alternativa tan práctica como atractiva, demostrando que reutilizar no solo es posible, sino que también es una elección inteligente para el planeta y para tu bolsillo.
            </p>
        </div>
        <div class="flex justify-center">
            <img src="{{ asset('storage/images/nosotros-permuta2.webp') }}" alt="Sobre Permuta2" class="rounded-lg max-h-96">
        </div>
    </div>

    {{-- Nuestros valores --}}
    <div class="mb-12 p-6 fondo-gris rounded-xl">
        <h3 class="text-2xl font-bold text-gray-800 text-center">Nuestros valores</h3>
        <div class="grid md:grid-cols-4 gap-8">
            
            {{-- Confianza --}}
            <div class=" p-6 flex flex-col items-center text-center">
                <img src="{{ asset('storage/images/1-img-nosotros.webp') }}" alt="Confianza" class="h-48 mb-4 rounded-xl">
                <h4 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Confianza</h4>
                <p class="text-gray-600 text-sm">
                    Creamos un espacio seguro y transparente. Con herramientas que protegen la información y respaldan cada intercambio.
                </p>
            </div>

            {{-- Comunidad --}}
            <div class=" p-6 flex flex-col items-center text-center">
                <img src="{{ asset('storage/images/2-img-nosotros.webp') }}" alt="Comunidad" class="h-48 mb-4 rounded-xl">
                <h4 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Comunidad</h4>
                <p class="text-gray-600 text-sm">
                    Fomentamos el encuentro entre personas que comparten los mismos intereses y oportunidades, generando redes de colaboración y respeto mutuo.
                </p>
            </div>

            {{-- Sostenibilidad --}}
            <div class=" p-6 flex flex-col items-center text-center">
                <img src="{{ asset('storage/images/3-img-nosotros.webp') }}" alt="Sostenibilidad" class="h-48 mb-4 rounded-xl">
                <h4 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Sostenibilidad</h4>
                <p class="text-gray-600 text-sm">
                    Impulsamos la reutilización de bienes para reducir el impacto ambiental y promover un consumo más consciente.
                </p>
            </div>

            {{-- Innovación --}}
            <div class=" p-6 flex flex-col items-center text-center">
                <img src="{{ asset('storage/images/4-img-nosotros.webp') }}" alt="Innovación" class="h-48 mb-4 rounded-xl">
                <h4 class="bg-yellow-300 text-black font-bold px-4 py-2 rounded-md mb-2">Innovación</h4>
                <p class="text-gray-600 text-sm">
                    Integramos tecnología moderna para que el proceso de trueque sea simple, intuitivo y en constante evolución.
                </p>
            </div>
        </div>
    </div>


</div>
@endsection
