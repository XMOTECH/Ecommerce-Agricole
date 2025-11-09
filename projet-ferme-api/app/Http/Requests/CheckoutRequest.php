<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // app/Http/Requests/CheckoutRequest.php
    public function rules(): array
    {
        return [
            'address_id' => 'required|integer|exists:addresses,id',
            'delivery_mode' => 'required|string|in:point_relais,domicile', // Ex: définissez vos modes
            'payment_method' => 'required|string|in:wave,cash', // Ex: Wave, Paiement à la livraison (cash)
        ];
    }

// Pour la validation plus fine : vérifier que l'address_id appartient bien à l'utilisateur
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = Auth::user();
            if (!$user->addresses()->where('id', $this->address_id)->exists()) {
                $validator->errors()->add('address_id', 'L\'adresse spécifiée n\'appartient pas à cet utilisateur.');
            }
        });
    }
}
