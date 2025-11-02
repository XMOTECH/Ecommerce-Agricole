<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // Créer les permissions pour les produits
        $productPermissions = [
            'products.create',
            'products.read',
            'products.update',
            'products.delete',
            'products.publish',
        ];

        // Créer les permissions pour les commandes
        $orderPermissions = [
            'orders.create',
            'orders.read',
            'orders.update',
            'orders.cancel',
        ];

        // Créer les permissions pour les catégories
        $categoryPermissions = [
            'categories.manage',
        ];

        // Créer les permissions pour les utilisateurs
        $userPermissions = [
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
        ];

        // Créer toutes les permissions
        $allPermissions = array_merge(
            $productPermissions,
            $orderPermissions,
            $categoryPermissions,
            $userPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ===== CONFIGURATION POUR MVP (2 RÔLES) =====

        // 1. Administrateur - Accès total à la gestion
        $admin = Role::create(['name' => 'administrateur']);
        $admin->givePermissionTo(Permission::all());

        // 2. Client - Consultation et achat uniquement
        $client = Role::create(['name' => 'client']);
        $client->givePermissionTo([
            'products.read',           // Voir les produits
            'orders.create',           // Créer des commandes
            'orders.read',             // Voir ses propres commandes
            'orders.cancel',           // Annuler ses commandes
        ]);

        // Créer des utilisateurs de test
        
        // Administrateur
        $adminUser = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@agrimarket.com',
            'phone' => '+221771234567',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('administrateur');

        // Client
        $clientUser = User::create([
            'name' => 'Client Test',
            'email' => 'client@agrimarket.com',
            'phone' => '+221772345678',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $clientUser->assignRole('client');
    }
}