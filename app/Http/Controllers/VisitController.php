<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VisitController extends Controller
{

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $visits = Visit::with('place.subcategory.typecategory.category')->where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);

        return Inertia::render('Visit/Index', compact('visits'));
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        Visit::create([
            'user_id' => $user->id,
            'place_id' => $request->visit,
        ]);

        return redirect()->route('visits.index')->with('toast', ['Visita registrada exitosamente!', 'success']);
    }

}
