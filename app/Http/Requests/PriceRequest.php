<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_persona' => [
                'required',
                'string',
                'max:50',
                Rule::unique('prices')->where(function ($query) {
                    $query->where('place_id', $this->place_id);
                }),
            ],
            'precio' => 'required|numeric|min:0',
            'place_id' => 'required|integer|exists:places,id',
        ];
    }
}
