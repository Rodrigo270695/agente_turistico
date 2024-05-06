<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeCategoryRequest;
use App\Models\Category;
use App\Models\TypeCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TypeCategoryController extends Controller
{

    public function index(): Response
    {
        $typeCategories = TypeCategory::with('category')->orderBy('id','desc')->paginate(7);
        $categories = Category::where('estado',1)->orderBy('nombre','asc')->get();

        return Inertia::render('Category/TypeCategory/Index', compact(['typeCategories','categories']));
    }

    public function store(TypeCategoryRequest $request): RedirectResponse
    {
        try {
            TypeCategory::create($request->all());
            return redirect()->route('tipocategorias.index')->with('toast', ['Tipo Categoría creada exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(TypeCategoryRequest $request, TypeCategory $tipocategoria): RedirectResponse
    {
        try {
            $tipocategoria->update($request->all());
            return redirect()->route('tipocategorias.index')->with('toast', ['Tipo Categoría actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(TypeCategory $typeCategory): RedirectResponse
    {
        try {
            $typeCategory->delete();
            return redirect()->route('tipocategorias.index')->with('toast', ['Tipo Categoría eliminado exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function change(TypeCategory $types): RedirectResponse
    {

        if ($types->estado == 1) {
            $types->estado = 0;
        }else {
            $types->estado = 1;
        }

        $types->save();
        return redirect()->route('tipocategorias.index')->with('toast', ['cambio de estado exitosamente!', 'success']);

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

        $typeCategories = TypeCategory::with('category')
            ->where(function ($query) use ($texto, $estado) {
                $query->whereHas('category', function ($q) use ($texto) {
                    $q->where('nombre', 'like', '%' . $texto . '%');
                })
                ->orWhere('nombre', 'like', '%' . $texto . '%')
                ->orWhere(function($q) use ($estado) {
                    if ($estado !== null) {
                        $q->where('estado', $estado);
                    }
                });
            })
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $categories = Category::where('estado',1)->orderBy('nombre','asc')->get();

        return Inertia::render('Category/TypeCategory/Index', compact('typeCategories','categories', 'texto'));
    }

}
