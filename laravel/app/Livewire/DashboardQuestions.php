<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Question;

class DashboardQuestions extends Component
{
    use WithPagination;
    
    public $activeQuestionId = null;
    public $answerContent = '';

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

    public function showAnswerForm($questionId)
    {
        $this->activeQuestionId = $questionId;
    }

    public function cancelAnswer()
    {
        $this->activeQuestionId = null;
        $this->answerContent = '';
    }

    public function submitAnswer($questionId)
    {
        $this->validate([
            'answerContent' => [
                'required',
                'string',
                'min:3',
                'max:500',
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
                                $fail('No puedes compartir información de redes sociales en las respuestas.');
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
                                $fail('No puedes solicitar contacto directo en las respuestas.');
                                return;
                            }
                        }
                    }
                },
            ],
        ]);

        $parentQuestion = Question::find($questionId);
        
        Question::create([
            'product_id' => $parentQuestion->product_id,
            'user_id' => auth()->id(),
            'parent_id' => $questionId,
            'content' => $this->answerContent
        ]);

        $this->cancelAnswer();
        session()->flash('message', 'Respuesta enviada correctamente');
    }

    public function render()
    {
        $questions = Question::with(['product', 'user', 'replies.user'])
            ->whereHas('product', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard-questions', [
            'questions' => $questions
        ]);
    }
}