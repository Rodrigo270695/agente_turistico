<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TypeCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('about');
});

Route::get('/about', [WelcomeController::class, 'index'])->name('about');
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::get('/placesclient', [WelcomeController::class, 'places'])->name('places');
Route::get('/placesclient/search', [WelcomeController::class, 'search'])->name('placesclient.search');
Route::get('/placesclient/{id}', [WelcomeController::class, 'show'])->name('placesclient.show');
Route::post('/visits', [VisitController::class, 'store'])->name('visit.store');
Route::get('/visits', [VisitController::class, 'index'])->name('visits.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    /* Categorias */
    Route::get('categorias/search', [CategoryController::class, 'search' ])->name('categorias.search');
    Route::resource('categorias', CategoryController::class);
    Route::put('categorias/change/{category}', [CategoryController::class, 'change'])->name('categorias.change');

    /* Tipo de categorias */
    Route::get('tipocategorias/search', [TypeCategoryController::class, 'search' ])->name('tipocategorias.search');
    Route::resource('tipocategorias', TypeCategoryController::class);
    Route::put('tipocategorias/change/{types}', [TypeCategoryController::class, 'change'])->name('tipocategorias.change');

    /* Sub Categoria */
    Route::get('subcategorias/search', [SubCategoryController::class, 'search' ])->name('subcategorias.search');
    Route::resource('subcategorias', SubCategoryController::class);
    Route::put('subcategorias/change/{subcategory}', [SubCategoryController::class, 'change'])->name('subcategorias.change');

    /* Lugares */
    Route::get('lugares/search', [PlaceController::class, 'search' ])->name('lugares.search');
    Route::resource('lugares', PlaceController::class);
    Route::put('lugares/change/{place}', [PlaceController::class, 'change'])->name('lugares.change');
    Route::get('lugares/{id}/photos', [PlaceController::class, 'photos'])->name('places.photos');
    Route::get('lugares/{id}/prices', [PlaceController::class, 'prices'])->name('places.prices');
    Route::resource('lugares/photos', PhotoController::class);
    Route::resource('lugares/prices', PriceController::class);

    /* User */
    Route::get('usuarios/search', [UserController::class, 'search' ])->name('usuarios.search');
    Route::resource('usuarios', UserController::class);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* Noticias */
    Route::get('noticias/search', [NewsController::class, 'search' ])->name('noticias.search');
    Route::resource('noticias', NewsController::class);
    Route::put('noticias/change/{news}', [NewsController::class, 'change'])->name('noticias.change');

});
