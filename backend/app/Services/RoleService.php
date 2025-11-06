<?php

namespace App\Services;

use App\Constants\Roles;

/**
 * Service pour gérer les rôles et permissions
 * Utilise les constantes définies dans App\Constants\Roles
 */
class RoleService
{
    // Constantes pour les rôles (pour compatibilité avec le seeder)
    public const ADMIN = Roles::ADMIN;
    public const CLIENT = Roles::CLIENT;

    /**
     * Récupérer toutes les permissions disponibles
     */
    public static function getAllPermissions(): array
    {
        return Roles::allPermissions();
    }

    /**
     * Récupérer les permissions d'un administrateur
     */
    public static function getAdminPermissions(): array
    {
        return Roles::ADMIN_PERMISSIONS;
    }

    /**
     * Récupérer les permissions d'un client
     */
    public static function getClientPermissions(): array
    {
        return Roles::CLIENT_PERMISSIONS;
    }

    /**
     * Récupérer tous les rôles disponibles
     */
    public static function getAllRoles(): array
    {
        return Roles::allRoles();
    }

    /**
     * Vérifier si un rôle existe
     */
    public static function roleExists(string $role): bool
    {
        return in_array($role, self::getAllRoles());
    }

    /**
     * Récupérer le nom d'affichage d'un rôle
     */
    public static function getRoleDisplayName(string $role): string
    {
        return Roles::getRoleDisplayName($role);
    }

    /**
     * Récupérer les permissions d'un rôle spécifique
     */
    public static function getPermissionsForRole(string $role): array
    {
        return Roles::getPermissionsForRole($role);
    }
}
