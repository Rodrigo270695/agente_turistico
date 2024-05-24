<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Department;
use App\Models\Place;
use App\Models\SubCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlaceController extends Controller
{

    public function index(): Response
    {
        $places = Place::with('subcategory.typecategory.category', 'district.province.department')->orderBy('id', 'desc')->paginate(7);
        $departments = Department::with(['provinces.districts'])->get();
        $subCategories = SubCategory::with('typecategory.category')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Place/Index', compact('places', 'departments', 'subCategories'));
    }

    public function store(PlaceRequest $request): RedirectResponse
    {
        try {
            Place::create($request->all());
            return redirect()->route('lugares.index')->with('toast', ['Lugar creado exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(Request $request, Place $place)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        //
    }
}
