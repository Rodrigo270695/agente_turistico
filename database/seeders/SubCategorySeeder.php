<?php

namespace Database\Seeders;

use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        SubCategory::create(['nombre' => 'Templos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Petroglifos (grabado en piedra)', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Pinturas rupestres', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Festivales', 'type_category_id' => 2]);
        SubCategory::create(['nombre' => 'Conciertos', 'type_category_id' => 2]);
        SubCategory::create(['nombre' => 'Carnavales', 'type_category_id' => 2]);
        SubCategory::create(['nombre' => 'Castillos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Sitios Históricos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Museos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Galerías de Arte', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Playas', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Islas', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Montañas', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Ríos', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Lagos', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Bosques', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Parques Nacionales', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Reservas Naturales', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Áreas de Conservación', 'type_category_id' => 3]);
        SubCategory::create(['nombre' => 'Monumentos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Estatuas', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Puentes', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Plazas', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Teatros', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Bibliotecas', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Cafés Históricos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Bares Históricos', 'type_category_id' => 1]);
        SubCategory::create(['nombre' => 'Mercados', 'type_category_id' => 4]);
        SubCategory::create(['nombre' => 'Ferias', 'type_category_id' => 4]);
        SubCategory::create(['nombre' => 'Fiestas Populares', 'type_category_id' => 4]);
    }
}
