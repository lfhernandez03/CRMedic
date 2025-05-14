<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DropDiseasePatientTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('diseases');
    }

    public function down()
    {
        // Si necesitas revertir, puedes agregar la migración de nuevo.
    }
}
