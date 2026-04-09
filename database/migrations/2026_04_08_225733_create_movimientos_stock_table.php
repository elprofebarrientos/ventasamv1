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
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id('id_movimiento');

            $table->unsignedBigInteger('id_variante');

            $table->unsignedBigInteger('id_bodega_origen')->nullable();
            $table->unsignedBigInteger('id_bodega_destino')->nullable();

            $table->unsignedBigInteger('id_ubicacion_origen')->nullable();
            $table->unsignedBigInteger('id_ubicacion_destino')->nullable();

            $table->decimal('cantidad', 12, 2);
            $table->string('tipo'); // ENTRADA, SALIDA, TRANSFERENCIA
            $table->string('referencia')->nullable(); // id_recepcion, venta, etc.

            $table->timestamps();

            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_stock');
    }
};
