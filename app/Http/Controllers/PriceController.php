<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceRequest;
use App\Models\Price;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PriceController extends Controller
{
    public function store(PriceRequest $request)
    {
        try {
            Price::create($request->all());
            // Asegrate de que 'places.prices' es el nombre correcto de la ruta y que 'id' es el parámetro esperado
            return Redirect::route('places.prices', ['id' => $request->place_id])->with('toast', ['Precio registrado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return Redirect::back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(Price $price)
    {
        try {
            $price->delete();

            return redirect()->route("places.prices", $price->place_id)->with('toast', ['Precio eliminado exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->route("places.prices", $price->place_id)->with('toast', ['Error al eliminar el precio!', 'error']);
        }
    }
}
