<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Lister les produits",
     *     @OA\Parameter(name="search", in="query", description="Recherche par nom", @OA\Schema(type="string")),
     *     @OA\Parameter(name="category_id", in="query", description="Filtrer par catégorie", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category')->active()->inStock();

        // Recherche par nom
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtrage par catégorie
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(20);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/products",
     *     tags={"Products"},
     *     summary="Créer un produit",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","price","stock_quantity","unit","category_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock_quantity", type="integer"),
     *             @OA\Property(property="unit", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="image_url", type="string", format="uri"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créé"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,id',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/products/{product}",
     *     tags={"Products"},
     *     summary="Afficher un produit",
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product->load('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/products/{product}",
     *     tags={"Products"},
     *     summary="Mettre à jour un produit",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number", format="float"),
     *             @OA\Property(property="stock_quantity", type="integer"),
     *             @OA\Property(property="unit", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="image_url", type="string", format="uri"),
     *             @OA\Property(property="is_active", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock_quantity' => 'sometimes|required|integer|min:0',
            'unit' => 'sometimes|required|string|max:50',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        return response()->json($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/products/{product}",
     *     tags={"Products"},
     *     summary="Supprimer un produit",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Supprimé"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
