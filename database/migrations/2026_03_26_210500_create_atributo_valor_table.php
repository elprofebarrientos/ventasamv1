<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atributo_valor', function (Blueprint $table) {
            $table->id('id_valor');
            $table->unsignedBigInteger('id_atributo');
            $table->string('valor', 100);
            $table->string('codigo_hex', 7)->nullable();
            $table->string('imagen_url', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('id_atributo')->references('id_atributo')->on('atributos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atributo_valor');
    }
};