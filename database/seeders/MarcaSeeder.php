<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            // Electrónica y Tecnología
            ['nombre' => 'Samsung', 'descripcion' => 'Electrónica y tecnología de consumo'],
            ['nombre' => 'Apple', 'descripcion' => 'Productos electrónicos y software'],
            ['nombre' => 'Sony', 'descripcion' => 'Electrónica, videojuegos y entretenimiento'],
            ['nombre' => 'LG', 'descripcion' => 'Electrónica de consumo y electrodomésticos'],
            ['nombre' => 'Xiaomi', 'descripcion' => 'Electrónica y tecnología inteligente'],
            ['nombre' => 'Huawei', 'descripcion' => 'Telecomunicaciones y electrónica'],
            ['nombre' => 'Lenovo', 'descripcion' => 'Computadores y tecnología'],
            ['nombre' => 'Dell', 'descripcion' => 'Computadores y tecnología empresarial'],
            ['nombre' => 'HP', 'descripcion' => 'Computadores e impresoras'],
            ['nombre' => 'Asus', 'descripcion' => 'Computadores y componentes'],
            
            // Electrodomésticos
            ['nombre' => 'Whirlpool', 'descripcion' => 'Electrodomésticos'],
            ['nombre' => 'Electrolux', 'descripcion' => 'Electrodomésticos'],
            ['nombre' => 'Mabe', 'descripcion' => 'Electrodomésticos línea blanca'],
            ['nombre' => 'Indurama', 'descripcion' => 'Electrodomésticos de cocina'],
            ['nombre' => 'Haceb', 'descripcion' => 'Electrodomésticos colombianos'],
            
            // Automotriz
            ['nombre' => 'Michelin', 'descripcion' => 'Neumáticos y servicios de movilidad'],
            ['nombre' => 'Bosch', 'descripcion' => 'Tecnología automotriz e industrial'],
            ['nombre' => 'Castrol', 'descripcion' => 'Lubricantes automotrices'],
            ['nombre' => 'Monroe', 'descripcion' => 'Refacciones para automóviles'],
            
            // Alimentos y Bebidas
            ['nombre' => 'Coca-Cola', 'descripcion' => 'Bebidas gaseosas'],
            ['nombre' => 'Pepsi', 'descripcion' => 'Bebidas y snacks'],
            ['nombre' => 'Nestlé', 'descripcion' => 'Alimentos y bebidas'],
            ['nombre' => 'Mondelez', 'descripcion' => 'Snacks y confitería'],
            ['nombre' => 'Unilever', 'descripcion' => 'Bienes de consumo'],
            
            // Ropa y Accesorios
            ['nombre' => 'Nike', 'descripcion' => 'Deportivos y ropa'],
            ['nombre' => 'Adidas', 'descripcion' => 'Deportivos y ropa'],
            ['nombre' => 'Puma', 'descripcion' => 'Deportivos y ropa'],
            ['nombre' => 'Levi\'s', 'descripcion' => 'Jeans y ropa casual'],
            
            // Hogar y Construcción
            ['nombre' => 'Cerro', 'descripcion' => 'Materiales de construcción'],
            ['nombre' => 'Pavco', 'descripcion' => 'Tuberías y conexiones'],
            ['nombre' => 'Corona', 'descripcion' => 'Sanitarios y cerámica'],
            ['nombre' => 'Toto', 'descripcion' => 'Sanitarios de lujo'],
            
            // Farmacéutica
            ['nombre' => 'Pfizer', 'descripcion' => 'Farmacéutica'],
            ['nombre' => 'Bayer', 'descripcion' => 'Farmacéutica y ciencias de la vida'],
            ['nombre' => 'Roche', 'descripcion' => 'Farmacéutica'],
            
            // Varios
            ['nombre' => '3M', 'descripcion' => 'Tecnología y productos diversificados'],
            ['nombre' => 'Scotch-Brite', 'descripcion' => 'Productos de limpieza'],
            ['nombre' => 'Scotch', 'descripcion' => 'Cintas y adhesivos'],
        ];

        foreach ($marcas as $marca) {
            Marca::updateOrCreate(
                ['nombre' => $marca['nombre']],
                $marca
            );
        }
    }
}