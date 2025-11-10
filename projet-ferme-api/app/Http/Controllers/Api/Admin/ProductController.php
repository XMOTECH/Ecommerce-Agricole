<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Liste tous les produits (y compris inactifs)
     */
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($products);
    }

    /**
     * Créer un nouveau produit
     */
    public function store(AdminProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'message' => 'Produit créé avec succès',
            'product' => $product->load('category')
        ], 201);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(AdminProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return response()->json([
            'message' => 'Produit mis à jour avec succès',
            'product' => $product->load('category')
        ]);
    }

    /**
     * Désactiver un produit (soft delete)
     */
    public function destroy(Product $product)
    {
        $product->update(['statut' => 'inactive']);

        return response()->json([
            'message' => 'Produit désactivé avec succès'
        ]);
    }
}
