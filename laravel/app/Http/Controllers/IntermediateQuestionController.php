<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\IntermediateQuestion;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\ForbiddenWordsChecker;

class IntermediateQuestionController extends Controller
{
    use ForbiddenWordsChecker;

    public function store(Request $request, Offer $offer)
    {
        $request->validate([
            'question' => [
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

        // Verificar que el usuario tiene permiso para preguntar en esta oferta
        if (Auth::id() !== $offer->from_user_id && Auth::id() !== $offer->to_user_id) {
            abort(403, 'No tienes permiso para realizar preguntas en esta oferta');
        }

        $question = IntermediateQuestion::create([
            'offer_id' => $offer->id,
            'user_id' => Auth::id(),
            'question' => $request->question,
        ]);

        // Determinar quién es el otro usuario para notificar
        $userToNotify = Auth::id() === $offer->from_user_id 
            ? $offer->to_user_id 
            : $offer->from_user_id;

        // Crear notificación para el otro usuario
        Notification::create([
            'user_id' => $userToNotify,
            'type' => 'intermediate_question',
            'notifiable_id' => $question->id,
            'notifiable_type' => IntermediateQuestion::class,
            'message' => Auth::user()->name . ' ha hecho una pregunta sobre la oferta para "' . $offer->productRequested->title . '"',
            'read' => false,
            'data' => [
                'offer_id' => $offer->id,
                'product_id' => $offer->product_requested_id,
                'product_title' => $offer->productRequested->title
            ]
        ]);

        return back()->with('success', 'Pregunta enviada correctamente');
    }

    public function answer(Request $request, IntermediateQuestion $question)
    {
        $request->validate([
            'answer' => [
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

        // Verificar que el usuario tiene permiso para responder
        $offer = $question->offer;
        $isFromUser = Auth::id() === $offer->from_user_id;
        $isToUser = Auth::id() === $offer->to_user_id;
        
        if (!$isFromUser && !$isToUser) {
            abort(403, 'No tienes permiso para responder preguntas en esta oferta');
        }

        // No permitir que alguien responda su propia pregunta
        if (Auth::id() === $question->user_id) {
            abort(403, 'No puedes responder tu propia pregunta');
        }

        $question->update([
            'answer' => $request->answer,
            'answered_by' => Auth::id(),
            'answered_at' => now(),
        ]);

        // Crear notificación para el usuario que hizo la pregunta
        Notification::create([
            'user_id' => $question->user_id,
            'type' => 'intermediate_answer',
            'notifiable_id' => $question->id,
            'notifiable_type' => IntermediateQuestion::class,
            'message' => Auth::user()->name . ' ha respondido tu pregunta sobre la oferta para "' . $offer->productRequested->title . '"',
            'read' => false,
            'data' => [
                'offer_id' => $offer->id,
                'product_id' => $offer->product_requested_id,
                'product_title' => $offer->productRequested->title
            ]
        ]);

        return back()->with('success', 'Respuesta enviada correctamente');
    }
}