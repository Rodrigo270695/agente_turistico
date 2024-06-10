<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller 
{

    public function index(): Response
    {
        $categories = Category::orderBy('id', 'desc')->paginate(7);
        return Inertia::render('Category/Index', compact('categories'));
    }


    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            Category::create($request->all());
            return redirect()->route('categorias.index')->with('toast', ['Categoría creada exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }


    public function update(CategoryRequest $request, Category $categoria): RedirectResponse
    {
        try {
            $categoria->update($request->all());
            return redirect()->route('categorias.index')->with('toast', ['Categoría actualizado exitosamente!', 'success']);
        } catch (QueryException $e) {

            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function change(Category $category): RedirectResponse
    {

        if ($category->estado == 1) {
            $category->estado = 0;
        }else {
            $category->estado = 1;
        }

        $category->save();
        return redirect()->route('categorias.index')->with('toast', ['cambio de estado exitosamente!', 'success']);

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

        $categories = Category::Where('nombre', 'like', '%' . $texto . '%')
            ->orWhere('estado', $estado)
            ->orderBy("id","desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        return Inertia::render('Category/Index', compact('categories', 'texto'));
    }
}
