<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Liste toutes les commandes (avec filtres)
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filtre par utilisateur
        if ($request->has('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Filtre par date
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $orders = $query->orderByDesc('created_at')->paginate(20);

        return response()->json($orders);
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        $order->adresse_decoded = json_decode($order->adresse);

        return response()->json($order);
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in(['pending', 'processing', 'completed', 'cancelled'])
            ],
        ]);

        $oldStatus = $order->status;
        $order->status = $validated['status'];
        $order->save();

        // TODO: Envoyer une notification au client
        // if ($order->status === 'processing') {
        //     Mail::to($order->user->email)->send(new OrderProcessingMail($order));
        // }

        return response()->json([
            'message' => "Statut de la commande mis à jour de '{$oldStatus}' à '{$order->status}'",
            'order' => $order->load('orderItems.product')
        ]);
    }
}
