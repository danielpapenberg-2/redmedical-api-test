<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:connector,vpn_connection'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be either connector or vpn_connection.',
        ];
    }
}
