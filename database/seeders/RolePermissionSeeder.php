<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Réinitialise le cache des permissions pour éviter toute incohérence
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Création des permissions (ajuste-les selon tes besoins)
        $viewDashboard = Permission::firstOrCreate(['name' => 'view dashboard']);
        $postJob       = Permission::firstOrCreate(['name' => 'post job']);
        $applyJob      = Permission::firstOrCreate(['name' => 'apply for job']);

        // Création des rôles
        $adminRole    = Role::firstOrCreate(['name' => 'admin']);
        $employerRole = Role::firstOrCreate(['name' => 'employeur']);
        $candidateRole = Role::firstOrCreate(['name' => 'condidat']);

        // Attribution des permissions
        // Pour l'admin : toutes les permissions
        $adminRole->syncPermissions(Permission::all());

        // Pour l'employeer : accès au dashboard et possibilité de poster des offres
        $employerRole->syncPermissions([$viewDashboard, $postJob]);

        // Pour le condidat : accès au dashboard et possibilité de postuler aux offres
        $candidateRole->syncPermissions([$viewDashboard, $applyJob]);
    }
}
