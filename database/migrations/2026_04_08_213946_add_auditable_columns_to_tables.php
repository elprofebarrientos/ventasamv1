<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'empresa', 'sucursal', 'bodega', 'categoria', 'marca', 
            'producto', 'atributo', 'atributo_valor', 'variante_valor',
            'producto_variante', 'variante_precio', 'unidad_medida',
            'impuesto', 'proveedor', 'compras', 'compra_detalles',
            'abonos', 'codigo_cliente', 'codigo_uso'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $blueprint) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'created_by')) {
                        $blueprint->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                    }
                    if (!Schema::hasColumn($tableName, 'updated_by')) {
                        $blueprint->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'empresa', 'sucursal', 'bodega', 'categoria', 'marca', 
            'producto', 'atributo', 'atributo_valor', 'variante_valor',
            'producto_variante', 'variante_precio', 'unidad_medida',
            'impuesto', 'proveedor', 'compras', 'compra_detalles',
            'abonos', 'codigo_cliente', 'codigo_uso'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['created_by']);
                    $blueprint->dropForeign(['updated_by']);
                    $blueprint->dropColumn(['created_by', 'updated_by']);
                });
            }
        }
    }
};
