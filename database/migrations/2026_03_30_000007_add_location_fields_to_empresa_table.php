<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_country')->nullable()->after('ciudad_municipio');
            $table->unsignedBigInteger('id_department')->nullable()->after('id_country');
            $table->unsignedBigInteger('id_city')->nullable()->after('id_department');

            // Foreign keys
            $table->foreign('id_country')->references('id_country')->on('countries')->onDelete('set null');
            $table->foreign('id_department')->references('id_department')->on('departments')->onDelete('set null');
            $table->foreign('id_city')->references('id_city')->on('cities')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            $table->dropForeign(['id_country']);
            $table->dropForeign(['id_department']);
            $table->dropForeign(['id_city']);
            $table->dropColumn(['id_country', 'id_department', 'id_city']);
        });
    }
};
