<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventario_ubicacion', function (Blueprint $table) {
            $table->decimal('cantidad_pedida', 12, 2)->default(0)->after('stock_reservado');
            $table->decimal('cantidad_recibida', 12, 2)->default(0)->after('cantidad_pedida');
            $table->decimal('cantidad_pendiente', 12, 2)->virtualAs('cantidad_pedida - cantidad_recibida')->after('cantidad_recibida');
        });
    }

    public function down(): void
    {
        Schema::table('inventario_ubicacion', function (Blueprint $table) {
            $table->dropColumn(['cantidad_pedida', 'cantidad_recibida', 'cantidad_pendiente']);
        });
    }
};