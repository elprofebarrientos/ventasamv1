<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variante_valor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_variante');
            $table->unsignedBigInteger('id_valor');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_variante')->references('id_variante')->on('producto_variante')->onDelete('cascade');
            $table->foreign('id_valor')->references('id_valor')->on('atributo_valor')->onDelete('cascade');

            // Unique constraint to prevent duplicate values for the same variant
            $table->unique(['id_variante', 'id_valor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variante_valor');
    }
};
