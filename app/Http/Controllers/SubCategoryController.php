<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategoryRequest;
use App\Models\SubCategory;
use App\Models\TypeCategory;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $subCategories = SubCategory::with('typecategory.category')->orderBy('id', 'desc')->paginate(7);
        $typeCategories = TypeCategory::with('category')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Category/SubType/Index', compact('subCategories', 'typeCategories'));
    }

    public function store(SubCategoryRequest $request): RedirectResponse
    {
        try {
            SubCategory::create($request->all());
            return redirect()->route('subcategorias.index')->with('toast', ['Sub Categoría creada exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(SubCategoryRequest $request, SubCategory $subcategoria): RedirectResponse
    {
        try {
            $subcategoria->update($request->all());
            return redirect()->route('subcategorias.index')->with('toast', ['Sub Categoría actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {
            dd($e);
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        //
    }

    public function change(SubCategory $subcategory): RedirectResponse
    {

        if ($subcategory->estado == 1) {
            $subcategory->estado = 0;
        } else {
            $subcategory->estado = 1;
        }

        $subcategory->save();
        return redirect()->route('subcategorias.index')->with('toast', ['cambio de estado exitosamente!', 'success']);
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

        $subCategories = SubCategory::with('typecategory.category')
            ->where(function ($query) use ($texto, $estado) {
                $query->whereHas('typecategory', function ($q) use ($texto) {
                    $q->where('nombre', 'like', '%' . $texto . '%');
                })
                    ->orWhere('nombre', 'like', '%' . $texto . '%')
                    ->orWhere(function ($q) use ($estado) {
                        if ($estado !== null) {
                            $q->where('estado', $estado);
                        }
                    });
            })
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $typeCategories = TypeCategory::with('category')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        return Inertia::render('Category/SubType/Index', compact('typeCategories', 'subCategories', 'texto'));
    }
}
