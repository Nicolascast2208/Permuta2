<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'rut' => $this->faker->numerify('########-#'), // <-- agregado
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // clave por defecto
            'remember_token' => \Str::random(10),
            'alias' => 'Permutador_' . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT),
            'profile_photo_path' => 'default-profile.png'
        ];
    }
}
