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
        Schema::create('categoria', function (Blueprint $table) {
            $table->id('id_categoria');
            $table->string('nombre', 100);
            $table->unsignedBigInteger('id_categoria_padre')->nullable();
            $table->integer('nivel')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('id_categoria_padre')
                  ->references('id_categoria')
                  ->on('categoria')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria');
    }
};