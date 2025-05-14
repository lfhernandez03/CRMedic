<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableForSpecialities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar el campo speciality_id
            $table->dropColumn('speciality_id');

            // Agregar el campo specialities como JSON
            $table->json('specialities')->nullable();  // Usamos nullable() para permitir que pueda estar vacío
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Volver a agregar speciality_id
            $table->unsignedBigInteger('speciality_id')->nullable();

            // Eliminar el campo specialities
            $table->dropColumn('specialities');
        });
    }
}
