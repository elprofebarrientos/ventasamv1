<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bodega', function (Blueprint $table) {
            $table->id('id_bodega');
            $table->unsignedBigInteger('id_sucursal');
            $table->string('nombre', 255);
            $table->string('codigo', 50)->unique();
            $table->string('responsable', 255)->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('id_sucursal')->references('id_sucursal')->on('sucursal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bodega');
    }
};
