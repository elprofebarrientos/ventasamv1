<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Categoria::create(['nombre' => 'Hombres', 'nivel' => 1, 'activo' => true]);
        Categoria::create(['nombre' => 'Mujeres', 'nivel' => 1, 'activo' => true]);
    }
}