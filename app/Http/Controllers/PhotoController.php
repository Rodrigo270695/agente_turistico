<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
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

    public function store(PhotoRequest $request)
    {
        try {

            $placeId = $request->input('place_id');

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $filename = time() . '_' . $photo->getClientOriginalName();
                    $path = $photo->storeAs('photos', $filename, 'public');

                    Photo::create([
                        'image' => $path,
                        'place_id' => $placeId,
                    ]);
                }
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
