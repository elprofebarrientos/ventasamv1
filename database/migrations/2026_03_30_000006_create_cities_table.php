<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id('id_city');
            $table->unsignedBigInteger('id_department');
            $table->string('name', 100);
            $table->string('code', 10)->nullable();
            $table->timestamps();

            // Foreign key
            $table->foreign('id_department')->references('id_department')->on('departments')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
