<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Paso 1: Rellenar los null con un texto vacío
        DB::table('patients')
            ->whereNull('medical_history')
            ->update(['medical_history' => '']);

        // Paso 2: Cambiar el campo a NOT NULL
        Schema::table('patients', function (Blueprint $table) {
            $table->text('medical_history')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('medical_history')->nullable()->change();
        });
    }
};
