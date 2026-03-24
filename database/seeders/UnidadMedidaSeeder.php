<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            // Unidades de cantidad/unidad
            ['nombre' => 'Unidad', 'abreviatura' => 'und', 'tipo' => 'unidad'],
            ['nombre' => 'Pieza', 'abreviatura' => 'pza', 'tipo' => 'unidad'],
            ['nombre' => 'Par', 'abreviatura' => 'par', 'tipo' => 'unidad'],
            ['nombre' => 'Juego', 'abreviatura' => 'jgo', 'tipo' => 'unidad'],
            ['nombre' => 'Docena', 'abreviatura' => 'dzn', 'tipo' => 'unidad'],
            ['nombre' => 'Caja', 'abreviatura' => 'cja', 'tipo' => 'unidad'],
            ['nombre' => 'Paquete', 'abreviatura' => 'paq', 'tipo' => 'unidad'],
            ['nombre' => 'Bulto', 'abreviatura' => 'blt', 'tipo' => 'unidad'],
            ['nombre' => 'Saco', 'abreviatura' => 'sco', 'tipo' => 'unidad'],
            ['nombre' => 'Rollo', 'abreviatura' => 'rll', 'tipo' => 'unidad'],
            
            // Unidades de peso
            ['nombre' => 'Kilogramo', 'abreviatura' => 'kg', 'tipo' => 'peso'],
            ['nombre' => 'Gramo', 'abreviatura' => 'g', 'tipo' => 'peso'],
            ['nombre' => 'Libra', 'abreviatura' => 'lb', 'tipo' => 'peso'],
            ['nombre' => 'Onza', 'abreviatura' => 'oz', 'tipo' => 'peso'],
            ['nombre' => 'Tonelada', 'abreviatura' => 't', 'tipo' => 'peso'],
            ['nombre' => 'Quintal', 'abreviatura' => 'qq', 'tipo' => 'peso'],
            
            // Unidades de volumen
            ['nombre' => 'Litro', 'abreviatura' => 'L', 'tipo' => 'volumen'],
            ['nombre' => 'Mililitro', 'abreviatura' => 'mL', 'tipo' => 'volumen'],
            ['nombre' => 'Galón', 'abreviatura' => 'gal', 'tipo' => 'volumen'],
            ['nombre' => 'Barril', 'abreviatura' => 'bbl', 'tipo' => 'volumen'],
            ['nombre' => 'Metro cúbico', 'abreviatura' => 'm³', 'tipo' => 'volumen'],
            
            // Unidades de longitud
            ['nombre' => 'Metro', 'abreviatura' => 'm', 'tipo' => 'longitud'],
            ['nombre' => 'Centímetro', 'abreviatura' => 'cm', 'tipo' => 'longitud'],
            ['nombre' => 'Milímetro', 'abreviatura' => 'mm', 'tipo' => 'longitud'],
            ['nombre' => 'Pulgada', 'abreviatura' => 'in', 'tipo' => 'longitud'],
            ['nombre' => 'Pie', 'abreviatura' => 'ft', 'tipo' => 'longitud'],
            ['nombre' => 'Yarda', 'abreviatura' => 'yd', 'tipo' => 'longitud'],
            
            // Unidades de superficie
            ['nombre' => 'Metro cuadrado', 'abreviatura' => 'm²', 'tipo' => 'superficie'],
            ['nombre' => 'Centímetro cuadrado', 'abreviatura' => 'cm²', 'tipo' => 'superficie'],
            ['nombre' => 'Hectárea', 'abreviatura' => 'ha', 'tipo' => 'superficie'],
            ['nombre' => 'Acre', 'abreviatura' => 'ac', 'tipo' => 'superficie'],
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::updateOrCreate(
                ['abreviatura' => $unidad['abreviatura']],
                $unidad
            );
        }
    }
}