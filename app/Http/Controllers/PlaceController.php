<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Place;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlaceController extends Controller
{

    public function index()
    {
        $departments = Department::with(['provinces.districts'])->get();
        $places = Place::orderBy('id', 'desc')->paginate(7);

        return Inertia::render('Places/Index', compact('places','departments'));
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

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        //
    }
}
