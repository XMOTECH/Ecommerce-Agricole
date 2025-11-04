<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class PaymentController extends Controller
{
    /**
     * Initiate payment for an order
     *
     * @OA\Post(
     *     path="/payments/initiate",
     *     tags={"Payments"},
     *     summary="Initier un paiement",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id","payment_method","amount"},
     *             @OA\Property(property="order_id", type="integer"),
     *             @OA\Property(property="payment_method", type="string", enum={"orange_money","wave","card"}),
     *             @OA\Property(property="amount", type="number", format="float")
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=400, description="Order not eligible")
     * )
     */
    public function initiatePayment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:orange_money,wave,card',
            'amount' => 'required|numeric|min:0'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Vérifier que l'utilisateur peut payer cette commande
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Vérifier que la commande est en attente de paiement
        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order is not eligible for payment'], 400);
        }

        // Simulation d'initiation de paiement
        $paymentId = 'pay_' . time() . '_' . $order->id;

        // Simuler différents scénarios selon la méthode de paiement
        $paymentUrl = match($request->payment_method) {
            'orange_money' => 'https://api.orange.com/payment/' . $paymentId,
            'wave' => 'https://api.wave.com/payment/' . $paymentId,
            'card' => 'https://api.stripe.com/payment/' . $paymentId,
        };

        return response()->json([
            'payment_id' => $paymentId,
            'order_id' => $order->id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'payment_url' => $paymentUrl,
            'expires_at' => now()->addMinutes(15)->toISOString()
        ]);
    }

    /**
     * Confirm payment (webhook simulation)
     *
     * @OA\Post(
     *     path="/payments/confirm",
     *     tags={"Payments"},
     *     summary="Confirmer le paiement (simulation)",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"payment_id","transaction_id","status"},
     *             @OA\Property(property="payment_id", type="string"),
     *             @OA\Property(property="transaction_id", type="string"),
     *             @OA\Property(property="status", type="string", enum={"success","failed","cancelled"})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Confirmé"),
     *     @OA\Response(response=400, description="Invalid payment ID"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|string',
            'transaction_id' => 'required|string',
            'status' => 'required|in:success,failed,cancelled'
        ]);

        // Extraire l'order_id du payment_id
        $parts = explode('_', $request->payment_id);
        if (count($parts) !== 3 || $parts[0] !== 'pay') {
            return response()->json(['message' => 'Invalid payment ID'], 400);
        }

        $orderId = $parts[2];
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Simuler la confirmation de paiement
        if ($request->status === 'success') {
            $order->update(['status' => 'confirmed']);

            return response()->json([
                'message' => 'Payment confirmed successfully',
                'order' => $order->load(['orderItems.product', 'user']),
                'transaction_id' => $request->transaction_id
            ]);
        } elseif ($request->status === 'failed') {
            return response()->json([
                'message' => 'Payment failed',
                'order_id' => $order->id
            ], 400);
        } else {
            return response()->json([
                'message' => 'Payment cancelled',
                'order_id' => $order->id
            ]);
        }
    }

    /**
     * Check payment status
     *
     * @OA\Get(
     *     path="/payments/{paymentId}/status",
     *     tags={"Payments"},
     *     summary="Statut de paiement",
     *     security={{{"sanctum": {}}}},
     *     @OA\Parameter(name="paymentId", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=400, description="Invalid payment ID"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function checkPaymentStatus(Request $request, $paymentId): JsonResponse
    {
        // Extraire l'order_id du payment_id
        $parts = explode('_', $paymentId);
        if (count($parts) !== 3 || $parts[0] !== 'pay') {
            return response()->json(['message' => 'Invalid payment ID'], 400);
        }

        $orderId = $parts[2];
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Vérifier que l'utilisateur peut voir cette commande
        if ($order->user_id !== $request->user()->id && !$request->user()->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'payment_id' => $paymentId,
            'order_id' => $order->id,
            'status' => $order->status,
            'amount' => $order->total_amount,
            'created_at' => $order->created_at
        ]);
    }

    /**
     * Simulate payment callback (for testing)
     *
     * @OA\Post(
     *     path="/payments/simulate",
     *     tags={"Payments"},
     *     summary="Simuler un paiement",
     *     security={{{"sanctum": {}}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_id"},
     *             @OA\Property(property="order_id", type="integer"),
     *             @OA\Property(property="success", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="OK"),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=404, description="Order not found")
     * )
     */
    public function simulatePayment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'success' => 'boolean'
        ]);

        $order = Order::findOrFail($request->order_id);

        // Vérifier que l'utilisateur peut modifier cette commande
        if ($order->user_id !== $request->user()->id && !$request->user()->hasRole('administrateur')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->input('success', true)) {
            $order->update(['status' => 'confirmed']);
            $message = 'Payment simulation successful';
        } else {
            $message = 'Payment simulation failed';
        }

        return response()->json([
            'message' => $message,
            'order' => $order->load(['orderItems.product', 'user'])
        ]);
    }
}
