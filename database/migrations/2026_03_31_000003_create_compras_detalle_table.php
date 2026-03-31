<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras_detalle', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_variante');
            $table->decimal('cantidad', 12, 2);
            $table->decimal('costo_unitario', 18, 2)->comment('Sin IVA');
            $table->decimal('porcentaje_iva', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_compra')->references('id_compra')->on('compras')->cascadeOnDelete();
            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras_detalle');
    }
};
