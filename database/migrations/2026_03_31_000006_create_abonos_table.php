<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abonos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_compra')->constrained('compras', 'id_compra')->onDelete('cascade');
            $table->decimal('monto', 15, 2)->default(0);
            $table->decimal('monto_restante', 15, 2)->default(0);
            $table->string('metodo_pago')->default('efectivo');
            $table->text('nota')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonos');
    }
};
