<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class SwaggerController extends Controller
{
    /**
     * @OA\Info(
     *     title="Ecommerce API",
     *     version="1.0.0",
     *     description="Documentation de l'API e-commerce (auth, produits, catégories, commandes, paiements)."
     * )
     *
     * @OA\Server(
     *     url="/api",
     *     description="Serveur API (chemin de base)"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="sanctum",
     *     type="apiKey",
     *     in="header",
     *     name="Authorization",
     *     description="Bearer <token>"
     * )
     *
     * @OA\Tag(name="Auth", description="Authentification et session")
     * @OA\Tag(name="Categories", description="Gestion des catégories")
     * @OA\Tag(name="Products", description="Gestion des produits")
     * @OA\Tag(name="Orders", description="Gestion des commandes")
     * @OA\Tag(name="Images", description="Images produits")
     * @OA\Tag(name="Payments", description="Paiements")
     */
}


