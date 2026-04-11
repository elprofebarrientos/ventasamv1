<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use App\Models\Bodega;
use Illuminate\Database\Seeder;

class UbicacionSeeder extends Seeder
{
    public function run(): void
    {
        $bodega = Bodega::where('nombre', 'Bodega Principal')->first();

        if (!$bodega) {
            return;
        }

        $zona = Ubicacion::firstOrCreate(
            ['id_bodega' => $bodega->id_bodega, 'nombre' => 'Zona Principal'],
            ['tipo' => 'zona', 'codigo' => 'ZONA-001', 'estado' => true]
        );

        $pasillos = ['A', 'B', 'C'];
        foreach ($pasillos as $pasillo) {
            $ubicacionPasillo = Ubicacion::firstOrCreate(
                ['id_bodega' => $bodega->id_bodega, 'nombre' => 'Pasillo ' . $pasillo, 'id_padre' => $zona->id_ubicacion],
                ['tipo' => 'pasillo', 'codigo' => 'PASILLO-' . $pasillo, 'estado' => true]
            );

            for ($estante = 1; $estante <= 3; $estante++) {
                $ubicacionEstante = Ubicacion::firstOrCreate(
                    ['id_bodega' => $bodega->id_bodega, 'nombre' => 'Estante ' . $estante, 'id_padre' => $ubicacionPasillo->id_ubicacion],
                    ['tipo' => 'estante', 'codigo' => 'EST-' . $pasillo . $estante, 'estado' => true]
                );

                for ($nivel = 1; $nivel <= 4; $nivel++) {
                    Ubicacion::firstOrCreate(
                        ['id_bodega' => $bodega->id_bodega, 'nombre' => 'Nivel ' . $nivel, 'id_padre' => $ubicacionEstante->id_ubicacion],
                        ['tipo' => 'nivel', 'codigo' => 'NIVEL-' . $pasillo . $estante . $nivel, 'estado' => true]
                    );
                }
            }
        }
    }
}