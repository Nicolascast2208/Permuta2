@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 flex justify-between items-center" role="alert">
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
            <a 
                href="{{ route('dashboard') }}" 
                class="text-green-700 hover:text-green-900 font-semibold underline text-sm"
            >
                Volver a mi Perfil →
            </a>
        </div>
        @endif

        <form 
            method="POST" 
            action="{{ route('profile.update') }}" 
            enctype="multipart/form-data"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            id="profile-form"
        >
            @csrf
            @method('PUT')

            <!-- Header con fondo azul y foto de perfil -->
            <div class="bg-blue-600 h-32 relative">
                <div class="absolute -bottom-16 left-6">
                    <div class="relative">
                        <img 
                            src="{{ $user->profile_photo_url }}" 
                            alt="{{ $user->alias }}" 
                            class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                            id="profile-preview"
                        >
                        <div class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-md">
                            <label for="profile_photo" class="cursor-pointer">
                                <i class="fas fa-camera text-blue-600"></i>
                                <input 
                                    type="file" 
                                    id="profile_photo" 
                                    name="profile_photo" 
                                    class="hidden"
                                    accept=".jpg,.jpeg,.png"
                                >
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="pt-20 px-6 pb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <div class="flex items-center mt-2">
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($user->rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - $user->rating < 1)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="ml-2 text-gray-600">
                                {{ number_format($user->rating, 1) }} ({{ $user->reviews_count }} reseñas)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Alerta de error para imagen -->
                <div id="image-error-alert" class="hidden mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-700 text-sm" id="image-error-message"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Información personal -->
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700">Información Personal</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo</label>
                            <input 
                                type="text" 
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">RUT</label>
                            <input 
                                type="text" 
                                name="rut"
                                value="{{ old('rut', $user->rut) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 cursor-not-allowed"
                                readonly
                            >
                            <p class="text-xs text-gray-500 mt-1">El RUT no se puede modificar</p>
                            @error('rut') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alias</label>
                            <input 
                                type="text" 
                                name="alias"
                                value="{{ $user->alias }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 cursor-not-allowed"
                                readonly
                            >
                            <p class="text-xs text-gray-500 mt-1">El alias no se puede modificar</p>
                        </div>
                    </div>

                    <!-- Contacto y ubicación -->
                    <div class="bg-gray-50 p-5 rounded-lg">
                        <h2 class="text-lg font-semibold mb-4 text-gray-700">Contacto y Ubicación</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Correo Electrónico</label>
                            <input 
                                type="email" 
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Teléfono</label>
                            <input 
                                type="tel" 
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ubicación</label>
                            <input 
                                type="text" 
                                name="location"
                                value="{{ old('location', $user->location) }}"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                            @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Biografía -->
  <div class="bg-gray-50 p-5 rounded-lg mb-6">
    <h2 class="text-lg font-semibold mb-4 text-gray-700">Sobre Mí</h2>
    <textarea 
        name="bio" 
        rows="4" 
        id="bio-textarea"
        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
        oninput="validateBioInput(this, 'bio-error')"
        placeholder="Cuéntanos sobre ti..."
    >{{ old('bio', $user->bio) }}</textarea>
    @error('bio') 
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
    @enderror
    <div id="bio-error" class="text-red-500 text-sm mt-1 hidden"></div>
    <p class="text-xs text-gray-500 mt-2">
        Por seguridad, no compartas información de contacto (teléfono, email, redes sociales) y no uses el símbolo @.
    </p>
</div>
                <!-- Cambio de contraseña -->
                <div class="bg-gray-50 p-5 rounded-lg">
                    <h2 class="text-lg font-semibold mb-4 text-gray-700">Cambiar Contraseña</h2>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Contraseña Actual</label>
                        <input 
                            type="password" 
                            name="current_password"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                        >
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nueva Contraseña</label>
                            <input 
                                type="password" 
                                name="password"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Confirmar Contraseña</label>
                            <input 
                                type="password" 
                                name="password_confirmation"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white"
                            >
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i> Deja estos campos vacíos si no quieres cambiar tu contraseña.
                    </p>
                </div>
            </div>

            <div class="py-2 flex justify-center">
                <button 
                    type="submit" 
                    class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition"
                    id="submit-btn"
                >
                    Guardar Cambios
                </button>
            </div> 
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('profile_photo');
    const preview = document.getElementById('profile-preview');
    const errorAlert = document.getElementById('image-error-alert');
    const errorMessage = document.getElementById('image-error-message');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('profile-form');

    // Función para mostrar errores
    function showError(message) {
        errorMessage.textContent = message;
        errorAlert.classList.remove('hidden');
        // Scroll to error
        errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Función para ocultar errores
    function hideError() {
        errorAlert.classList.add('hidden');
    }

    // Validar archivo antes de cargarlo
    function validateImageFile(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        // Verificar tipo de archivo
        if (!allowedTypes.includes(file.type)) {
            showError(`Formato no soportado: ${file.name}. Solo se permiten archivos JPG, JPEG y PNG.`);
            return false;
        }

        // Verificar tamaño
        if (file.size > maxSize) {
            showError(`Archivo demasiado grande: ${file.name}. El tamaño máximo es 2MB.`);
            return false;
        }

        hideError();
        return true;
    }

    // Manejar cambio de imagen
    input.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            
            // Validar el archivo
            if (!validateImageFile(file)) {
                // Limpiar el input si el archivo no es válido
                this.value = '';
                return;
            }

            // Cargar previsualización
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                hideError();
            };
            reader.onerror = () => {
                showError('Error al leer el archivo. Por favor, intenta con otra imagen.');
                this.value = '';
            };
            reader.readAsDataURL(file);
        }
    });

    // Validar antes de enviar el formulario
    form.addEventListener('submit', function(e) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (!validateImageFile(file)) {
                e.preventDefault();
                // Enfocar el input de imagen
                input.focus();
            }
        }
    });

    // Prevenir arrastrar y soltar archivos no válidos
    preview.parentElement.addEventListener('dragover', (e) => {
        e.preventDefault();
        preview.parentElement.classList.add('border-blue-400', 'bg-blue-50');
    });

    preview.parentElement.addEventListener('dragleave', (e) => {
        e.preventDefault();
        preview.parentElement.classList.remove('border-blue-400', 'bg-blue-50');
    });

    preview.parentElement.addEventListener('drop', (e) => {
        e.preventDefault();
        preview.parentElement.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (validateImageFile(file)) {
                // Crear un DataTransfer para asignar el archivo al input
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
                
                // Disparar el evento change
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        }
    });
});
</script>
@endsection