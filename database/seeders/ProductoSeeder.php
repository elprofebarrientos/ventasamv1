<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $categoria = Categoria::where('nombre', 'Hombres')->first();
        $marca = Marca::where('nombre', 'Adidas')->first();
        $unidad = UnidadMedida::where('nombre', 'Unidad')->first();

        $producto = Producto::create([
            'nombre' => 'Zapatos Adizero',
            'slug' => 'zapatos-adizero',
            'descripcion' => 'Zapatos para correr en asfalto',
            'imagen' => 'https://example.com/images/zapatos-adizero.jpg',
            'id_categoria' => $categoria?->id_categoria,
            'id_marca' => $marca?->id_marca,
            'id_unidad_medida' => $unidad?->id_unidad_medida,
            'estado' => 'disponible',
            'permite_venta' => true,
        ]);
    }
}