<?php

namespace Database\Seeders;

use App\Models\TypeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeCategorySeeder extends Seeder
{

    public function run(): void
    {
        TypeCategory::create(['nombre' => 'Sitios Arqueológicos', 'category_id' => 1]);
        TypeCategory::create(['nombre' => 'Eventos', 'category_id' => 2]);
        TypeCategory::create(['nombre' => 'Lugares Históricos', 'category_id' => 1]);
        TypeCategory::create(['nombre' => 'Fiestas', 'category_id' => 2]);
        TypeCategory::create(['nombre' => 'Arquitectura y Espacios Urbanos', 'category_id' => 2]);
        TypeCategory::create(['nombre' => 'Museos y otros', 'category_id' => 1]);
        TypeCategory::create(['nombre' => 'Costas', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Creencias Populares', 'category_id' => 4]);
        TypeCategory::create(['nombre' => 'Áreas Protegidas', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Otros', 'category_id' => 5]);
        TypeCategory::create(['nombre' => 'Artesanía y Artes', 'category_id' => 5]);
        TypeCategory::create(['nombre' => 'Centros Científicos y Técnicos', 'category_id' => 5]);
        TypeCategory::create(['nombre' => 'Gastronomía', 'category_id' => 4]);
        TypeCategory::create(['nombre' => 'Músicas y Danzas', 'category_id' => 4]);
        TypeCategory::create(['nombre' => 'Artísticos', 'category_id' => 2]);
        TypeCategory::create(['nombre' => 'Montañas', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Cuerpo de Agua', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Pueblos', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Zonas paisajísticas', 'category_id' => 3]);
        TypeCategory::create(['nombre' => 'Caídas de agua', 'category_id' => 3]);
    }
}
