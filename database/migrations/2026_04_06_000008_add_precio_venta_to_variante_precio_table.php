<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variante_precio', function (Blueprint $table) {
            $table->decimal('precio_venta', 12, 2)->nullable()->after('precio_final');
        });
    }

    public function down(): void
    {
        Schema::table('variante_precio', function (Blueprint $table) {
            $table->dropColumn('precio_venta');
        });
    }
};