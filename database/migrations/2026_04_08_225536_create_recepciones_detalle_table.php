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
        Schema::create('recepciones_detalle', function (Blueprint $table) {
            $table->id('id_detalle');
            $table->unsignedBigInteger('id_recepcion');
            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_bodega');
            $table->unsignedBigInteger('id_ubicacion');

            $table->decimal('cantidad_recibida', 12, 2);
            $table->string('lote')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();

            $table->foreign('id_recepcion')->references('id_recepcion')->on('recepciones_compra')->cascadeOnDelete();
            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->cascadeOnDelete();
            $table->foreign('id_bodega')->references('id_bodega')->on('bodega')->cascadeOnDelete();
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones_detalle');
    }
};
