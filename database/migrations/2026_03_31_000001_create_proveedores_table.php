<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id('id_proveedor');
            $table->enum('tipo_documento', ['CC', 'NIT', 'CE', 'PAP']);
            $table->string('numero_documento', 20);
            $table->tinyInteger('digito_verificacion')->nullable();
            $table->string('razon_social', 255);
            $table->string('nombre_comercial', 255)->nullable();
            $table->string('responsabilidad_fiscal', 50)->nullable();
            $table->boolean('es_iva_responsable')->default(false);
            $table->boolean('es_autoretenedor')->default(false);
            $table->string('correo_facturacion', 150)->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->unsignedBigInteger('id_pais')->nullable();
            $table->unsignedBigInteger('id_departamento')->nullable();
            $table->unsignedBigInteger('id_municipio')->nullable();
            $table->integer('plazo_pago_dias')->default(0);
            $table->decimal('cupo_credito', 18, 2)->default(0);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('id_pais')->references('id_country')->on('countries')->nullOnDelete();
            $table->foreign('id_departamento')->references('id_department')->on('departments')->nullOnDelete();
            $table->foreign('id_municipio')->references('id_city')->on('cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
