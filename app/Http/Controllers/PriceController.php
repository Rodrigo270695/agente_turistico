<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PriceController extends Controller
{

    public function store(Request $request)
    {
        try {
            Price::create($request->all());
            return redirect()->route('places.prices', $request->place_id)->with('toast', ['Precio registrado exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }


    public function destroy(Price $price)
    {
        //
    }
}
