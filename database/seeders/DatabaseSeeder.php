<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UnidadMedidaSeeder;
use Database\Seeders\MarcaSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\EmpresaSucursalBodegaSeeder;
use Database\Seeders\CategoriaSeeder;
use Database\Seeders\ProveedorSeeder;
use Database\Seeders\AtributoSeeder;
use Database\Seeders\ImpuestoSeeder;
use Database\Seeders\UbicacionSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            CountrySeeder::class,
            DepartmentSeeder::class,
            CitySeeder::class,
            UnidadMedidaSeeder::class,
            MarcaSeeder::class,
            EmpresaSucursalBodegaSeeder::class,
            CategoriaSeeder::class,
            ProveedorSeeder::class,
            AtributoSeeder::class,
            ImpuestoSeeder::class,
            UbicacionSeeder::class,
        ]);
    }
}
