<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:100',
                'min:3',
                Rule::unique('news', 'title')->ignore($this->news),
            ],
            'content' => 'required|string',
            'date_end' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'district_id' => 'required|exists:districts,id',
        ];
    }
}
