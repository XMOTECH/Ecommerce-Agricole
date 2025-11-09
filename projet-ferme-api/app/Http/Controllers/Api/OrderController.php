<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function index(Request $request){
        $orders = $request->user()->orders()
            ->orderByDesc('created_at')
            ->paginate(10);
        return response()->json($orders);
    }
    public function show(Request $request, int $id)
    {
        // Vérifier que la commande appartient bien à l'utilisateur (Policy non nécessaire ici grâce au where)
        $order = Order::where('user_id', $request->user()->id)
            ->with('orderItems') // Charger les articles commandés
            ->findOrFail($id);

        return response()->json($order);
    }
}
