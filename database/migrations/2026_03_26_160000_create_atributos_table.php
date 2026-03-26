<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atributos', function (Blueprint $table) {
            $table->id('id_atributo');
            $table->string('nombre', 100)->unique();
            $table->enum('tipo_visual', ['TEXTO', 'COLOR', 'IMAGEN'])->default('TEXTO');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atributos');
    }
};