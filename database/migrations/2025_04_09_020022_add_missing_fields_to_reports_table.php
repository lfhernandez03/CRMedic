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
            if (!Schema::hasColumn('reports', 'time')) {
                $table->time('time')->nullable();
            }

            if (!Schema::hasColumn('reports', 'status')) {
                $table->string('status')->default('open');
            }

            if (!Schema::hasColumn('reports', 'reason')) {
                $table->text('reason')->nullable();
            }

            if (!Schema::hasColumn('reports', 'notificated')) {
                $table->boolean('notificated')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            if (Schema::hasColumn('reports', 'time')) {
                $table->dropColumn('time');
            }

            if (Schema::hasColumn('reports', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('reports', 'reason')) {
                $table->dropColumn('reason');
            }

            if (Schema::hasColumn('reports', 'notificated')) {
                $table->dropColumn('notificated');
            }
        });
    }
};
