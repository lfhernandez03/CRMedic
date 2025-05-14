<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar la restricción CHECK existente con una consulta SQL directa
        DB::statement('ALTER TABLE reports DROP CONSTRAINT IF EXISTS reports_report_type_check');

        // Agregar la nueva restricción CHECK con SQL directo
        DB::statement('ALTER TABLE reports ADD CONSTRAINT reports_report_type_check CHECK (report_type IN (\'patients_attended\', \'diagnosis\', \'lab_results\', \'consultation_summary\'))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la restricción CHECK si se necesita revertir la migración
        DB::statement('ALTER TABLE reports DROP CONSTRAINT IF EXISTS reports_report_type_check');

        // Restaurar la restricción original (si es necesario)
        // Si quieres revertir la migración, asegúrate de colocar la restricción original
        DB::statement('ALTER TABLE reports ADD CONSTRAINT reports_report_type_check CHECK (report_type IN (\'valores_antiguos\'))');
    }
};
