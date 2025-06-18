<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Eliminar la columna 'notified'
            $table->dropColumn('notificated');  // Cambia 'notified' si el nombre es diferente
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Si necesitas revertir la migración, puedes volver a agregar la columna 'notified'
            $table->boolean('notificated')->default(false);  // Ajusta el tipo y valor por defecto si es necesario
        });
    }
};
