<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'dni' => 'required|string|size:8|unique:users,dni,' . $this->user,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user,
            'role' => 'required|exists:roles,name',
        ];
    }
}
