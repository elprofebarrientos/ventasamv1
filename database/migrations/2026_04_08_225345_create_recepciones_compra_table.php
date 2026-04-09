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
        Schema::create('recepciones_compra', function (Blueprint $table) {
            $table->id('id_recepcion');
            $table->unsignedBigInteger('id_compra');

            $table->dateTime('fecha');
            $table->text('observacion')->nullable();
            $table->string('estado')->default('BORRADOR');

            $table->timestamps();

            $table->foreign('id_compra')->references('id_compra')->on('compras')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepciones_compra');
    }
};
