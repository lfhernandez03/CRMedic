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
            // Eliminar el campo speciality_id si existe
            if (Schema::hasColumn('users', 'speciality_id')) {
                $table->dropColumn('speciality_id');
            }

            // Agregar el campo specialities si no existe
            if (!Schema::hasColumn('users', 'specialities')) {
                $table->text('specialities')->nullable(); // Puedes cambiar a json si lo prefieres
            }
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
            // Agregar de nuevo speciality_id si no existe
            if (!Schema::hasColumn('users', 'speciality_id')) {
                $table->unsignedBigInteger('speciality_id')->nullable();
            }

            // Eliminar specialities si existe
            if (Schema::hasColumn('users', 'specialities')) {
                $table->dropColumn('specialities');
            }
        });
    }
}
