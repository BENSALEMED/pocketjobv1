<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $user = new User();
        $user->name = 'Admin Test';
        $user->email = 'admin@test.com';
        $user->password = bcrypt('hamma123');  // Choisis un mot de passe robuste
        $user->save();

        // Affecte le rôle "admin" à cet utilisateur
        $user->assignRole('admin');
    }
}

