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
        Schema::create('inventario_ubicacion', function (Blueprint $table) {
            $table->id('id_inventario');

            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_ubicacion');

            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('stock_reservado', 12, 2)->default(0);

            $table->timestamps();

            $table->unique(['id_variante', 'id_ubicacion']);

            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->cascadeOnDelete();
            $table->foreign('id_ubicacion')->references('id_ubicacion')->on('ubicaciones')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_ubicacion');
    }
};
