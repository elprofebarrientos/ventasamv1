<?php

namespace Database\Seeders;

use App\Models\Impuesto;
use Illuminate\Database\Seeder;

class ImpuestoSeeder extends Seeder
{
    public function run(): void
    {
        $impuestos = [
            ['nombre' => 'IVA 19%', 'tipo' => 'porcentaje', 'valor' => 19, 'activo' => true],
            ['nombre' => 'IVA 5%', 'tipo' => 'porcentaje', 'valor' => 5, 'activo' => true],
            ['nombre' => 'Impuesto al Consumo 4%', 'tipo' => 'porcentaje', 'valor' => 4, 'activo' => true],
            ['nombre' => 'Imp Consumo Bolsa', 'tipo' => 'fijo', 'valor' => 73, 'activo' => true],
        ];

        foreach ($impuestos as $impuesto) {
            Impuesto::firstOrCreate(
                ['nombre' => $impuesto['nombre']],
                $impuesto
            );
        }
    }
}