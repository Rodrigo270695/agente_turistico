<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Photo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{


    public function store(PhotoRequest $request)
    {
        try {
            foreach ($request->file('urls') as $file) {
                $image = $file->store('photos', 'public');
                $url = Storage::url($image);
                Photo::create([
                    'url' => $url,
                    'place_id' => $request->place_id
                ]);
            }

            return redirect()->route("places.photos", $request->place_id)->with('toast', ['Fotos registradas exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->route("places.photos", $request->place_id)->with('toast', ['Error al registrar las fotos!', 'error']);
        }
    }
    public function destroy(Photo $photo)
    {
        try {
            Storage::disk('public')->delete(str_replace('/storage/', '', $photo->url));
            $photo->delete();

            return redirect()->route("places.photos", $photo->place_id)->with('toast', ['Foto eliminada exitosamente!', 'success']);
        } catch (Exception $e) {
            return redirect()->route("places.photos", $photo->place_id)->with('toast', ['Error al eliminar la foto!', 'error']);
        }
    }


}
