<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Eliminar la columna que hacía referencia a 'specialities'
            $table->dropColumn('specialty_id');  // Cambia 'specialities_id' por el nombre correcto de la columna si es diferente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Si necesitas revertir la migración, puedes volver a agregar la columna
            $table->unsignedBigInteger('specialty_id')->nullable();  // O ajusta según el tipo de la columna
        });
    }
};
