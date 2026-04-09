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
        Schema::create('inventario_disponible', function (Blueprint $table) {
            $table->unsignedBigInteger('id_variante')->primary();

            $table->decimal('stock_actual', 12, 2)->default(0);
            $table->decimal('stock_reservado', 12, 2)->default(0);
            $table->decimal('stock_disponible', 12, 2)->default(0);

            $table->timestamp('ultima_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventario_disponible');
    }
};
