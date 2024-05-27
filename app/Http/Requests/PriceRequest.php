<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_persona' => 'required|string|max:50|unique_with:prices,place_id',
            'precio' => 'required|numeric|min:0',
            'place_id' => 'required|integer|exists:places,id',
        ];
    }
}
