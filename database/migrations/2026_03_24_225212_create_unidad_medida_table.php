<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidad_medida', function (Blueprint $table) {
            $table->id('id_unidad_medida');
            $table->string('nombre', 50)->unique();
            $table->string('abreviatura', 10)->unique();
            $table->string('tipo', 20); // unidad, peso, volumen, longitud, superficie
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidad_medida');
    }
};
