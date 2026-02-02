@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    {{-- Encabezado --}}
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold text-black">PREGUNTAS FRECUENTES (FAQ)</h2>
        <p class="text-gray-800 text-sm mt-1">Encuentra respuestas a las dudas más comunes sobre Permuta2.</p>
    </div>

    {{-- Sección: Plataforma --}}
    <div class="questions-container max-w-full mx-auto fondo-gris rounded-lg shadow overflow-hidden mb-10">
        <div class="bg-yellow-400 px-6 py-3">
            <h2 class="text-xl font-semibold text-black text-center">Plataforma</h2>
        </div>

        <div class="px-6 py-6 max-w-7xl mx-auto space-y-4">
            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Qué es Permuta2?</summary>
                <p class="mt-2 text-gray-600">Permuta2 es una plataforma en línea pensada para que las personas puedan intercambiar o truequear objetos nuevos o usados de manera sencilla, segura y sin límites. Conectamos a quienes quieren dar una segunda vida a lo que ya tienen, fomentando la reutilización y el consumo consciente.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Cuáles son los valores o motivaciones de Permuta2?</summary>
                <p class="mt-2 text-gray-600">Nos mueve la confianza, la comunidad, la sostenibilidad y la innovación. Creemos en la economía circular y en que cada objeto merece más de una oportunidad, por eso trabajamos para ofrecer un espacio seguro, colaborativo y tecnológicamente amigable.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Qué puedo hacer dentro de la plataforma?</summary>
                <p class="mt-2 text-gray-600">Puedes explorar publicaciones, buscar artículos que te interesen, contactar a otros usuarios y proponer intercambios. También puedes crear tus propias publicaciones para encontrar el trueque perfecto, todo en un entorno que facilita la comunicación directa.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Debo crear una cuenta para utilizar la plataforma?</summary>
                <p class="mt-2 text-gray-600">No necesitas una cuenta para navegar y revisar las publicaciones. Sin embargo, si deseas hacer preguntas, enviar propuestas o concretar un intercambio, sí deberás registrarte para que podamos mantener un espacio seguro y confiable.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Cómo puedo contactarme con Permuta2?</summary>
                <p class="mt-2 text-gray-600">Puedes escribirnos a nuestro correo electrónico oficial: <strong>contacto@permuta2.cl</strong></p>
            </details>
        </div>
    </div>

    {{-- Sección: Cuenta --}}
    <div class="questions-container max-w-full mx-auto fondo-gris rounded-lg shadow overflow-hidden mb-10">
        <div class="bg-yellow-400 px-6 py-3">
            <h2 class="text-xl font-semibold text-black text-center">Cuenta</h2>
        </div>

        <div class="px-6 py-6 max-w-7xl mx-auto space-y-4">
            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Cómo crear una cuenta?</summary>
                <p class="mt-2 text-gray-600">En la página principal haz clic en el botón “Crear cuenta”. Completa el formulario con los datos solicitados y sigue las instrucciones para activar tu perfil.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Puedo realizar una permuta sin una cuenta creada?</summary>
                <p class="mt-2 text-gray-600">No. Para publicar artículos o concretar un intercambio es necesario tener una cuenta registrada.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Qué datos necesito para crear mi perfil?</summary>
                <p class="mt-2 text-gray-600">Al registrarte te pediremos nombre completo, RUT, correo electrónico y una contraseña. Luego podrás agregar más datos como ciudad, foto y número de celular.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Puedo crear una cuenta si soy menor de edad?</summary>
                <p class="mt-2 text-gray-600">No. Permuta2 está disponible solo para personas mayores de 18 años.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Puedo editar mi perfil después de crearlo?</summary>
                <p class="mt-2 text-gray-600">Sí. Puedes modificar tu información personal en cualquier momento desde tu perfil.</p>
            </details>
        </div>
    </div>

    {{-- Sección: Publicaciones --}}
    <div class="questions-container max-w-full mx-auto fondo-gris rounded-lg shadow overflow-hidden mb-10">
        <div class="bg-yellow-400 px-6 py-3">
            <h2 class="text-xl font-semibold text-black text-center">Publicaciones</h2>
        </div>

        <div class="px-6 py-6 max-w-7xl mx-auto space-y-4">
            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Qué puedo publicar en Permuta2?</summary>
                <p class="mt-2 text-gray-600">Puedes ofrecer bienes nuevos o usados que sean de tu propiedad y estén permitidos por la ley chilena.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Se pueden editar o eliminar las publicaciones?</summary>
                <p class="mt-2 text-gray-600">Sí, puedes modificarlas o eliminarlas en cualquier momento.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Qué elementos no se pueden publicar?</summary>
                <p class="mt-2 text-gray-600">Está prohibido publicar objetos ilegales, peligrosos o que infrinjan derechos de terceros.</p>
            </details>
        </div>
    </div>

    {{-- Sección: Permutas --}}
    <div class="questions-container max-w-full mx-auto fondo-gris rounded-lg shadow overflow-hidden mb-10">
        <div class="bg-yellow-400 px-6 py-3">
            <h2 class="text-xl font-semibold text-black text-center">Permutas</h2>
        </div>

        <div class="px-6 py-6 max-w-7xl mx-auto space-y-4">
            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Dónde puedo realizar las permutas?</summary>
                <p class="mt-2 text-gray-600">Puedes acordar con la otra persona un lugar público y seguro.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Cuál es el precio por concretar una permuta?</summary>
                <p class="mt-2 text-gray-600">Permuta2 cobra una comisión del 8 % del valor estimado del producto.</p>
            </details>

            <details class="bg-gray-50 rounded-lg shadow p-4">
                <summary class="cursor-pointer font-semibold">¿Cómo me comunico con otros permutadores?</summary>
                <p class="mt-2 text-gray-600">Mediante un chat privado disponible una vez que haces o recibes una oferta.</p>
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
