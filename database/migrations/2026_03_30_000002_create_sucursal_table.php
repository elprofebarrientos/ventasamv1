<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sucursal', function (Blueprint $table) {
            $table->id('id_sucursal');
            $table->unsignedBigInteger('id_empresa');
            $table->string('nombre', 255);
            $table->string('direccion', 500);
            $table->string('telefono', 20)->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('id_empresa')->references('id_empresa')->on('empresa')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sucursal');
    }
};
