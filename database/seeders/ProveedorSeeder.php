<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        Proveedor::create([
            'tipo_documento' => 'NIT',
            'numero_documento' => '900123456',
            'digito_verificacion' => 1,
            'razon_social' => 'DISTRIBUIDORA TEXTIL S.A.S.',
            'nombre_comercial' => 'Distribuidora Textil',
            'responsabilidad_fiscal' => 'Responsable IVA',
            'es_iva_responsable' => true,
            'es_autoretenedor' => false,
            'correo_facturacion' => 'facturas@disttextil.com',
            'telefono' => '6012345678',
            'direccion' => 'Calle 45 # 12-34, Bogotá D.C.',
            'codigo_postal' => '110111',
            'id_pais' => 1,
            'id_departamento' => 1,
            'id_municipio' => 1,
            'plazo_pago_dias' => 30,
            'cupo_credito' => 5000000,
            'estado' => true,
        ]);
    }
}