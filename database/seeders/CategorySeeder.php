<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run(): void
    {
        Category::create(['nombre' => 'Manifestaciones Culturales']);
        Category::create(['nombre' => 'Acontecimientos Programados']);
        Category::create(['nombre' => 'Sitios Naturales']);
        Category::create(['nombre' => 'Folclore']);
        Category::create(['nombre' => 'Realizaciones Técnicas, Científicas y Artísticas Contemporáneas']);
    }
}
