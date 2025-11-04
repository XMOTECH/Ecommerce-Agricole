<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Lister les catégories",
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')->get();

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Créer une catégorie",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image_url", type="string", format="uri")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créée"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Afficher une catégorie",
     *     @OA\Parameter(name="category", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json($category->load(['products' => function ($query) {
            $query->active()->inStock();
        }]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Mettre à jour une catégorie",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="category", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="image_url", type="string", format="uri")
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
        ]);

        $category->update($validated);

        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/categories/{category}",
     *     tags={"Categories"},
     *     summary="Supprimer une catégorie",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="category", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Supprimée"),
     *     @OA\Response(response=422, description="Conflit (produits existants)"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(Category $category): JsonResponse
    {
        // Vérifier si la catégorie a des produits
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing products'
            ], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
