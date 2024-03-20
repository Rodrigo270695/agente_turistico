<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Department;
use App\Models\Place;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlaceController extends Controller
{

    public function index()
    {
        $departments = Department::with(['provinces.districts'])->get();
        $places = Place::orderBy('id', 'desc')->paginate(7);

        return Inertia::render('Places/Index', compact('places','departments'));
    }


    public function store(PlaceRequest $request)
    {
        try {
            Place::create($request->all());
            return redirect()->route('places.index')->with('toast', ['Lugar creado exitosamente!','success']);
        } catch (QueryException $e) {

            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El lugar ya existe!','warning']);
            }else{
                dd($e);
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function update(Request $request, Place $place)
    {
        try {
            $place->update($request->all());
            return redirect()->route('places.index')->with('toast', ['Lugar actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['El lugar ya existe!', 'warning']);
            }else{
                return redirect()->back()->with('toast', ['Ocurrió un error!','danger']);
            }
        }
    }

    public function destroy(Place $place)
    {
        //
    }
}
