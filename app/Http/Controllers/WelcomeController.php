<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('About');
    }

    public function places()
    {
        $places = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos','prices')->where('estado', 1)->orderBy('created_at', 'desc')->paginate(15);

        return Inertia::render('PlacesCliente/Index', compact('places'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $place = Place::with([
            'subcategory.typecategory.category',
            'district.province.department',
            'photos',
            'prices'
        ])->findOrFail($id);

        // Consumir la API
        $response = Http::get('http://127.0.0.1:5000/recomendaciones/' . $id);
        $recommendations = $response->json();

        // Obtener los productos recomendados
        $recommendedPlaces = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos','prices')->whereIn('id', $recommendations['recomendaciones'])->get();

        return Inertia::render('PlacesCliente/ShowPlace', compact('place', 'recommendedPlaces'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
