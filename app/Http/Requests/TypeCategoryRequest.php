<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypeCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'min:3',
                Rule::unique('type_categories')->ignore($this->tipocategoria ? $this->tipocategoria->id : null),
            ],
            'descripcion' => [
                'nullable',
                'string'
            ],
            'category_id' => [
                'required',
                'exists:categories,id'
            ]
        ];
    }
}
