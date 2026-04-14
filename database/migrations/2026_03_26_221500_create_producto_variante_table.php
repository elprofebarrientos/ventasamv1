<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto_variante', function (Blueprint $table) {
            $table->id('id_variante');
            $table->unsignedBigInteger('id_producto');
            $table->string('sku', 100)->nullable();
            $table->string('codigo_barras', 100)->nullable();
            $table->boolean('tiene_lote')->default(false);
            $table->boolean('tiene_fecha_vencimiento')->default(false);
            $table->json('imagenes_json')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto_variante');
    }
};
