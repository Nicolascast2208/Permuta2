<?php

namespace App\Traits;

trait ForbiddenWordsChecker
{
    /**
     * Verifica si un texto contiene palabras prohibidas
     */
    protected function containsForbiddenWords($text)
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
    protected function normalizeText($text)
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
}