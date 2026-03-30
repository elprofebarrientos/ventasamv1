<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Colombia departments (id_country = 1 for Colombia)
        $departments = [
            ['id_country' => 1, 'name' => 'Amazonas', 'code' => '91', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Antioquia', 'code' => '05', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Arauca', 'code' => '81', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Atlántico', 'code' => '08', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Bolívar', 'code' => '13', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Boyacá', 'code' => '15', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Caldas', 'code' => '17', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Caquetá', 'code' => '18', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Casanare', 'code' => '85', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Cauca', 'code' => '19', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Cesar', 'code' => '20', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Chocó', 'code' => '27', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Córdoba', 'code' => '23', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Cundinamarca', 'code' => '25', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Guainía', 'code' => '94', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Guaviare', 'code' => '95', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Huila', 'code' => '41', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'La Guajira', 'code' => '44', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Magdalena', 'code' => '47', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Meta', 'code' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Nariño', 'code' => '52', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Norte de Santander', 'code' => '54', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Putumayo', 'code' => '86', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Quindío', 'code' => '63', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Risaralda', 'code' => '66', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'San Andrés y Providencia', 'code' => '88', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Santander', 'code' => '68', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Sucre', 'code' => '70', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Tolima', 'code' => '73', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Valle del Cauca', 'code' => '76', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Vaupés', 'code' => '97', 'created_at' => now(), 'updated_at' => now()],
            ['id_country' => 1, 'name' => 'Vichada', 'code' => '99', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('departments')->insert($departments);
    }
}
