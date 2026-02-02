@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    {{-- Términos y condiciones --}}
    <div class="mb-12 p-6 fondo-gris rounded-xl">
        <h3 class="text-2xl font-bold text-gray-800 text-center mb-6">Términos y Condiciones de Uso</h3>
<hr class="mt-8 border-t border-gray-400 pt-6 text-gray-400 text-center text-sm">
        <ol class="list-decimal list-inside space-y-6 text-gray-700">

            <li>
                <strong>Objetivo del servicio:</strong>
                <p>Permuta2 es una plataforma en línea que permite a sus usuarios publicar y encontrar artículos para intercambio o trueque. No somos compradores, vendedores ni intermediarios en las transacciones; solo facilitamos el contacto entre usuarios.</p>
            </li>

            <li>
                <strong>Registro, edad mínima y cuenta:</strong>
                <ul class="list-disc list-inside ml-4">
                    <li>Solo pueden registrarse personas mayores de 18 años.</li>
                    <li>El hecho de contar con una cuenta no es un contrato legal para contratar en Chile conforme al Código Civil.</li>
                    <li>Eres responsable de la veracidad de los datos entregados, de mantener la confidencialidad de tu contraseña y de toda actividad realizada desde tu cuenta.</li>
                    <li>Podemos suspender o eliminar cuentas que incumplan estos Términos.</li>
                </ul>
            </li>

            <li>
                <strong>Publicaciones y comportamiento del usuario:</strong>
                <ul class="list-disc list-inside ml-4">
                    <li>Describir los artículos con información veraz, incluyendo estado y cualquier defecto relevante.</li>
                    <li>No publicar artículos que sean réplicas, falsificaciones, estén con licencias expiradas, productos falsificados o que infrinjan derechos de propiedad intelectual.</li>
                    <li>Mantener un trato respetuoso con otros usuarios y abstenerse de enviar spam, mensajes ofensivos o fraudulentos.</li>
                </ul>
            </li>

            <li>
                <strong>Naturaleza de los intercambios:</strong>
                <ul class="list-disc list-inside ml-4">
                    <li>Los acuerdos se celebran exclusivamente entre usuarios, quienes asumen toda la responsabilidad legal, tributaria y civil derivada del intercambio.</li>
                    <li>Permuta2 no garantiza la satisfacción, pago, seguridad, o entrega de los artículos.</li>
                    <li>Cualquier reclamación, daño o incumplimiento debe resolverse entre las partes.</li>
                </ul>
            </li>

            <li>
                <strong>Servicios de apoyo:</strong>
                <p>Durante nuestra etapa de lanzamiento ofrecemos herramientas y servicios gratuitos para facilitar la comunicación y coordinación de intercambios. Estos pueden cambiar o dejar de estar disponibles en cualquier momento.</p>
            </li>

            <li>
                <strong>Privacidad y datos personales:</strong>
                <p>Respetamos tu privacidad conforme a la Ley N°19.628 sobre Protección de la Vida Privada de Chile. La información que recopilemos se tratará según nuestra Política de Privacidad, parte integral de estos Términos. No compartas datos personales sensibles en publicaciones o mensajes.</p>
            </li>

            <li>
                <strong>Limitación de responsabilidad:</strong>
                <ul class="list-disc list-inside ml-4">
                    <li>Daños o pérdidas derivados de los intercambios entre usuarios.</li>
                    <li>Fallas técnicas, interrupciones del servicio o accesos no autorizados.</li>
                    <li>Contenidos, descripciones o imágenes publicados por usuarios.</li>
                </ul>
            </li>

            <li>
                <strong>Modificaciones de la plataforma:</strong>
                <p>Podemos actualizar o modificar la plataforma, estos Términos y cualquier política relacionada. Los cambios serán efectivos al publicarse en el sitio; el uso continuado implica la aceptación de dichas modificaciones.</p>
            </li>

            <li>
                <strong>Propiedad intelectual:</strong>
                <p>Todos los derechos de la plataforma, marca, diseño, software y contenido original de Permuta2 son propiedad de <a href="https://www.permuta2.cl" class="text-blue-600 underline">www.permuta2.cl</a> y están protegidos por la legislación chilena de propiedad intelectual.</p>
            </li>

            <li>
                <strong>Ley aplicable y jurisdicción:</strong>
                <p>Estos Términos se rigen por las leyes de la República de Chile. Cualquier controversia será resuelta por los tribunales competentes correspondientes.</p>
            </li>

        </ol>
    </div>

</div>
@endsection
