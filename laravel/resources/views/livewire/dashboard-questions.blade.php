<div class="container mx-auto px-4 py-8 fondo-gris rounded-xl">
    <div class="mb-2">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Responde preguntas</h1>
        <p class="text-gray-600">Administra tus preguntas activas y pendientes</p>
    </div>
    
    <div>
        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if ($questions->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-600">No tienes preguntas sobre tus productos</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow divide-y divide-gray-200">
                @foreach ($questions as $question)
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-600 hover:underline">
                                    <a href="{{ route('products.show', $question->product) }}">
                                        {{ $question->product->title }}
                                    </a>
                                </h3>

                                <p class="mt-2 text-gray-700">
                                    <span class="font-medium">{{ $question->user->name }}:</span>
                                    {{ $question->content }}
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $question->created_at->diffForHumans() }}
                                </p>
                            </div>

                            @if ($question->replies->isEmpty())
                                <button wire:click="showAnswerForm({{ $question->id }})"
                                        class="text-sm text-blue-600 hover:text-blue-800 transition">
                                    Responder
                                </button>
                            @endif
                        </div>

                        {{-- Respuestas --}}
                        @if ($question->replies->isNotEmpty())
                            <div class="mt-4 pl-6 border-l-2 border-gray-200 space-y-3">
                                @foreach ($question->replies as $reply)
                                    <div>
                                        <p class="text-gray-700">
                                            <span class="font-medium">{{ $reply->user->name }}:</span>
                                            {{ $reply->content }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $reply->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Formulario de respuesta --}}
                        @if ($activeQuestionId === $question->id)
                            <div class="mt-6 pl-6">
                                <textarea wire:model="answerContent"
                                          id="answer-input-{{ $question->id }}"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('answerContent') border-red-500 @enderror"
                                          rows="3"
                                          placeholder="Escribe tu respuesta..."
                                          oninput="validateDashboardAnswerInput(this, 'answer-error-{{ $question->id }}')"></textarea>
                                
                                <!-- Mensaje de error del backend -->
                                @error('answerContent')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                
                                <!-- Mensaje de error del frontend -->
                                <div id="answer-error-{{ $question->id }}" class="text-red-500 text-sm mt-1 hidden"></div>
                                
                                <!-- Mensaje de seguridad -->
                                <p class="text-xs text-gray-500 mt-2">
                                    Por seguridad, no compartas información de contacto (teléfono, email, redes sociales, usuarios con @).
                                </p>
                                
                                <div class="mt-3 flex justify-end gap-2">
                                    <button wire:click="cancelAnswer"
                                            class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                                        Cancelar
                                    </button>
                                    <button wire:click="submitAnswer({{ $question->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="submitAnswer"
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="submitAnswer">Enviar respuesta</span>
                                        <span wire:loading wire:target="submitAnswer">Enviando...</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $questions->links() }}
            </div>
        @endif
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

// Función mejorada para validar respuesta en tiempo real (versión dashboard)
function validateDashboardAnswerInput(textarea, errorElementId) {
    const value = textarea.value;
    const errorElement = document.getElementById(errorElementId);
    
    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
    if (value.includes('@')) {
        errorElement.textContent = 'No puedes usar el símbolo @ en las respuestas.';
        errorElement.classList.remove('hidden');
        textarea.classList.add('border-red-500');
        return;
    }
    
    // Validar palabras prohibidas
    const forbiddenError = checkForbiddenWords(value);
    if (forbiddenError) {
        errorElement.textContent = forbiddenError;
        errorElement.classList.remove('hidden');
        textarea.classList.add('border-red-500');
        return;
    }
    
    // Quitar espacios para validar emails
    const cleanValue = value.replace(/\s+/g, '');
    
    // Expresiones regulares mejoradas
    const emailPattern = /[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/i;
    const phonePattern = /(\+?\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;
    const socialPattern = /(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i;
    const whatsappPattern = /whatsapp\s*[:.]?\s*\+?\d{1,3}[-.\s]?\d{1,4}[-.\s]?\d{1,9}/i;
    
    // Lista de dominios de redes sociales para detección sin URL completa
    const socialDomains = ['facebook', 'twitter', 'instagram', 'whatsapp', 'tiktok', 'telegram', 'snapchat', 'linkedin', 'youtube', 'discord', 'reddit', 'pinterest'];
    
    let errorMessage = '';
    
    // Validación en cascada
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
        for (const domain of socialDomains) {
            if (lowerValue.includes(domain)) {
                // Verificar si está acompañado de caracteres de contacto
                if (/\d/.test(value) || lowerValue.includes('http') || lowerValue.includes('.com') || lowerValue.includes('.net') || lowerValue.includes('.org')) {
                    errorMessage = 'No puedes compartir información de redes sociales.';
                    textarea.classList.add('border-red-500');
                    break;
                }
            }
        }
        
        if (!errorMessage) {
            // Verificar palabras clave de contacto combinadas con símbolos de contacto
            const contactKeywords = ['contacto', 'contáctame', 'escríbeme', 'llámame', 'whatsapp', 'telegram', 'instagram', 'facebook', 'twitter', 'snapchat', 'tiktok', 'red social'];
            const hasHttp = lowerValue.includes('http://') || lowerValue.includes('https://');
            const hasDotCom = lowerValue.includes('.com') || lowerValue.includes('.net') || lowerValue.includes('.org');
            const hasNumbers = /\d/.test(value);
            
            for (const keyword of contactKeywords) {
                if (lowerValue.includes(keyword) && (hasHttp || hasDotCom || hasNumbers)) {
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
    
    // Mostrar u ocultar mensaje de error
    if (errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

// Validación adicional antes de enviar respuestas
document.addEventListener('DOMContentLoaded', function() {
    // Agregar event listeners a todos los botones de respuesta
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.textContent === 'Enviar respuesta' || 
                         (e.target.tagName === 'SPAN' && e.target.textContent === 'Enviar respuesta'))) {
            // Encontrar el textarea más cercano
            let button = e.target;
            if (button.tagName === 'SPAN') {
                button = button.closest('button');
            }
            
            const formDiv = button.closest('div.mt-6.pl-6');
            if (formDiv) {
                const textarea = formDiv.querySelector('textarea');
                if (textarea) {
                    // Obtener el ID del error del textarea
                    const textareaId = textarea.id;
                    const questionId = textareaId.replace('answer-input-', '');
                    const errorElement = document.getElementById(`answer-error-${questionId}`);
                    
                    // Validar el input
                    validateDashboardAnswerInput(textarea, `answer-error-${questionId}`);
                    
                    // Si hay error, prevenir el envío
                    if (errorElement && !errorElement.classList.contains('hidden')) {
                        e.preventDefault();
                        e.stopPropagation();
                        textarea.focus();
                        
                        // En el caso de Livewire, necesitamos prevenir el evento de wire:click
                        button.setAttribute('data-validation-error', 'true');
                        return false;
                    }
                }
            }
        }
    });
});

// Validar mientras se escribe
document.addEventListener('input', function(e) {
    if (e.target && e.target.tagName === 'TEXTAREA' && e.target.id.startsWith('answer-input-')) {
        const textarea = e.target;
        const questionId = textarea.id.replace('answer-input-', '');
        validateDashboardAnswerInput(textarea, `answer-error-${questionId}`);
    }
});
</script>