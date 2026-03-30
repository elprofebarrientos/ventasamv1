<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('id_empresa');
            $table->string('nit', 20)->unique();
            $table->string('digito_verificacion', 1);
            $table->string('razon_social', 255);
            $table->string('nombre_comercial', 255)->nullable();
            $table->string('direccion_fisica', 500);
            $table->string('ciudad_municipio', 100);
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('email_corporativo', 255)->nullable();
            $table->string('email_facturacion', 255)->nullable();
            $table->string('sitio_web', 255)->nullable();
            $table->string('representante_legal', 255)->nullable();
            $table->string('cedula_representante', 20)->nullable();
            $table->string('logo_empresa', 500)->nullable();
            $table->enum('regimen_fiscal', ['responsable_iva', 'no_responsable', 'simple'])->default('responsable_iva');
            $table->string('resolucion_dian', 100)->nullable();
            $table->integer('rango_inicio')->nullable();
            $table->integer('rango_fin')->nullable();
            $table->string('clave_tecnica', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresa');
    }
};
