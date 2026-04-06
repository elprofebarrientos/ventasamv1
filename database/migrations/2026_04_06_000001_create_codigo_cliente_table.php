<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codigo_cliente', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->enum('tipo_descuento', ['porcentaje', 'valor'])->notNull();
            $table->decimal('valor', 10, 2)->notNull();
            $table->enum('tipo_uso', ['unico', 'multiple'])->notNull();
            $table->integer('max_usos')->nullable();
            $table->integer('usos_actuales')->default(0);
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->decimal('monto_minimo', 10, 2)->nullable();
            $table->enum('aplica_a', ['producto', 'categoria', 'todos'])->default('todos');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('creado_en')->useCurrent();
            $table->timestamp('actualizado_en')->nullable();

            $table->foreign('producto_id')->references('id_producto')->on('productos')->onDelete('set null');
            $table->foreign('categoria_id')->references('id_categoria')->on('categorias')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codigo_cliente');
    }
};
