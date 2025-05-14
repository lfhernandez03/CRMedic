<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSpecialitiesTableForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Eliminar las claves foráneas en la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['speciality_id']);
        });

        // Eliminar las claves foráneas en la tabla reports
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
        });

        // Eliminar la tabla 'specialities'
        Schema::dropIfExists('specialities');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restaurar la tabla 'specialities' en caso de rollback
        Schema::create('specialities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Restaurar las claves foráneas
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('speciality_id')->references('id')->on('specialities');
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('specialty_id')->references('id')->on('specialities');
        });
    }
}
