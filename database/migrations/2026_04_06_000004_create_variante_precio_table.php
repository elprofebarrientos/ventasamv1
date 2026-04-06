<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variante_precio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variante_id')->unique();
            $table->decimal('ultimo_costo', 12, 2)->nullable();
            $table->decimal('margen_porcentaje', 6, 2)->nullable();
            $table->decimal('precio_base', 12, 2)->nullable();
            $table->decimal('precio_final', 12, 2)->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();

            $table->foreign('variante_id')
                ->references('id_variante')
                ->on('producto_variante')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variante_precio');
    }
};