<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->id('id_compra');
            $table->unsignedBigInteger('id_proveedor');
            $table->string('numero_factura', 50)->nullable();
            $table->string('cufe', 255)->nullable()->comment('Código Único de Factura Electrónica DIAN');
            $table->date('fecha_emision');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('subtotal_bruto', 18, 2)->default(0);
            $table->decimal('total_iva', 18, 2)->default(0);
            $table->decimal('valor_retefuente', 18, 2)->default(0);
            $table->decimal('valor_reteica', 18, 2)->default(0);
            $table->decimal('total_neto_pagar', 18, 2)->default(0);
            $table->enum('resultado_recepcion', ['Por recibir', 'Completa', 'Incompleta', 'Con daños', 'Mixta'])->nullable()->default('Por recibir');
            $table->timestamps();

            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
