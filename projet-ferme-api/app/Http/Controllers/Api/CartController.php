<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::where('user_id', $request->user()->id)
            ->with('product')
            ->get();
        return response()->json($cart);
    }

    public function add(CartAddRequest $request)
    {
        $user = $request->user();
        $productId = $request->product_id;
        $quantite = $request->quantite;

        // Vérifier si l'article existe déjà dans le panier
        $cartItem = Cart::where('user_id', $user->id)
            ->where('produit_id', $productId)
            ->first();

        if ($cartItem) {
            // Si l'article existe, on incrémente la quantité
            $cartItem->quantite += $quantite;
            $cartItem->save();
        } else {
            // Sinon on crée un nouvel article
            $cartItem = Cart::create([
                'user_id' => $user->id,
                'produit_id' => $productId,
                'quantite' => $quantite,
            ]);
        }

        return response()->json([
            'message' => 'Article ajouté/mis à jour avec succès',
            'cart_item' => $cartItem->load('product')
        ], 201);
    }

    public function update(Request $request, int $productId)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1|max:100'
        ]);

        $cartItem = Cart::where('user_id', $request->user()->id)
            ->where('produit_id', $productId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'message' => 'Article non trouvé dans le panier.'
            ], 404);
        }

        $cartItem->quantite = $request->quantite;
        $cartItem->save();

        return response()->json([
            'message' => 'Quantité mise à jour.',
            'cart_item' => $cartItem->load('product')
        ]);
    }

    public function remove(Request $request, int $productId)
    {
        $deleted = Cart::where('user_id', $request->user()->id)
            ->where('produit_id', $productId)
            ->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Article supprimé du panier.'
            ], 200);
        }

        return response()->json([
            'message' => 'Article non trouvé dans le panier.'
        ], 404);
    }

    public function sync(Request $request)
    {
        $request->validate([
            'local_cart' => 'required|array',
            'local_cart.*.produit_id' => 'required|integer|exists:products,id',
            'local_cart.*.quantite' => 'required|integer|min:1|max:100',
        ]);

        $user = $request->user();
        $localCart = $request->local_cart;

        $newlyAdded = [];
        $updated = [];

        foreach ($localCart as $item) {
            $productId = $item['produit_id'];
            $localQuantity = $item['quantite'];

            $cartItem = Cart::where('user_id', $user->id)
                ->where('produit_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantite = $localQuantity;
                $cartItem->save();
                $updated[] = $cartItem;
            } else {
                $newItem = Cart::create([
                    'user_id' => $user->id,
                    'produit_id' => $productId,
                    'quantite' => $localQuantity,
                ]);
                $newlyAdded[] = $newItem;
            }
        }

        $finalCart = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();

        return response()->json([
            'message' => 'Panier synchronisé et fusionné avec succès.',
            'final_cart' => $finalCart,
            'summary' => [
                'added_new_items' => count($newlyAdded),
                'updated_items' => count($updated),
            ]
        ]);
    }
}
