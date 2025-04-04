<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Eliminar las claves foráneas antes de eliminar la tabla
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
        });

        // Ahora eliminar la tabla
        Schema::dropIfExists('doctors');
    }

    public function down()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });
    }
};
