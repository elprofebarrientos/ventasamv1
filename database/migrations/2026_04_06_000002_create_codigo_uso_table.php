<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigo_uso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigo_cliente_id')->notNull();
            $table->unsignedBigInteger('venta_id')->notNull();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->timestamp('fecha_uso')->useCurrent();

            $table->foreign('codigo_cliente_id')
                ->references('id')
                ->on('codigo_cliente')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigo_uso');
    }
};