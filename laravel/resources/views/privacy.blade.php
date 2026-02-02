@extends('layouts.app')

@section('content')
<div class="container mx-auto">

    {{-- Términos y condiciones --}}
    <div class="mb-12 p-6 fondo-gris rounded-xl">
        <h3 class="text-2xl font-bold text-gray-800 text-center mb-6">Política de Privacidad</h3>
<hr class="mt-8 border-t border-gray-400 pt-6 text-gray-400 text-center text-sm">
        <p class="mb-4 text-gray-700">
            En <strong>Permuta2</strong> valoramos tu confianza y protegemos tu información personal conforme a la Ley N°19.628 sobre Protección de la Vida Privada y demás normativa chilena aplicable. Esta política explica cómo recopilamos, usamos y protegemos tus datos cuando utilizas nuestra plataforma.
        </p>

        <ol class="list-decimal list-inside space-y-4 text-gray-700">
            <li>
                <strong>Información que recopilamos:</strong> Podemos solicitar y almacenar:
                <ul class="list-disc list-inside ml-4">
                    <li>Datos de registro: nombre, correo electrónico, contraseña y cualquier dato que entregues al crear tu cuenta.</li>
                    <li>Información de uso: registros de actividad, publicaciones, búsquedas e interacciones dentro de la plataforma.</li>
                    <li>Datos técnicos: dirección IP, tipo de navegador, sistema operativo y cookies necesarias para el funcionamiento del sitio.</li>
                </ul>
            </li>

            <li>
                <strong>Uso de la información:</strong> Utilizamos tus datos para:
                <ul class="list-disc list-inside ml-4">
                    <li>Crear y administrar tu cuenta.</li>
                    <li>Facilitar la publicación de artículos y el contacto entre usuarios.</li>
                    <li>Enviar notificaciones sobre actividad o cambios en nuestros servicios.</li>
                    <li>Mejorar la experiencia en el sitio y prevenir fraudes o usos indebidos.</li>
                    <li>Nunca venderemos ni arrendaremos tu información a terceros.</li>
                </ul>
            </li>

            <li>
                <strong>Compartir datos con terceros:</strong> Podemos compartir datos solo en los siguientes casos:
                <ul class="list-disc list-inside ml-4">
                    <li>Con proveedores de servicios tecnológicos que nos ayudan a operar la plataforma (alojamiento, análisis, mensajería), bajo estrictos acuerdos de confidencialidad.</li>
                    <li>Cuando lo exija la ley chilena, una orden judicial o para proteger derechos, propiedad o seguridad de Permuta2 o de otros usuarios.</li>
                </ul>
            </li>

            <li>
                <strong>Cookies y tecnologías similares:</strong> Usamos cookies esenciales y analíticas para:
                <ul class="list-disc list-inside ml-4">
                    <li>Mantener tu sesión iniciada.</li>
                    <li>Recordar preferencias.</li>
                    <li>Analizar el tráfico y mejorar el servicio.</li>
                </ul>
                Puedes configurar tu navegador para rechazar cookies, pero algunas funciones podrían verse limitadas.
            </li>

            <li>
                <strong>Derechos de los usuarios:</strong> De acuerdo con la Ley N°19.628, puedes:
                <ul class="list-disc list-inside ml-4">
                    <li>Acceder a tus datos personales.</li>
                    <li>Rectificarlos, actualizarlos o eliminar tu cuenta.</li>
                    <li>Solicitar información sobre el uso de tus datos.</li>
                </ul>
            </li>

            <li>
                <strong>Seguridad de la información:</strong> Adoptamos medidas técnicas y administrativas razonables para proteger tu información. Sin embargo, ninguna transmisión de datos por Internet es 100 % segura; utilizas el servicio bajo tu propia responsabilidad.
            </li>

            <li>
                <strong>Menores de edad:</strong> El uso de Permuta2 está limitado a personas mayores de 18 años. No recopilamos de manera intencional datos de menores. Si identificamos información de un menor, la eliminaremos.
            </li>

            <li>
                <strong>Cambios en la política:</strong> Podemos actualizar esta Política para reflejar cambios legales o de funcionamiento. Publicaremos la versión más reciente en esta misma página con su fecha de vigencia.
            </li>
        </ol>
    </div>

</div>
@endsection
