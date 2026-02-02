<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
        public function showRegistrationForm()
    {
        return view('auth.register');
    }
public function register(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'rut' => ['required', 'string', 'max:12', 'unique:users'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'profile_photo' => ['nullable', 'image', 'max:2048']
    ]);

    $user = User::create([
        'name' => $request->name,
        'rut' => $request->rut,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    if ($request->hasFile('profile_photo')) {
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);
    }

    event(new Registered($user)); // ✅ envía el correo de verificación

    Auth::login($user);

    // Redirige a la vista de verificación
    return redirect()->route('verification.notice');
}
}