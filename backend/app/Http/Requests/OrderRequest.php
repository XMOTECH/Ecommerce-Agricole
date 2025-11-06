<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }
    
    public function rules()
    {
        return [
            'items' => 'required|array|min:1|max:20',
            'items.*.product_id' => [
                'required',
                'integer',
                'exists:products,id,is_active,1'
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100'
            ],
            'shipping_address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'regex:/^[a-zA-Z0-9\s\-\.\,]+$/'
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\+)?[0-9\s\-\(\)]{8,20}$/'
            ],
            'notes' => 'nullable|string|max:1000',
        ];
    }
    
    public function messages()
    {
        return [
            'items.required' => 'Au moins un article est requis.',
            'items.max' => 'Maximum 20 articles par commande.',
            'items.*.product_id.required' => 'L\'ID du produit est requis.',
            'items.*.product_id.exists' => 'Le produit sélectionné n\'existe pas ou n\'est pas disponible.',
            'items.*.quantity.required' => 'La quantité est requise.',
            'items.*.quantity.min' => 'La quantité minimum est 1.',
            'items.*.quantity.max' => 'La quantité maximum est 100.',
            'shipping_address.required' => 'L\'adresse de livraison est requise.',
            'shipping_address.min' => 'L\'adresse doit contenir au moins 10 caractères.',
            'shipping_address.max' => 'L\'adresse ne peut pas dépasser 500 caractères.',
            'shipping_address.regex' => 'L\'adresse contient des caractères non valides.',
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.regex' => 'Format de téléphone invalide. Utilisez les formats: +1234567890, 123-456-7890, ou 1234567890.',
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
        ];
    }
    
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Vérifier que les quantités ne dépassent pas le stock
            if ($this->has('items')) {
                foreach ($this->items as $index => $item) {
                    $product = \App\Models\Product::find($item['product_id']);
                    if ($product && $product->stock_quantity < $item['quantity']) {
                        $validator->errors()->add("items.{$index}.quantity", 
                            "Stock insuffisant pour {$product->name}. Stock disponible: {$product->stock_quantity}");
                    }
                }
            }
        });
    }
}