<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Anyone who scans the QR can leave their details.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'phone' => ['required', 'string', 'regex:/^\+?[\d\s\-()]{6,20}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Ingresa tu nombre.',
            'name.min' => 'El nombre es muy corto.',
            'name.max' => 'El nombre es muy largo.',
            'phone.required' => 'Ingresa tu número.',
            'phone.regex' => 'Ingresa un número válido.',
        ];
    }
}
