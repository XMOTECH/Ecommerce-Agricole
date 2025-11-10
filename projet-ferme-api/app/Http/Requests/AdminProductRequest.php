<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix_base' => 'required|numeric|min:0',
            'prix_etudiant' => 'nullable|numeric|min:0|lt:prix_base',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'statut' => 'nullable|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom du produit est requis.',
            'prix_base.required' => 'Le prix de base est requis.',
            'prix_etudiant.lt' => 'Le prix étudiant doit être inférieur au prix de base.',
            'stock.required' => 'Le stock est requis.',
            'category_id.required' => 'La catégorie est requise.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }
}
