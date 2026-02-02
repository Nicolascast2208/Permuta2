<?php
// database/seeders/UpdateExistingUsersSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpdateExistingUsersSeeder extends Seeder
{
    public function run()
    {
        // Activar todos los usuarios existentes
        User::where('is_active', false)->orWhereNull('is_active')->update([
            'is_active' => true
        ]);
    }
}