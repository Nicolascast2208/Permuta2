<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Webfriends',
            'rut' => '12345671-9',
            'email' => 'webfriendschile@gmail.com',
            'password' => Hash::make('perSPW7ta2'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Administrador',
            'rut' => '12345673-9',
            'email' => 'c.castillo@rokha.cl',
            'password' => Hash::make('.}pTVG^Ta5BfsuX^'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
                User::create([
            'name' => 'Administrador',
            'rut' => '12345672-9',
            'email' => 'drcastillo@clinit.cl',
            'password' => Hash::make('.!hgY#$s<Ho12sil)'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

    }
}