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
        Schema::table('categoria', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_padre']);
        });
        Schema::rename('categoria', 'categorias');
        Schema::table('categorias', function (Blueprint $table) {
            $table->foreign('id_categoria_padre')->references('id_categoria')->on('categorias')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_padre']);
        });
        Schema::rename('categorias', 'categoria');
        Schema::table('categoria', function (Blueprint $table) {
            $table->foreign('id_categoria_padre')->references('id_categoria')->on('categoria')->onDelete('set null');
        });
    }
};
