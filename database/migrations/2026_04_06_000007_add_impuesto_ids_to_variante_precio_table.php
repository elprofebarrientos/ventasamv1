<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('variante_precio', function (Blueprint $table) {
            $table->json('impuesto_ids')->nullable()->after('precio_final');
        });
    }

    public function down(): void
    {
        Schema::table('variante_precio', function (Blueprint $table) {
            $table->dropColumn('impuesto_ids');
        });
    }
};