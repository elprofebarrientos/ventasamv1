<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Sucursal;
use App\Models\Bodega;
use Illuminate\Database\Seeder;

class EmpresaSucursalBodegaSeeder extends Seeder
{
    public function run(): void
    {
        $empresa = Empresa::create([
            'nit' => '9001234567',
            'digito_verificacion' => '8',
            'razon_social' => 'COMERCIAL AMV S.A.S.',
            'nombre_comercial' => 'AMV comercial',
            'direccion_fisica' => 'Carrera 45 # 23-56, Bogotá D.C.',
            'telefono_contacto' => '6011234567',
            'email_corporativo' => 'contacto@amv.com',
            'email_facturacion' => 'facturas@amv.com',
            'sitio_web' => 'www.amv.com',
            'representante_legal' => 'Juan Pérez García',
            'cedula_representante' => '1012345678',
            'regimen_fiscal' => 'responsable_iva',
            'resolucion_dian' => 'RES001',
            'rango_inicio' => 1,
            'rango_fin' => 10000,
            'id_city' => 1,
        ]);

        $sucursal = Sucursal::create([
            'id_empresa' => $empresa->id_empresa,
            'nombre' => 'Sede Principal',
            'direccion' => 'Carrera 45 # 23-56, Bogotá D.C.',
            'telefono' => '6011234567',
            'estado' => 'activa',
        ]);

        Bodega::create([
            'id_sucursal' => $sucursal->id_sucursal,
            'nombre' => 'Bodega Principal',
            'codigo' => 'BODEGA-001',
            'responsable' => 'Almacenista Principal',
            'estado' => 'activa',
        ]);
    }
}