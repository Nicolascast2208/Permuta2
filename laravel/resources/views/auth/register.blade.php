@extends('layouts.app')

@section('content')
    @auth
        <script>window.location = "{{ route('dashboard') }}";</script>
    @else
        <!-- Encabezado -->
    <div class="col-span-12 rounded-lg px-6 py-10 mb-4 fondo-personalizado">
        <h2 class="text-3xl font-bold">CREAR TU CUENTA</h2>
        <p class="mt-2 text-sm">Únete a nuestra comunidad y comienza a intercambiar productos de forma fácil y segura.</p>
    </div>
    <div class="flex flex-col items-center justify-start py-5 px-4 sm:px-6 lg:px-2 bg-gray-50 rounded-xl">


        <!-- Contenedor del Formulario -->
        <div class="max-w-md w-full">
            @if($errors->any())
                <div class="rounded-xl bg-red-50 p-4 mb-6 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Revisa los siguientes errores:</h3>
                            <ul class="mt-2 text-sm text-red-700 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-start">
                                        <span class="mr-2">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 space-y-6" action="{{ route('register') }}" method="POST" enctype="multipart/form-data" id="registerForm">
                @csrf

                <!-- Nombre Completo -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <div class="relative">
                        <input id="name" name="name" type="text" required autofocus
                               class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                               placeholder="Ej: Juan Pérez"
                               value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RUT -->
                <div>
                    <label for="rut" class="block text-sm font-medium text-gray-700 mb-1">RUT</label>
                    <div class="relative">
                        <input id="rut" name="rut" type="text" required
                               class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                               placeholder="12.345.678-9"
                               value="{{ old('rut') }}"
                               oninput="formatearRUT(this)"
                               onblur="validarRUT(this)">
                    </div>
                    <p id="rut-error" class="mt-1 text-sm text-red-600 hidden">El RUT ingresado no es válido.</p>
                    @error('rut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <div class="relative">
                        <input id="email" name="email" type="email" required
                               class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                               placeholder="ejemplo@dominio.com"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <div class="relative">
                        <input id="password" name="password" type="password" required
                               class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                               placeholder="••••••••">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword('password')">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="mt-1 block w-full px-3 py-2.5 bg-white border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                               placeholder="••••••••">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePassword('password_confirmation')">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Botón de Registro -->
                <div class="pt-4">
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Crear Cuenta
                    </button>
                </div>

                <!-- Enlace a Inicio de Sesión -->
                <div class="text-center pt-4">
                    <p class="text-gray-600 text-sm">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Inicia Sesión
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatearRUT(input) {
            // Remover todo excepto números y K
            let rut = input.value.replace(/[^0-9kK]/g, '').toUpperCase();
            
            if (rut.length > 1) {
                // Separar el cuerpo del dígito verificador
                let cuerpo = rut.slice(0, -1);
                let dv = rut.slice(-1);
                
                // Formatear el cuerpo con puntos
                cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                
                // Unir cuerpo y DV con guión
                input.value = cuerpo + '-' + dv;
            }
        }

        function validarRUT(input) {
            const rutError = document.getElementById('rut-error');
            const rut = input.value.replace(/\./g, '').replace(/-/g, '').toUpperCase();
            
            // Ocultar mensaje de error al inicio
            rutError.classList.add('hidden');
            input.classList.remove('border-red-500', 'border-green-500');
            
            if (!rut || rut.length < 2) {
                return false;
            }

            // Separar cuerpo y DV
            const cuerpo = rut.slice(0, -1);
            const dv = rut.slice(-1);

            // Validar que el cuerpo sea numérico
            if (!/^\d+$/.test(cuerpo)) {
                rutError.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }

            // Calcular DV correcto
            let suma = 0;
            let multiplo = 2;

            // Recorrer el RUT de derecha a izquierda
            for (let i = cuerpo.length - 1; i >= 0; i--) {
                suma += parseInt(cuerpo.charAt(i)) * multiplo;
                multiplo = multiplo < 7 ? multiplo + 1 : 2;
            }

            // Calcular dígito verificador
            const dvEsperado = 11 - (suma % 11);
            let dvCalculado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();

            // Validar
            if (dvCalculado === dv) {
                input.classList.add('border-green-500');
                return true;
            } else {
                rutError.classList.remove('hidden');
                input.classList.add('border-red-500');
                return false;
            }
        }

        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const toggleIcon = passwordField.parentNode.querySelector('svg');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                // Cambiar el icono a ojo tachado
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                passwordField.type = 'password';
                // Volver al icono de ojo normal
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        // Validar formulario antes de enviar
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const rutInput = document.getElementById('rut');
            if (!validarRUT(rutInput)) {
                e.preventDefault();
                rutInput.focus();
            }
        });
    </script>
    @endauth
@endsection