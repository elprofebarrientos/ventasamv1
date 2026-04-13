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
        Schema::table('recepciones_compra', function (Blueprint $table) {
            $table->date('fecha_recepcion')->nullable()->after('observacion');
            $table->string('numero_recepcion')->nullable()->after('fecha_recepcion');
            $table->text('observacion')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recepciones_compra', function (Blueprint $table) {
            //
        });
    }
};
