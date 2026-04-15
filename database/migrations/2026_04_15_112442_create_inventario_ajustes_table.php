<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventario_ajustes', function (Blueprint $table) {
            $table->id('id_ajuste');

            $table->unsignedBigInteger('id_bodega');
            $table->unsignedBigInteger('id_ubicacion');
            $table->unsignedBigInteger('id_variante');
            $table->decimal('cantidad', 12, 2);
            $table->string('motivo')->default('error_recepcion');
            $table->unsignedBigInteger('referencia_id')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->date('fecha');

            $table->timestamps();

            $table->foreign('id_bodega')->references('id_bodega')->on('bodega')->cascadeOnDelete();
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->cascadeOnDelete();
            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_ajustes');
    }
};