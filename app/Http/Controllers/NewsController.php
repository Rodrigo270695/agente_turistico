<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Models\Department;
use App\Models\News;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    public function index(): Response
    {
        $news = News::with('district.province.department')->orderBy('id', 'desc')->paginate(7);
        $departments = Department::with(['provinces.districts'])->get();
        return Inertia::render('News/Index', compact('news', 'departments'));
    }

    public function store(NewsRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['image'] = $request->file('image')->store('news', 'public');

            News::create($data);

            return redirect()->route('noticias.index')->with('toast', ['Noticia creada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function update(Request $request, News $noticia): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:100|min:3',
                'content' => 'required|string',
                'date_end' => 'required|date',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
                'district_id' => 'required|exists:districts,id',
            ]);

            $data = $validatedData;

            if ($request->hasFile('image')) {
                if ($noticia->image) {
                    Storage::disk('public')->delete($noticia->image);
                }
                $data['image'] = $request->file('image')->store('news', 'public');
            } else {
                $data['image'] = $noticia->image;
            }

            $noticia->update($data);

            return redirect()->route('noticias.index')->with('toast', ['Noticia actualizada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error!', 'danger']);
        }
    }

    public function destroy(News $news): RedirectResponse
    {
        try {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }

            $news->delete();

            return redirect()->route('news.index')->with('toast', ['Noticia eliminada exitosamente!', 'success']);
        } catch (QueryException $e) {
            return redirect()->back()->with('toast', ['No se puede eliminar la noticia!', 'danger']);
        }
    }

    public function change(News $news): RedirectResponse
    {
        $news->estado = !$news->estado;
        $news->save();
        return redirect()->route('noticias.index')->with('toast', ['Cambio de estado exitosamente!', 'success']);
    }

    public function search(Request $request): Response
    {
        $texto = $request->get('texto');
        $estado = null;

        if (strtolower($texto) === 'publicado') {
            $estado = 1;
        } elseif (strtolower($texto) === 'borrador') {
            $estado = 0;
        }

        $news = News::with('district.province.department')->where('title', 'like', '%' . $texto . '%')
            ->orWhere('estado', $estado)
            ->orderBy("id", "desc")
            ->paginate(7)
            ->appends(['texto' => $texto]);

        $departments = Department::with(['provinces.districts'])->get();

        return Inertia::render('News/Index', compact('news', 'texto', 'departments'));
    }
}
