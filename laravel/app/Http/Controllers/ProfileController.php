<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $user->load('reviews.author');
        $user->loadCount('reviews');
        
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'rut' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => [
                'nullable', 
                'string', 
                'max:500',
                function ($attribute, $value, $fail) {
                    // BLOQUEAR COMPLETAMENTE EL SÍMBOLO @
                    if (strpos($value, '@') !== false) {
                        $fail('El campo "Sobre mí" no puede contener el símbolo @.');
                        return;
                    }
                    
                    // Eliminar espacios para detectar correos con espacios
                    $cleanText = preg_replace('/\s+/', '', $value);
                    
                    // Patrón para detectar correos electrónicos (incluyendo con espacios)
                    $emailPattern = '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/i';
                    
                    // Patrón para detectar números de teléfono
                    $phonePattern = '/\+?\d{1,3}[-.\s]?\(?\d{1,4}\)?[-.\s]?\d{1,4}[-.\s]?\d{1,9}/';
                    
                    // Patrón para detectar URLs de redes sociales
                    $socialPattern = '/(https?:\/\/)?(www\.)?(facebook|twitter|instagram|linkedin|tiktok|whatsapp|telegram|snapchat)\.(com|org|net|io|[a-z]{2,})/i';
                    
                    // Lista de dominios de redes sociales para detección sin URL completa
                    $socialDomains = [
                        'facebook', 'twitter', 'instagram', 'whatsapp',
                        'tiktok', 'telegram', 'snapchat', 'linkedin',
                        'youtube', 'discord', 'reddit', 'pinterest'
                    ];
                    
                    // Validación en cascada
                    if (preg_match($emailPattern, $cleanText)) {
                        $fail('El campo "Sobre mí" no puede contener direcciones de correo electrónico.');
                        return;
                    }
                    
                    if (preg_match($phonePattern, $value)) {
                        $fail('El campo "Sobre mí" no puede contener números de teléfono.');
                        return;
                    }
                    
                    if (preg_match($socialPattern, $value)) {
                        $fail('El campo "Sobre mí" no puede contener enlaces a redes sociales.');
                        return;
                    }
                    
                    // Detectar dominios de redes sociales sin URL completa
                    $lowerValue = strtolower($value);
                    foreach ($socialDomains as $domain) {
                        if (strpos($lowerValue, $domain) !== false) {
                            // Verificar si está acompañado de caracteres de contacto
                            if (preg_match('/\d|http|\.com|\.net|\.org/i', $value)) {
                                $fail('El campo "Sobre mí" no puede contener información de redes sociales.');
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
                                $fail('El campo "Sobre mí" no puede solicitar contacto directo.');
                                return;
                            }
                        }
                    }
                },
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'current_password' => ['nullable', 'string', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'profile_photo.image' => 'El archivo debe ser una imagen válida.',
            'profile_photo.mimes' => 'Solo se permiten imágenes en formato JPG, JPEG o PNG.',
            'profile_photo.max' => 'La imagen no debe pesar más de 2MB.',
            'rut.required' => 'El campo RUT es obligatorio.',
        ]);
        
        // Validar la imagen de perfil si se cargó
        if ($request->hasFile('profile_photo')) {
            $validator->after(function ($validator) use ($request) {
                if (!$request->file('profile_photo')->isValid()) {
                    $validator->errors()->add('profile_photo', 'La imagen de perfil no es válida');
                }
            });
        }
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Actualizar datos básicos
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->location = $request->input('location');
        $user->bio = $request->input('bio');
        
        // NOTA: El RUT y Alias no se actualizan ya que están como readonly en el formulario
        // pero mantenemos el RUT en la validación para asegurar que esté presente
        
        // Actualizar imagen de perfil
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            try {
                // Eliminar imagen anterior si existe y no es la predeterminada
                if ($user->profile_photo_path && $user->profile_photo_path !== 'default-profile.png') {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                
                // Guardar nueva imagen
                $path = $request->file('profile_photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['profile_photo' => 'Error al guardar la imagen: ' . $e->getMessage()])
                    ->withInput();
            }
        }
        
        // Actualizar contraseña si se proporcionó
        if ($request->filled('current_password') && $request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        
        $user->save();
        
        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente');
    }
}