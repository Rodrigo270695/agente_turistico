<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:places',
            'department' => 'required|string|max:50',
            'province' => 'required|string|max:50',
            'district' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'type_place' => 'required|string|max:50',
            'care_day' => 'required|string|max:50',
            'opening_hours' => 'required|string|max:50',
            'price' => 'required|numeric',
            'access' => 'required|string|max:50',
        ];
    }
}
