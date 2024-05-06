<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Place;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PlaceController extends Controller
{

    public function index(): Response
    {
        $places = Place::with('subcategory.typecategory.category', 'district.province.department')->orderBy('id', 'desc')->paginate(7);
        $departments = Department::with(['provinces.districts'])->get();
        $subcategories = SubCategory::with('typecategory.category')->where('estado', 1)->get();

        return Inertia::render('Place/Index', compact('places', 'departments', 'subcategories'));
    }

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
