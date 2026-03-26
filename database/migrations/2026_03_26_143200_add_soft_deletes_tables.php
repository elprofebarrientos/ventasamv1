<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->softDeletes()->nullable()->after('nivel');
        });

        Schema::table('marca', function (Blueprint $table) {
            $table->softDeletes()->nullable()->after('activo');
        });

        Schema::table('unidad_medida', function (Blueprint $table) {
            $table->softDeletes()->nullable()->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('marca', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('unidad_medida', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};