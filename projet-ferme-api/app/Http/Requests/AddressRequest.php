<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'rue' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'nom_complet' => 'required|string|max:255',
        ];
    }
}
