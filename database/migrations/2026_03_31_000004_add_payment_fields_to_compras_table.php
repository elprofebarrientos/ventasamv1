<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->string('estado')->default('pendiente')->after('total_neto_pagar');
            $table->timestamp('fecha_pago')->nullable()->after('estado');
            $table->string('metodo_pago')->nullable()->after('fecha_pago'); // efectivo | transferencia
            $table->decimal('monto_pagado', 15, 2)->nullable()->after('metodo_pago');
            $table->string('comprobante_pago')->nullable()->after('monto_pagado'); // path to file
        });
    }

    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn(['estado', 'fecha_pago', 'metodo_pago', 'monto_pagado', 'comprobante_pago']);
        });
    }
};
