<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('nombre', 255);
            $table->string('slug', 255)->unique();
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('id_categoria')->nullable();
            $table->unsignedBigInteger('id_marca')->nullable();
            $table->unsignedBigInteger('id_unidad_medida')->nullable();
            $table->enum('estado', ['disponible', 'no_disponible', 'descontinuado'])->default('disponible');
            $table->boolean('permite_venta')->default(true);
            $table->boolean('permite_alquiler')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->onDelete('set null');
            $table->foreign('id_marca')->references('id_marca')->on('marca')->onDelete('set null');
            $table->foreign('id_unidad_medida')->references('id_unidad_medida')->on('unidad_medida')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};