<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Lister les commandes (user: les siennes, admin: toutes)",
     *     security={{{"sanctum": {}}}},
     *     @OA\Response(response=200, description="OK")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Les clients voient seulement leurs commandes, les admins voient toutes
        $query = Order::with(['orderItems.product', 'user']);

        if (!$user->hasRole('administrateur')) {
            $query->where('user_id', $user->id);
        }

        $orders = $query->latest()->paginate(20);

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/orders",
     *     tags={"Orders"},
     *     summary="Créer une commande",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"items","shipping_address","phone"},
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     required={"product_id","quantity"},
     *                     @OA\Property(property="product_id", type="integer"),
     *                     @OA\Property(property="quantity", type="integer", minimum=1)
     *                 )
     *             ),
     *             @OA\Property(property="shipping_address", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="notes", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créée"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $user = $request->user();
        $totalAmount = 0;
        $orderItems = [];

        DB::transaction(function () use ($validated, $user, &$totalAmount, &$orderItems) {
            // Calculer le total et vérifier le stock
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if (!$product->is_active) {
                    throw new \Exception("Product {$product->name} is not available");
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $subtotal,
                ];
            }

            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Créer les items de commande et décrémenter le stock
            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);

                // Décrémenter le stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock_quantity', $item['quantity']);
            }
        });

        return response()->json([
            'message' => 'Order created successfully',
            'total_amount' => $totalAmount
        ], 201);
    }

    /**
     * Display the specified resource.
     *
    * @OA\Get(
    *     path="/orders/{order}",
    *     tags={"Orders"},
    *     summary="Afficher une commande",
    *     security={{{"sanctum": {}}}},
    *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
    *     @OA\Response(response=200, description="OK"),
    *     @OA\Response(response=403, description="Unauthorized"),
    *     @OA\Response(response=404, description="Not found")
    * )
    */
    public function show(Order $order): JsonResponse
    {
        $user = request()->user();

        // Vérifier que l'utilisateur peut voir cette commande
        if (!$user->hasRole('administrateur') && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($order->load(['orderItems.product', 'user']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Mettre à jour le statut (admin)",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending","confirmed","shipped","delivered","cancelled"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $user = $request->user();

        // Seuls les admins peuvent modifier le statut
        if (!$user->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return response()->json($order->load(['orderItems.product', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/orders/{order}",
     *     tags={"Orders"},
     *     summary="Annuler (user) ou supprimer (admin) la commande",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy(Order $order): JsonResponse
    {
        $user = request()->user();

        // Seuls les admins peuvent supprimer, ou l'utilisateur peut annuler sa propre commande
        if (!$user->hasRole('administrateur')) {
            if ($order->user_id !== $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Annuler au lieu de supprimer
            $order->update(['status' => 'cancelled']);

            // Remettre le stock
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            return response()->json(['message' => 'Order cancelled successfully']);
        }

        // Admin supprime complètement
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
