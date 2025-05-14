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
        Schema::table('notifications', function (Blueprint $table) {
            // Eliminar columnas innecesarias
            $table->dropColumn(['user_id', 'message', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Volver a agregar las columnas eliminadas si es necesario
            $table->unsignedBigInteger('user_id');
            $table->string('message');
            $table->string('status');
        });
    }
};
