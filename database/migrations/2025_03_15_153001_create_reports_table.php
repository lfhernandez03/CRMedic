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
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('doctor_id')->nullable();
            $table->unsignedInteger('specialty_id')->nullable();
            $table->string('description');
            $table->string('data');
            $table->enum('report_type', ['patients_attended', 'month_queries'])->default('patients_attended');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
            $table->foreign('specialty_id')->references('id')->on('specialities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
