<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('codigo_cliente', function (Blueprint $table) {
            $table->json('producto_ids')->nullable()->after('producto_id');
            $table->json('categoria_ids')->nullable()->after('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::table('codigo_cliente', function (Blueprint $table) {
            $table->dropColumn(['producto_ids', 'categoria_ids']);
        });
    }
};