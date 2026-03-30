<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('id_department');
            $table->unsignedBigInteger('id_country');
            $table->string('name', 100);
            $table->string('code', 10)->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_country')->references('id_country')->on('countries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
