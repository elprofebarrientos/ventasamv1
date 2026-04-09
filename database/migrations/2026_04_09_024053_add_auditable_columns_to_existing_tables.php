<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tablesToUpdate = [
            'categorias',
            'productos',
            'atributos',
            'proveedores',
            'compras_detalle',
        ];

        foreach ($tablesToUpdate as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'created_by')) {
                Schema::table($tableName, function (Blueprint $blueprint) {
                    $blueprint->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                    $blueprint->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                });
            }
        }
    }

    public function down(): void
    {
        $tablesToUpdate = [
            'categorias',
            'productos',
            'atributos',
            'proveedores',
            'compras_detalle',
        ];

        foreach ($tablesToUpdate as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'created_by')) {
                Schema::table($tableName, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['created_by']);
                    $blueprint->dropForeign(['updated_by']);
                    $blueprint->dropColumn(['created_by', 'updated_by']);
                });
            }
        }
    }
};
