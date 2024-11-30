<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Verificar si el usuario tiene el rol de administrador
        if (!$request->user() || !$request->user()->roles->contains('name', 'admin')) {
            // Redirigir a la página de "Sobre Nosotros" si no es administrador
            return redirect()->route('about');
        }

        // Obtener los lugares más visitados
        $mostVisitedPlaces = DB::table('visits')
            ->join('places', 'visits.place_id', '=', 'places.id')
            ->select('places.nombre', DB::raw('count(visits.id) as visit_count'))
            ->groupBy('places.nombre')
            ->orderBy('visit_count', 'desc')
            ->take(10)
            ->get();

        // Obtener visitas diarias del mes actual
        $currentMonthVisits = DB::table('visits')
            ->select(DB::raw('DAY(created_at) as day'), DB::raw('count(id) as visit_count'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        return Inertia::render('Dashboard', [
            'mostVisitedPlaces' => $mostVisitedPlaces,
            'currentMonthVisits' => $currentMonthVisits
        ]);
    }
}
