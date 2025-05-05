<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CandidateUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the "candidate" role for both web and api guards
        $webRole = Role::firstOrCreate([
            'name'       => 'candidate',
            'guard_name' => 'web',
        ]);
        $apiRole = Role::firstOrCreate([
            'name'       => 'candidate',
            'guard_name' => 'api',
        ]);

        // Create or retrieve the candidate user
        $user = User::firstOrCreate(
            ['email' => 'candidate@test.com'],
            [
                'name'              => 'Test Candidate',
                'password'          => bcrypt('password123'),
                'diplome'           => 'Licence Info',
                'telephone'         => '0123456789',
                'status'            => 'active',
                'date_inscription'  => now(),
            ]
        );

        // Assign both web and api roles to the user
        $user->syncRoles([$webRole, $apiRole]);
    }
}
