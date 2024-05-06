<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubCategoryRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sub_categories')->ignore($this->subcategory ? $this->subcategory->id : null),
            ],
            'type_category_id' => [
                'required',
                'exists:type_categories,id'
            ]
        ];
    }
}
