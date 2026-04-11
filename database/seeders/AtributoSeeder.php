<?php

namespace Database\Seeders;

use App\Models\Atributo;
use App\Models\AtributoValor;
use Illuminate\Database\Seeder;

class AtributoSeeder extends Seeder
{
    public function run(): void
    {
        $atributoColor = Atributo::firstOrCreate(['nombre' => 'Color'], ['tipo_visual' => 'COLOR', 'estado' => true]);
        $talla = Atributo::firstOrCreate(['nombre' => 'Talla'], ['tipo_visual' => 'TEXTO', 'estado' => true]);

        $tallasNumericas = ['37', '38', '39', '40', '41', '42', '43', '44'];
        $tallasLetras = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'];

        $colores = [
            ['valor' => 'Rojo', 'codigo_hex' => '#FF0000'],
            ['valor' => 'Negro', 'codigo_hex' => '#000000'],
            ['valor' => 'Blanco', 'codigo_hex' => '#FFFFFF'],
            ['valor' => 'Azul', 'codigo_hex' => '#0000FF'],
            ['valor' => 'Amarillo', 'codigo_hex' => '#FFFF00'],
        ];

        foreach ($tallasNumericas as $valor) {
            AtributoValor::firstOrCreate(
                ['id_atributo' => $talla->id_atributo, 'valor' => $valor],
                ['estado' => true]
            );
        }

        foreach ($tallasLetras as $valor) {
            AtributoValor::firstOrCreate(
                ['id_atributo' => $talla->id_atributo, 'valor' => $valor],
                ['estado' => true]
            );
        }

        foreach ($colores as $colorData) {
            AtributoValor::firstOrCreate(
                ['id_atributo' => $atributoColor->id_atributo, 'valor' => $colorData['valor']],
                ['codigo_hex' => $colorData['codigo_hex'], 'estado' => true]
            );
        }
    }
}