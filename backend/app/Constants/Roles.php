<?php

namespace App\Constants;

/**
 * Constantes pour les rôles et permissions de l'application
 * Utilisé avec Spatie Laravel Permission
 */
class Roles
{
    // Rôles
    public const ADMIN = 'admin';
    public const CLIENT = 'client';
    
    // Noms d'affichage des rôles
    public const ROLE_DISPLAY_NAMES = [
        self::ADMIN => 'Administrateur',
        self::CLIENT => 'Client',
    ];
    
    // Permissions
    // Produits
    public const PERMISSION_PRODUCTS_CREATE = 'products.create';
    public const PERMISSION_PRODUCTS_READ = 'products.read';
    public const PERMISSION_PRODUCTS_UPDATE = 'products.update';
    public const PERMISSION_PRODUCTS_DELETE = 'products.delete';
    public const PERMISSION_PRODUCTS_PUBLISH = 'products.publish';
    
    // Commandes
    public const PERMISSION_ORDERS_CREATE = 'orders.create';
    public const PERMISSION_ORDERS_READ = 'orders.read';
    public const PERMISSION_ORDERS_UPDATE = 'orders.update';
    public const PERMISSION_ORDERS_CANCEL = 'orders.cancel';
    
    // Catégories
    public const PERMISSION_CATEGORIES_MANAGE = 'categories.manage';
    
    // Utilisateurs
    public const PERMISSION_USERS_CREATE = 'users.create';
    public const PERMISSION_USERS_READ = 'users.read';
    public const PERMISSION_USERS_UPDATE = 'users.update';
    public const PERMISSION_USERS_DELETE = 'users.delete';
    
    // Images
    public const PERMISSION_IMAGES_MANAGE = 'images.manage';
    
    // Toutes les permissions avec leurs noms d'affichage
    public const PERMISSIONS = [
        // Produits
        self::PERMISSION_PRODUCTS_CREATE => 'Créer des produits',
        self::PERMISSION_PRODUCTS_READ => 'Voir les produits',
        self::PERMISSION_PRODUCTS_UPDATE => 'Modifier les produits',
        self::PERMISSION_PRODUCTS_DELETE => 'Supprimer les produits',
        self::PERMISSION_PRODUCTS_PUBLISH => 'Publier les produits',
        
        // Commandes
        self::PERMISSION_ORDERS_CREATE => 'Créer des commandes',
        self::PERMISSION_ORDERS_READ => 'Voir les commandes',
        self::PERMISSION_ORDERS_UPDATE => 'Modifier les commandes',
        self::PERMISSION_ORDERS_CANCEL => 'Annuler les commandes',
        
        // Catégories
        self::PERMISSION_CATEGORIES_MANAGE => 'Gérer les catégories',
        
        // Utilisateurs
        self::PERMISSION_USERS_CREATE => 'Créer des utilisateurs',
        self::PERMISSION_USERS_READ => 'Voir les utilisateurs',
        self::PERMISSION_USERS_UPDATE => 'Modifier les utilisateurs',
        self::PERMISSION_USERS_DELETE => 'Supprimer les utilisateurs',
        
        // Images
        self::PERMISSION_IMAGES_MANAGE => 'Gérer les images',
    ];
    
    // Permissions par rôle
    public const CLIENT_PERMISSIONS = [
        self::PERMISSION_PRODUCTS_READ,
        self::PERMISSION_ORDERS_CREATE,
        self::PERMISSION_ORDERS_READ,
        self::PERMISSION_ORDERS_CANCEL,
    ];
    
    public const ADMIN_PERMISSIONS = [
        self::PERMISSION_PRODUCTS_CREATE,
        self::PERMISSION_PRODUCTS_READ,
        self::PERMISSION_PRODUCTS_UPDATE,
        self::PERMISSION_PRODUCTS_DELETE,
        self::PERMISSION_PRODUCTS_PUBLISH,
        self::PERMISSION_ORDERS_CREATE,
        self::PERMISSION_ORDERS_READ,
        self::PERMISSION_ORDERS_UPDATE,
        self::PERMISSION_ORDERS_CANCEL,
        self::PERMISSION_CATEGORIES_MANAGE,
        self::PERMISSION_USERS_CREATE,
        self::PERMISSION_USERS_READ,
        self::PERMISSION_USERS_UPDATE,
        self::PERMISSION_USERS_DELETE,
        self::PERMISSION_IMAGES_MANAGE,
    ];
    
    /**
     * Récupérer tous les rôles
     */
    public static function allRoles(): array
    {
        return array_keys(self::ROLE_DISPLAY_NAMES);
    }
    
    /**
     * Récupérer le nom d'affichage d'un rôle
     */
    public static function getRoleDisplayName(string $role): string
    {
        return self::ROLE_DISPLAY_NAMES[$role] ?? $role;
    }
    
    /**
     * Récupérer toutes les permissions
     */
    public static function allPermissions(): array
    {
        return array_keys(self::PERMISSIONS);
    }
    
    /**
     * Récupérer le nom d'affichage d'une permission
     */
    public static function getPermissionDisplayName(string $permission): string
    {
        return self::PERMISSIONS[$permission] ?? $permission;
    }
    
    /**
     * Récupérer les permissions d'un rôle
     */
    public static function getPermissionsForRole(string $role): array
    {
        return match ($role) {
            self::ADMIN => self::ADMIN_PERMISSIONS,
            self::CLIENT => self::CLIENT_PERMISSIONS,
            default => [],
        };
    }
}




