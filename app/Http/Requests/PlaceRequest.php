<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('places')->ignore($this->lugare ? $this->lugare->id : null),
            ],
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'dias_abierto_desde' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'dias_cerrado_hasta' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
            'hora_apertura' => 'required',
            'hora_cierre' => 'required',
            'direccion' => 'required|string|max:200',
            'distancia_horas' => 'required',
            'distancia_km' => 'required|numeric',
            'epoca_visita' => 'required|string|max:50',
            'entrada' => 'required',
            'tipo_acceso' => 'required|string|max:15',
            'district_id' => 'required|exists:districts,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ];
    }
}
