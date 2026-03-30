<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // Major cities in Colombia
        // Department IDs based on order in DepartmentSeeder:
        // 1=Amazonas, 2=Antioquia, 3=Arauca, 4=Atlántico, 5=Bolívar, 6=Boyacá, 7=Caldas, 8=Caquetá,
        // 9=Casanare, 10=Cauca, 11=Cesar, 12=Chocó, 13=Córdoba, 14=Cundinamarca, 15=Guainía,
        // 16=Guaviare, 17=Huila, 18=La Guajira, 19=Magdalena, 20=Meta, 21=Nariño, 22=Norte de Santander,
        // 23=Putumayo, 24=Quindío, 25=Risaralda, 26=San Andrés y Providencia, 27=Santander, 28=Sucre,
        // 29=Tolima, 30=Valle del Cauca, 31=Vaupés, 32=Vichada

        $cities = [
            // Antioquia (id_department = 2)
            ['id_department' => 2, 'name' => 'Medellín', 'code' => '05001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 2, 'name' => 'Bello', 'code' => '05088', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 2, 'name' => 'Itagüí', 'code' => '05360', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 2, 'name' => 'Envigado', 'code' => '05266', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 2, 'name' => 'Apartadó', 'code' => '05045', 'created_at' => now(), 'updated_at' => now()],

            // Atlántico (id_department = 4)
            ['id_department' => 4, 'name' => 'Barranquilla', 'code' => '08001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 4, 'name' => 'Soledad', 'code' => '08758', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 4, 'name' => 'Malambo', 'code' => '08433', 'created_at' => now(), 'updated_at' => now()],

            // Bolívar (id_department = 5)
            ['id_department' => 5, 'name' => 'Cartagena', 'code' => '13001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 5, 'name' => 'Magangué', 'code' => '13430', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 5, 'name' => 'Turbaco', 'code' => '13836', 'created_at' => now(), 'updated_at' => now()],

            // Boyacá (id_department = 6)
            ['id_department' => 6, 'name' => 'Tunja', 'code' => '15001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 6, 'name' => 'Duitama', 'code' => '15238', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 6, 'name' => 'Sogamoso', 'code' => '15759', 'created_at' => now(), 'updated_at' => now()],

            // Caldas (id_department = 7)
            ['id_department' => 7, 'name' => 'Manizales', 'code' => '17001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 7, 'name' => 'Chinchiná', 'code' => '17174', 'created_at' => now(), 'updated_at' => now()],

            // Cundinamarca (id_department = 14)
            ['id_department' => 14, 'name' => 'Bogotá', 'code' => '25001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 14, 'name' => 'Soacha', 'code' => '25754', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 14, 'name' => 'Zipaquirá', 'code' => '25899', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 14, 'name' => 'Facatativá', 'code' => '25269', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 14, 'name' => 'Girardot', 'code' => '25307', 'created_at' => now(), 'updated_at' => now()],

            // Huila (id_department = 17)
            ['id_department' => 17, 'name' => 'Neiva', 'code' => '41001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 17, 'name' => 'Pitalito', 'code' => '41551', 'created_at' => now(), 'updated_at' => now()],

            // Magdalena (id_department = 19)
            ['id_department' => 19, 'name' => 'Santa Marta', 'code' => '47001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 19, 'name' => 'Ciénaga', 'code' => '47189', 'created_at' => now(), 'updated_at' => now()],

            // Meta (id_department = 20)
            ['id_department' => 20, 'name' => 'Villavicencio', 'code' => '50001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 20, 'name' => 'Acacías', 'code' => '50006', 'created_at' => now(), 'updated_at' => now()],

            // Nariño (id_department = 21)
            ['id_department' => 21, 'name' => 'Pasto', 'code' => '52001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 21, 'name' => 'Tumaco', 'code' => '52835', 'created_at' => now(), 'updated_at' => now()],

            // Norte de Santander (id_department = 22)
            ['id_department' => 22, 'name' => 'Cúcuta', 'code' => '54001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 22, 'name' => 'Ocaña', 'code' => '54498', 'created_at' => now(), 'updated_at' => now()],

            // Quindío (id_department = 24)
            ['id_department' => 24, 'name' => 'Armenia', 'code' => '63001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 24, 'name' => 'Calarcá', 'code' => '63130', 'created_at' => now(), 'updated_at' => now()],

            // Risaralda (id_department = 25)
            ['id_department' => 25, 'name' => 'Pereira', 'code' => '66001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 25, 'name' => 'Dosquebradas', 'code' => '66170', 'created_at' => now(), 'updated_at' => now()],

            // Santander (id_department = 27)
            ['id_department' => 27, 'name' => 'Bucaramanga', 'code' => '68001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 27, 'name' => 'Floridablanca', 'code' => '68276', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 27, 'name' => 'Girón', 'code' => '68307', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 27, 'name' => 'Barrancabermeja', 'code' => '68081', 'created_at' => now(), 'updated_at' => now()],

            // Sucre (id_department = 28)
            ['id_department' => 28, 'name' => 'Sincelejo', 'code' => '70001', 'created_at' => now(), 'updated_at' => now()],

            // Tolima (id_department = 29)
            ['id_department' => 29, 'name' => 'Ibagué', 'code' => '73001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 29, 'name' => 'Espinal', 'code' => '73268', 'created_at' => now(), 'updated_at' => now()],

            // Valle del Cauca (id_department = 30)
            ['id_department' => 30, 'name' => 'Cali', 'code' => '76001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 30, 'name' => 'Buenaventura', 'code' => '76109', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 30, 'name' => 'Palmira', 'code' => '76520', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 30, 'name' => 'Tuluá', 'code' => '76834', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 30, 'name' => 'Buga', 'code' => '76111', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 30, 'name' => 'Cartago', 'code' => '76147', 'created_at' => now(), 'updated_at' => now()],

            // Cesar (id_department = 11)
            ['id_department' => 11, 'name' => 'Valledupar', 'code' => '20001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 11, 'name' => 'Aguachica', 'code' => '20011', 'created_at' => now(), 'updated_at' => now()],

            // Córdoba (id_department = 13)
            ['id_department' => 13, 'name' => 'Montería', 'code' => '23001', 'created_at' => now(), 'updated_at' => now()],
            ['id_department' => 13, 'name' => 'Lorica', 'code' => '23417', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('cities')->insert($cities);
    }
}
