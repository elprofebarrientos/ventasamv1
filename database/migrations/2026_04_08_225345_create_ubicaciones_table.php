<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id('id_ubicacion');
            $table->unsignedBigInteger('id_bodega');
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->string('nombre', 255);
            $table->enum('tipo', ['zona', 'pasillo', 'estante', 'nivel', 'posicion'])->default('zona');
            $table->string('codigo', 50)->nullable();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_bodega')->references('id_bodega')->on('bodega')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ubicaciones');
    }
};