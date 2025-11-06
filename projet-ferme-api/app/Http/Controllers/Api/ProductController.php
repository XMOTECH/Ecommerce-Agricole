<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function index(Request $request){
        $query = Product::where('statut', 'active')
            ->with('category');
        if($request->has('category')){
            $categorySlug = $request->query('category');

            $query->whereHas('category', function($q) use ($categorySlug){
                $q->where('slug', $categorySlug);
            });
        }
        $products = $query->paginate(15);

        return response()->json($products);
    }
    public function show(string $id){
        $product = Product::where('statut', 'active')
            ->with('category')
            ->find($id);

        if (!$product) {
            return response()->json(['message' => 'produit non trouver ou inactives']);
        }
        return response()->json($product);
    }
}
