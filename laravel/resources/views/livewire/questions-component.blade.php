<div class="questions-container max-w-full mx-auto bg-white rounded-lg shadow overflow-hidden border border-yellow-200 scroll-mt-20" id="preguntas">

    <!-- Header amarillo -->
    <div class="bg-yellow-300 px-6 py-3">
        <div class="max-w-7xl mx-auto flex justify-start">
            <h1 class="text-xl font-semibold text-black text-left">Preguntas y respuestas</h1>
        </div>
    </div>

    <!-- Barra gris (input + botón) -->
    <div class="bg-gray-100 px-6 py-4">
        @auth
            @if(auth()->id() !== $product->user_id)
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="flex-1">
                            <textarea wire:model.defer="newQuestion"
                                      wire:keydown.enter.prevent="$set('newQuestion', $event.target.value)"
                                      rows="1"
                                      id="question-input"
                                      class="w-full resize-none px-4 py-3 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 @error('newQuestion') border-red-500 @enderror"
                                      placeholder="Escribe tu pregunta..."
                                      oninput="validateQuestionInput(this, 'question-error')"></textarea>
                            @error('newQuestion')
                                <div id="question-error" class="text-red-500 text-sm mt-1 pl-3">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="question-error" class="text-red-500 text-sm mt-1 pl-3 hidden"></div>
                        </div>

                        <button wire:click="askQuestion"
                                wire:loading.attr="disabled"
                                wire:target="askQuestion"
                                class="flex items-center justify-center gap-2 px-5 py-2 rounded-full bg-yellow-300 text-black font-semibold hover:bg-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="askQuestion">Preguntar</span>
                            <span wire:loading wire:target="askQuestion">Enviando...</span>
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 class="h-5 w-5" 
                                 fill="none" 
                                 viewBox="0 0 24 24" 
                                 stroke="currentColor">
                                <path stroke-linecap="round" 
                                      stroke-linejoin="round" 
                                      stroke-width="2" 
                                      d="M22 2L11 13M22 2L15 22L11 13L2 9L22 2Z" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 pl-2">
                        Por tu seguridad, no compartas información de contacto (teléfono, email, redes sociales).
                    </p>
                </div>
            @else
                <div class="max-w-7xl mx-auto text-sm text-gray-600">
                    Los propietarios no pueden preguntar sobre su propio producto.
                </div>
            @endif
        @else
            <div class="max-w-7xl mx-auto text-center">
                <p class="text-gray-700">
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Inicia sesión</a>
                    para hacer preguntas sobre este producto
                </p>
            </div>
        @endauth
    </div>

    <!-- Lista de preguntas -->
    <div class="px-6 py-6 max-w-7xl mx-auto">
        <div class="questions-list space-y-6">
            @forelse ($questions as $question)
                <div class="question bg-white rounded-lg border border-gray-100 shadow-sm">
                    <div class="px-5 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <a href="{{ route('user.profile', $question->user->id) }}">
                                        <img src="{{ $question->user->profile_photo_url }}" 
                                             alt="{{ $question->user->alias }}" 
                                             class="w-9 h-9 rounded-full mr-3 object-cover hover:opacity-80 transition">
                                    </a>
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('user.profile', $question->user->id) }}" 
                                               class="font-medium text-gray-800 hover:text-blue-600 transition">
                                                {{ $question->user->name }}
                                            </a>
                                            <span class="text-xs text-gray-400">{{ $question->created_at->format('d - m - Y') }}</span>
                                        </div>
                                        <p class="text-gray-700 mt-2">{{ $question->content }}</p>
                                    </div>
                                </div>

                                @if($question->answers->isNotEmpty())
                                    <div class="mt-3 pl-12 border-l-2 border-gray-100">
                                        @foreach ($question->answers as $answer)
                                            <div class="mb-4">
                                                <div class="flex items-center mb-1">
                                                    <a href="{{ route('user.profile', $answer->user->id) }}">
                                                        <img src="{{ $answer->user->profile_photo_url }}" 
                                                             alt="{{ $answer->user->alias }}" 
                                                             class="w-8 h-8 rounded-full mr-3 object-cover hover:opacity-80 transition">
                                                    </a>
                                                    <a href="{{ route('user.profile', $answer->user->id) }}">      
                                                        <span class="font-medium text-gray-800">{{ $answer->user->name }}</span>
                                                    </a>
                                                    <span class="text-xs text-gray-400 ml-2">{{ $answer->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-gray-700 ml-11">{{ $answer->content }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Botón responder (solo si eres propietario y no hay respuestas) -->
                            <div class="ml-4">
                                @if(auth()->id() === $product->user_id && $question->answers->isEmpty())
                                    <button wire:click="showAnswerForm({{ $question->id }})"
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                        Responder
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Formulario de respuesta (si está activo) -->
                        @if($activeQuestionId === $question->id)
                            <div class="mt-4 pl-12">
                                <div>
                                    <textarea wire:model.defer="answerContent"
                                              wire:keydown.enter.prevent="$set('answerContent', $event.target.value)"
                                              id="answer-input-{{ $question->id }}"
                                              rows="3"
                                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 @error('answerContent') border-red-500 @enderror"
                                              placeholder="Escribe tu respuesta..."
                                              oninput="validateAnswerInput(this, 'answer-error-{{ $question->id }}')"></textarea>
                                    @error('answerContent')
                                        <div id="answer-error-{{ $question->id }}" class="text-red-500 text-sm mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div id="answer-error-{{ $question->id }}" class="text-red-500 text-sm mt-1 hidden"></div>
                                </div>

                                <div class="mt-2 flex justify-end gap-2">
                                    <button wire:click="cancelAnswer"
                                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                        Cancelar
                                    </button>
                                    <button wire:click="answerQuestion({{ $question->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="answerQuestion"
                                            class="px-4 py-2 bg-yellow-400 text-black font-semibold rounded hover:bg-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span wire:loading.remove wire:target="answerQuestion">Enviar respuesta</span>
                                        <span wire:loading wire:target="answerQuestion">Enviando...</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-600 text-center py-6">No hay preguntas sobre este producto</p>
            @endforelse
        </div>

        <!-- Ver más preguntas -->
        @if($questions->count() > 3)
            <div class="mt-6 text-right">
                <a href="#" class="text-sm font-medium text-gray-700 hover:text-black">Ver más preguntas</a>
            </div>
        @endif
    </div>
</div>

<script>
// Lista de palabras prohibidas (versión completa)
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
    
    // Frases y patrones (como palabras separadas)
    'escríbelo', 'separado', 'espacios', 'símbolos', 'palabras',
    'interno', 'datos', 'transferirte', 'dame', 'fuera', 'app', 'página',
    'afuera', 'mejor', 'medio', 'otro', 'lado', 'por'
];

// Variantes comunes (sin espacios, con puntos, etc.)
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

// Función mejorada para validar entrada en tiempo real
function validateQuestionInput(textarea, errorElementId) {
    const value = textarea.value;
    const errorElement = document.getElementById(errorElementId);
    
    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
    if (value.includes('@')) {
        errorElement.textContent = 'No puedes usar el símbolo @ en las preguntas.';
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
    
    let errorMessage = '';
    
    // Validar teléfonos
    const phonePattern = /(\+?\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;
    
    // Validar URLs de redes sociales
    const socialPattern = /(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat|youtube|discord|reddit|pinterest)\.(com|org|net|io|me|[a-z]{2,})/i;
    
    // Lista de dominios de redes sociales
    const socialDomains = [
        'facebook', 'twitter', 'instagram', 'whatsapp',
        'tiktok', 'telegram', 'snapchat', 'linkedin',
        'youtube', 'discord', 'reddit', 'pinterest'
    ];
    
    // Palabras clave de contacto
    const contactKeywords = [
        'contacto', 'contáctame', 'escríbeme', 'llámame', 
        'whatsapp', 'telegram', 'instagram', 'facebook',
        'twitter', 'snapchat', 'tiktok', 'red social'
    ];
    
    // Validación en cascada
    if (phonePattern.test(value)) {
        errorMessage = 'No puedes enviar números de teléfono.';
        textarea.classList.add('border-red-500');
    } else if (socialPattern.test(value)) {
        errorMessage = 'No puedes enviar enlaces a redes sociales.';
        textarea.classList.add('border-red-500');
    } else {
        // Detectar dominios de redes sociales sin URL completa
        const lowerValue = value.toLowerCase();
        let foundSocialDomain = false;
        
        for (const domain of socialDomains) {
            if (lowerValue.includes(domain)) {
                if (value.match(/\d|http|\.com|\.net|\.org/i)) {
                    foundSocialDomain = true;
                    break;
                }
            }
        }
        
        if (foundSocialDomain) {
            errorMessage = 'No puedes compartir información de redes sociales.';
            textarea.classList.add('border-red-500');
        } else {
            // Detectar palabras clave de contacto combinadas con símbolos
            for (const keyword of contactKeywords) {
                if (lowerValue.includes(keyword)) {
                    const hasHttp = value.includes('http://') || value.includes('https://');
                    const hasDotCom = value.includes('.com') || value.includes('.net') || value.includes('.org');
                    const hasNumbers = /\d/.test(value);
                    
                    if (hasHttp || hasDotCom || hasNumbers) {
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
    }
    
    // Mostrar u ocultar mensaje de error
    if (errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

// Función similar para respuestas
function validateAnswerInput(textarea, errorElementId) {
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
    
    let errorMessage = '';
    
    // Validar teléfonos
    const phonePattern = /(\+?\d{1,3}[-.\s]?)?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/;
    
    // Validar URLs de redes sociales
    const socialPattern = /(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i;
    
    const contactKeywords = ['contacto', 'contáctame', 'escríbeme', 'llámame', 'whatsapp', 'telegram'];
    const lowerValue = value.toLowerCase();
    
    if (phonePattern.test(value)) {
        errorMessage = 'No puedes enviar números de teléfono.';
        textarea.classList.add('border-red-500');
    } else if (socialPattern.test(value)) {
        errorMessage = 'No puedes enviar enlaces a redes sociales.';
        textarea.classList.add('border-red-500');
    } else {
        for (const keyword of contactKeywords) {
            if (lowerValue.includes(keyword)) {
                const hasHttp = value.includes('http') || value.includes('www.');
                const hasNumbers = /\d/.test(value);
                
                if (hasHttp || hasNumbers) {
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
    
    if (errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

// Validación adicional antes de enviar
document.addEventListener('DOMContentLoaded', function() {
    // Para preguntas
    const questionForm = document.querySelector('button[wire\\:click="askQuestion"]');
    if (questionForm) {
        questionForm.addEventListener('click', function(e) {
            const textarea = document.getElementById('question-input');
            const errorElement = document.getElementById('question-error');
            validateQuestionInput(textarea, 'question-error');
            
            if (errorElement && !errorElement.classList.contains('hidden')) {
                e.preventDefault();
                e.stopPropagation();
                textarea.focus();
            }
        });
    }
});

</script>