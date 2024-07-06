<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceRequest;
use App\Models\Category;
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
        $categories = Category::with('typecategories.subcategories')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Place/Index', compact('places', 'departments', 'categories'));
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

    public function update(PlaceRequest $request, Place $lugare): RedirectResponse
    {
        try {
            $lugare->update($request->all());
            return redirect()->route('lugares.index')->with('toast', ['Lugar actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al actualizar!', 'danger']);
        }
    }


    public function destroy(Place $lugare): RedirectResponse
    {
        try {
            $lugare->delete();
            return redirect()->route('lugares.index')->with('toast', ['Lugar eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al eliminar el lugar!', 'danger']);
        }
    }

    public function change(Place $place): RedirectResponse
    {
        try {
            $place->estado = !$place->estado;
            $place->save();
            return redirect()->route('lugares.index')->with('toast', ['Estado del lugar cambiado exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error al cambiar el estado!', 'danger']);
        }
    }
    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $estado = null;

        if (strtolower($texto) === 'activo') {
            $estado = 1;
        } elseif (strtolower($texto) === 'inactivo') {
            $estado = 0;
        }

        $places = Place::with('subcategory.typecategory.category', 'district.province.department')
            ->select('places.*')
            ->join('districts', 'places.district_id', '=', 'districts.id')
            ->join('provinces', 'districts.province_id', '=', 'provinces.id')
            ->join('sub_categories', 'places.sub_category_id', '=', 'sub_categories.id')
            ->join('type_categories', 'sub_categories.type_category_id', '=', 'type_categories.id')
            ->join('categories', 'type_categories.category_id', '=', 'categories.id')
            ->where('places.nombre', 'like', '%' . $texto . '%')
            ->orWhere('districts.name', 'like', '%' . $texto . '%')
            ->orWhere('provinces.name', 'like', '%' . $texto . '%')
            ->orWhere('type_categories.nombre', 'like', '%' . $texto . '%')
            ->orWhere('categories.nombre', 'like', '%' . $texto . '%')
            ->orWhere('places.estado', $estado)
            ->with('subcategory.typecategory.category', 'district.province')
            ->orderBy("places.id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);
        $departments = Department::with(['provinces.districts'])->get();
        $subCategories = SubCategory::with('typecategory.category')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Place/Index', compact('places', 'texto', 'departments', 'subCategories'));
    }

    public function photos($id): Response
    {
        $place = Place::with('photos')->orderBy('id', 'desc')->findOrFail($id);

        return Inertia::render('Place/Photos/Index', compact('place'));
    }

    public function prices($id): Response
    {
        $place = Place::with('prices')->orderBy('id', 'desc')->findOrFail($id);

        return Inertia::render('Place/Prices/Index', compact('place'));
    }
}
