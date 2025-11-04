<?php

/**
 * @OpenApi\\Annotations\\Info(
 *     title="Ecommerce API",
 *     version="1.0.0",
 *     description="Documentation de l'API e-commerce (auth, produits, catégories, commandes, paiements)."
 * )
 *
 * @OpenApi\\Annotations\\Server(
 *     url="/api",
 *     description="Serveur API (chemin de base)"
 * )
 *
 * @OpenApi\\Annotations\\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="Bearer <token>"
 * )
 *
 * @OpenApi\\Annotations\\Tag(name="Auth", description="Authentification et session")
 * @OpenApi\\Annotations\\Tag(name="Categories", description="Gestion des catégories")
 * @OpenApi\\Annotations\\Tag(name="Products", description="Gestion des produits")
 * @OpenApi\\Annotations\\Tag(name="Orders", description="Gestion des commandes")
 * @OpenApi\\Annotations\\Tag(name="Images", description="Images produits")
 * @OpenApi\\Annotations\\Tag(name="Payments", description="Paiements")
 */


