<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

class ImageController extends Controller
{
    /**
     * Upload image for a product
     *
     * @OA\Post(
     *     path="/products/{productId}/upload-image",
     *     tags={"Images"},
     *     summary="Uploader une image produit",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"image"},
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function uploadProductImage(Request $request, $productId): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::findOrFail($productId);

        // Vérifier que l'utilisateur peut modifier ce produit (admin ou propriétaire)
        if (!$request->user()->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $product->image_url))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
            }

            $image = $request->file('image');
            $filename = time() . '_' . $productId . '.' . $image->getClientOriginalExtension();

            // Stockage dans storage/app/public/products
            $path = $image->storeAs('products', $filename, 'public');

            // URL complète accessible publiquement
            $url = asset('storage/' . $path);

            // Mettre à jour le produit avec la nouvelle URL
            $product->update(['image_url' => $url]);

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image_url' => $url,
                'path' => $path,
                'product' => $product->load('category')
            ]);
        }

        return response()->json(['message' => 'No image file provided'], 400);
    }

    /**
     * Delete product image
     *
     * @OA\Delete(
     *     path="/products/{productId}/delete-image",
     *     tags={"Images"},
     *     summary="Supprimer l'image d'un produit",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function deleteProductImage(Request $request, $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        // Vérifier que l'utilisateur peut modifier ce produit
        if (!$request->user()->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($product->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $product->image_url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->image_url));
            $product->update(['image_url' => null]);

            return response()->json([
                'message' => 'Image deleted successfully',
                'product' => $product->load('category')
            ]);
        }

        return response()->json(['message' => 'No image to delete'], 404);
    }
}
