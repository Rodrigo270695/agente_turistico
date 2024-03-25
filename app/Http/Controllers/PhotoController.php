<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        dd($request->all());
        try {
            $request->validate([
                'photos' => 'required',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'place_id' => 'required|exists:places,id', // Asegura que el place_id sea proporcionado y exista en la tabla places
            ]);

            $photos = $request->file('photos');
            $placeId = $request->input('place_id'); // Obtiene el place_id de la solicitud

            foreach ($photos as $photo) {
                $path = $photo->store('photos', 'public');
                Photo::create([
                    'image' => $path, // Asegúrate de que el nombre de la columna en tu migración coincida con lo que estás guardando
                    'place_id' => $placeId, // Asocia el place_id con la foto
                ]);
            }

            return redirect()->route('places.index')->with('toast', ['Fotos cargadas exitosamente!', 'success']);
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()->with('toast', ['Una o más fotos ya existen!', 'warning']);
            } else {
                return redirect()->back()->with('toast', ['Ocurrió un error al cargar las fotos!', 'danger']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('toast', ['Ocurrió un error inesperado!', 'danger']);
        }
    }

    public function show(Photo $photo)
    {
        //
    }


    public function edit(Photo $photo)
    {
        //
    }


    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
