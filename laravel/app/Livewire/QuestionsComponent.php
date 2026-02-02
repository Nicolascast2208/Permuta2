<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Question;
use App\Models\Notification;
use Livewire\Component;

class QuestionsComponent extends Component
{
    public $product;
    public $newQuestion = '';
    public $questions;
    public $answerContent = '';
    public $activeQuestionId = null;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->loadQuestions();
    }

    public function loadQuestions()
    {
        $this->questions = $this->product->questions()
            ->whereNull('parent_id')
            ->with(['user', 'answers.user'])
            ->get();
    }

    /**
     * Verifica si un texto contiene palabras prohibidas
     */
    private function containsForbiddenWords($text)
    {
        $text = mb_strtolower($text);
        
        // Normalizar texto: quitar acentos y caracteres especiales
        $normalizedText = $this->normalizeText($text);
        
        // Obtener palabras prohibidas de la configuración
        $forbiddenWords = config('forbidden_words.words', []);
        $forbiddenPhrases = config('forbidden_words.phrases', []);
        
        // Verificar palabras individuales
        foreach ($forbiddenWords as $word) {
            $normalizedWord = $this->normalizeText($word);
            
            // Verificar como palabra completa (con límites de palabra)
            if (preg_match('/\b' . preg_quote($normalizedWord, '/') . '\b/i', $normalizedText)) {
                return true;
            }
            
            // Verificar también sin límites de palabra para variantes
            if (str_contains($normalizedText, $normalizedWord)) {
                return true;
            }
        }
        
        // Verificar frases completas
        foreach ($forbiddenPhrases as $phrase) {
            $normalizedPhrase = $this->normalizeText($phrase);
            if (str_contains($normalizedText, $normalizedPhrase)) {
                return true;
            }
        }
        
        // Verificar variantes comunes
        $variants = [
            'gmail' => ['g m a i l', 'g.mail', 'g-mail', 'g*mail'],
            'whatsapp' => ['wasap', 'guasap', 'uatza', 'wsp', 'whats app'],
            'instagram' => ['insta', 'ig', 'insta gram', 'insta.gram', 'insta_gram'],
            'facebook' => ['fb', 'face', 'face book'],
            'telefono' => ['fono', 'tel', 'tele fono'],
            'correo' => ['mail', 'e-mail', 'email'],
        ];
        
        foreach ($variants as $baseWord => $wordVariants) {
            foreach ($wordVariants as $variant) {
                $normalizedVariant = $this->normalizeText($variant);
                if (str_contains($normalizedText, $normalizedVariant)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Normaliza texto para comparación (quita acentos, espacios extras, etc.)
     */
    private function normalizeText($text)
    {
        // Convertir a minúsculas
        $text = mb_strtolower($text);
        
        // Quitar acentos
        $text = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $text
        );
        
        // Reemplazar múltiples espacios por uno solo
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }

    public function askQuestion()
    {
        $this->validate([
            'newQuestion' => [
                'required',
                'string',
                'max:500',
                'min:3',
                function ($attribute, $value, $fail) {
                    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
                    if (strpos($value, '@') !== false) {
                        $fail('No puedes usar el símbolo @ en las preguntas.');
                        return;
                    }
                    
                    // Verificar palabras prohibidas
                    if ($this->containsForbiddenWords($value)) {
                        $fail('El contenido contiene palabras o frases no permitidas (información de contacto, redes sociales, etc.).');
                        return;
                    }
                    
                    // Eliminar espacios para detectar correos con espacios
                    $cleanText = preg_replace('/\s+/', '', $value);
                    
                    // Obtener patrones de configuración
                    $patterns = config('forbidden_words.patterns', []);
                    
                    // Validación en cascada usando patrones de configuración
                    if (isset($patterns['email_pattern']) && preg_match($patterns['email_pattern'], $cleanText)) {
                        $fail('No puedes enviar direcciones de correo electrónico en las preguntas.');
                        return;
                    }
                    
                    if (isset($patterns['phone_pattern']) && preg_match($patterns['phone_pattern'], $value)) {
                        $fail('No puedes enviar números de teléfono en las preguntas.');
                        return;
                    }
                    
                    if (isset($patterns['social_pattern']) && preg_match($patterns['social_pattern'], $value)) {
                        $fail('No puedes enviar enlaces a redes sociales en las preguntas.');
                        return;
                    }
                    
                    if (isset($patterns['whatsapp_pattern']) && preg_match($patterns['whatsapp_pattern'], $value)) {
                        $fail('No puedes compartir información de WhatsApp en las preguntas.');
                        return;
                    }
                    
                    // Lista de dominios de redes sociales para detección sin URL completa
                    $socialDomains = [
                        'facebook', 'twitter', 'instagram', 'whatsapp',
                        'tiktok', 'telegram', 'snapchat', 'linkedin',
                        'youtube', 'discord', 'reddit', 'pinterest'
                    ];
                    
                    // Detectar dominios de redes sociales sin URL completa
                    $lowerValue = strtolower($value);
                    foreach ($socialDomains as $domain) {
                        if (strpos($lowerValue, $domain) !== false) {
                            // Verificar si está acompañado de caracteres de contacto
                            if (preg_match('/\d|http|\.com|\.net|\.org/i', $value)) {
                                $fail('No puedes compartir información de redes sociales en las preguntas.');
                                return;
                            }
                        }
                    }
                    
                    // Detectar palabras clave que pueden indicar intento de contacto
                    $contactKeywords = [
                        'contacto', 'contáctame', 'escríbeme', 'llámame', 
                        'whatsapp', 'telegram', 'instagram', 'facebook',
                        'twitter', 'snapchat', 'tiktok', 'red social'
                    ];
                    
                    foreach ($contactKeywords as $keyword) {
                        if (strpos($lowerValue, $keyword) !== false) {
                            // Si la palabra clave está combinada con símbolos de contacto
                            $hasHttp = stripos($value, 'http://') !== false || stripos($value, 'https://') !== false;
                            $hasDotCom = stripos($value, '.com') !== false || stripos($value, '.net') !== false || stripos($value, '.org') !== false;
                            $hasNumbers = preg_match('/\d/', $value);
                            
                            if ($hasHttp || $hasDotCom || $hasNumbers) {
                                $fail('No puedes solicitar contacto directo en las preguntas.');
                                return;
                            }
                        }
                    }
                },
            ],
        ]);

        $question = $this->product->questions()->create([
            'user_id' => auth()->id(),
            'content' => $this->newQuestion
        ]);

        // Crear notificación para el dueño del producto (si no es el mismo que pregunta)
        if (auth()->id() !== $this->product->user_id) {
            Notification::create([
                'user_id' => $this->product->user_id,
                'type' => 'product_question',
                'notifiable_id' => $question->id,
                'notifiable_type' => Question::class,
                'message' => auth()->user()->name . ' ha hecho una pregunta sobre tu producto "' . $this->product->title . '"',
                'read' => false,
                'data' => [
                    'product_id' => $this->product->id,
                    'product_title' => $this->product->title
                ]
            ]);
        }

        $this->newQuestion = '';
        $this->loadQuestions();
        $this->dispatch('notify', 'Pregunta enviada correctamente');
    }

    public function showAnswerForm($questionId)
    {
        $this->activeQuestionId = $questionId;
    }

    public function cancelAnswer()
    {
        $this->activeQuestionId = null;
        $this->answerContent = '';
    }

    public function answerQuestion($questionId)
    {
        $this->validate([
            'answerContent' => [
                'required',
                'string',
                'max:500',
                'min:3',
                function ($attribute, $value, $fail) {
                    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
                    if (strpos($value, '@') !== false) {
                        $fail('No puedes usar el símbolo @ en las respuestas.');
                        return;
                    }
                    
                    // Verificar palabras prohibidas
                    if ($this->containsForbiddenWords($value)) {
                        $fail('El contenido contiene palabras o frases no permitidas (información de contacto, redes sociales, etc.).');
                        return;
                    }
                    
                    // Eliminar espacios para detectar correos con espacios
                    $cleanText = preg_replace('/\s+/', '', $value);
                    
                    // Obtener patrones de configuración
                    $patterns = config('forbidden_words.patterns', []);
                    
                    // Validación en cascada usando patrones de configuración
                    if (isset($patterns['email_pattern']) && preg_match($patterns['email_pattern'], $cleanText)) {
                        $fail('No puedes enviar direcciones de correo electrónico en las respuestas.');
                        return;
                    }
                    
                    if (isset($patterns['phone_pattern']) && preg_match($patterns['phone_pattern'], $value)) {
                        $fail('No puedes enviar números de teléfono en las respuestas.');
                        return;
                    }
                    
                    if (isset($patterns['social_pattern']) && preg_match($patterns['social_pattern'], $value)) {
                        $fail('No puedes enviar enlaces a redes sociales en las respuestas.');
                        return;
                    }
                    
                    if (isset($patterns['whatsapp_pattern']) && preg_match($patterns['whatsapp_pattern'], $value)) {
                        $fail('No puedes compartir información de WhatsApp en las respuestas.');
                        return;
                    }
                    
                    // Detectar palabras clave que pueden indicar intento de contacto
                    $contactKeywords = ['contacto', 'contáctame', 'escríbeme', 'llámame', 'whatsapp', 'telegram'];
                    $lowerValue = strtolower($value);
                    
                    foreach ($contactKeywords as $keyword) {
                        if (strpos($lowerValue, $keyword) !== false) {
                            // Verificar si está combinada con símbolos de contacto
                            $hasHttp = stripos($value, 'http') !== false;
                            $hasNumbers = preg_match('/\d/', $value);
                            
                            if ($hasHttp || $hasNumbers) {
                                $fail('No puedes solicitar contacto directo en las respuestas.');
                                return;
                            }
                        }
                    }
                },
            ],
        ]);

        $originalQuestion = Question::find($questionId);

        $answer = $this->product->questions()->create([
            'user_id' => auth()->id(),
            'parent_id' => $questionId,
            'content' => $this->answerContent
        ]);

        // Crear notificación para el usuario que hizo la pregunta original
        if (auth()->id() !== $originalQuestion->user_id) {
            Notification::create([
                'user_id' => $originalQuestion->user_id,
                'type' => 'question_answered',
                'notifiable_id' => $answer->id,
                'notifiable_type' => Question::class,
                'message' => auth()->user()->name . ' ha respondido tu pregunta sobre el producto "' . $this->product->title . '"',
                'read' => false,
                'data' => [
                    'product_id' => $this->product->id,
                    'product_title' => $this->product->title
                ]
            ]);
        }

        $this->answerContent = '';
        $this->activeQuestionId = null;
        $this->loadQuestions();
        $this->dispatch('notify', 'Respuesta enviada correctamente');
    }

    public function render()
    {
        return view('livewire.questions-component');
    }
}