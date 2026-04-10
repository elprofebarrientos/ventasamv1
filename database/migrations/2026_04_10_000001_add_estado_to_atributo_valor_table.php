<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('atributo_valor', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->after('imagen_url');
        });
    }

    public function down(): void
    {
        Schema::table('atributo_valor', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};
