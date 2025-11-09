<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(CheckoutRequest $request)
    {
        $user = $request->user();

        DB::beginTransaction();

        try {
            // 1. Récupérer les articles du panier
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Votre panier est vide');
            }

            // 2. Vérifier si l'utilisateur est étudiant
            $isStudent = method_exists($user, 'hasRole') ? $user->hasRole('student') : false;

            $totalOrder = 0;
            $orderItemsData = [];

            // 3. Valider le stock et calculer le total
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $quantity = $cartItem->quantite;

                // Vérifier le stock
                if ($product->stock < $quantity) {
                    throw new \Exception("Stock insuffisant pour {$product->nom}.");
                }

                // Calculer le prix
                $pricePaid = $isStudent ? $product->prix_etudiant : $product->prix_base;
                $subTotal = $pricePaid * $quantity;
                $totalOrder += $subTotal;

                // Préparer les données des items
                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'nom_de_produit' => $product->nom,
                    'prix_payer' => $pricePaid,
                    'quantite' => $quantity,
                ];
            }

            // 4. Récupérer l'adresse (CORRIGÉ : addresses au lieu de adresses)
            $address = $user->addresses()->findOrFail($request->address_id);

            // 5. Créer la commande UNE SEULE FOIS (SORTI DE LA BOUCLE)
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $totalOrder,
                'adresse' => $address->toJson(),
                'mode_de_livraison' => $request->delivery_mode,
                'status' => $request->payment_method == 'cash' ? 'pending' : 'pending',
            ]);

            // 6. Créer tous les items de la commande
            $order->items()->createMany($orderItemsData);

            // 7. Décrémenter le stock de tous les produits
            foreach ($cartItems as $cartItem) {
                Product::where('id', $cartItem->product_id)
                    ->decrement('stock', $cartItem->quantite);
            }

            // 8. Vider le panier
            Cart::where('user_id', $user->id)->delete();

            // 9. Traitement du paiement Wave
            if ($request->payment_method == 'wave') {
                // TODO: Intégrer l'API Wave
                // $waveResponse = $this->callWaveApi($order);
                // if ($waveResponse->success) {
                //     $order->update(['statut' => 'en_attente_confirmation_wave']);
                //     DB::commit();
                //     return response()->json([
                //         'message' => 'Redirection vers Wave.',
                //         'redirect_url' => $waveResponse->link
                //     ], 202);
                // } else {
                //     throw new \Exception('Erreur de l\'API Wave.');
                // }

                // Pour la démo, on simule le succès
                $order->update(['status' => 'processing']);
                // TODO: Envoyer email de confirmation
            }

            DB::commit();

            return response()->json([
                'message' => 'Commande créée avec succès',
                'order' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Échec de la commande : ' . $e->getMessage()
            ], 400);
        }
    }
}
