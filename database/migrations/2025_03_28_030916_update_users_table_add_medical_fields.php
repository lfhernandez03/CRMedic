<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('horario')->nullable()->after('rol');
            $table->integer('pacientes_atendidos')->default(0)->after('horario');
            $table->integer('pacientes_pendientes')->default(0)->after('pacientes_atendidos');
            $table->unsignedBigInteger('speciality_id')->nullable()->after('pacientes_pendientes');

            // Agregar clave foránea para specialities
            $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar la clave foránea antes de eliminar la columna
            $table->dropForeign(['speciality_id']);
            $table->dropColumn(['horario', 'pacientes_atendidos', 'pacientes_pendientes', 'speciality_id']);
        });
    }
};
