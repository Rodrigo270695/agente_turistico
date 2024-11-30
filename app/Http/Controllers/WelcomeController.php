<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Department;
use App\Models\News;
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
        $news = News::with('district.province.department')->where('estado', 1)->orderBy('created_at', 'desc')->get();
        return Inertia::render('About', compact('news'));
    }

    public function places()
    {
        $places = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos', 'prices')
            ->where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $departments = Department::with(['provinces.districts'])->get();
        $categories = Category::with('typecategories')->where('estado', 1)->orderBy('nombre', 'asc')->get();

        $user = auth()->user();
        $recommendedPlaces = collect(); // Asegúrate de inicializar la variable

        if ($user) {
            $lastVisit = $user->visits()->orderBy('created_at', 'desc')->first();
            if ($lastVisit) {
                $lastPlaceId = $lastVisit->place_id;
                $recommendations = Http::get('http://127.0.0.1:5000/recomendaciones/' . $lastPlaceId)->json();
                $recommendedPlaces = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos', 'prices')
                    ->whereIn('id', $recommendations['recomendaciones'])
                    ->get();
            }
        }

        return Inertia::render('PlacesCliente/Index', compact('places', 'departments', 'categories', 'recommendedPlaces'));
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
        $recommendedPlaces = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos', 'prices')->whereIn('id', $recommendations['recomendaciones'])->get();

        return Inertia::render('PlacesCliente/ShowPlace', compact('place', 'recommendedPlaces'));
    }

    public function search(Request $request)
    {
        $query = Place::query()->with('subcategory.typecategory.category', 'district.province.department', 'photos', 'prices');

        if ($request->filled('nombre')) {
            $query->where('places.nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('distrito')) {
            $query->join('districts as d', 'places.district_id', '=', 'd.id')
                ->where('d.name', $request->distrito);
        } elseif ($request->filled('provincia')) {
            $query->join('districts', 'places.district_id', '=', 'districts.id')
                ->join('provinces', 'districts.province_id', '=', 'provinces.id')
                ->where('provinces.name', $request->provincia);
        }

        if ($request->filled('tipo')) {
            $query->join('sub_categories as s', 'places.sub_category_id', '=', 's.id')
                ->join('type_categories as t', 's.type_category_id', '=', 't.id')
                ->where('t.nombre', $request->tipo);
        } elseif ($request->filled('categoria')) {
            $query->join('sub_categories', 'places.sub_category_id', '=', 'sub_categories.id')
                ->join('type_categories', 'sub_categories.type_category_id', '=', 'type_categories.id')
                ->join('categories', 'type_categories.category_id', '=', 'categories.id')
                ->where('categories.nombre', $request->categoria);
        }

        if ($request->filled('distancia')) {
            switch ($request->distancia) {
                case 'menos de 30 min':
                    $query->whereRaw('TIME_TO_SEC(distancia_horas) < ?', [30 * 60]);
                    break;
                case '30 min a 1 hora':
                    $query->whereRaw('TIME_TO_SEC(distancia_horas) BETWEEN ? AND ?', [30 * 60, 60 * 60]);
                    break;
                case 'más de 1 hora':
                    $query->whereRaw('TIME_TO_SEC(distancia_horas) > ?', [60 * 60]);
                    break;
            }
        }

        $places = $query->select('places.*')->paginate(15)->appends($request->all());

        $user = auth()->user();
        if ($user) {
            $lastVisit = $user->visits()->orderBy('created_at', 'desc')->first();
            if ($lastVisit) {
                $lastPlaceId = $lastVisit->place_id;
                $recommendations = Http::get('http://127.0.0.1:5000/recomendaciones/' . $lastPlaceId)->json();
                $recommendedPlaces = Place::with('subcategory.typecategory.category', 'district.province.department', 'photos', 'prices')->whereIn('id', $recommendations['recomendaciones'])->get();
            }
        } else {
            $recommendedPlaces = collect();
        }

        return Inertia::render('PlacesCliente/Index', [
            'places' => $places,
            'departments' => Department::with('provinces.districts')->get(),
            'categories' => Category::with('typecategories')->get(),
            'searchParams' => $request->all(), // Añadir los parámetros de búsqueda
            'recommendedPlaces' => $recommendedPlaces
        ]);
    }
}
