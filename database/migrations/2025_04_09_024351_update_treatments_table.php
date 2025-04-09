<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            if (!Schema::hasColumn('treatments', 'title')) {
                $table->string('title')->after('id');
            }

            if (!Schema::hasColumn('treatments', 'purpose')) {
                $table->text('purpose')->nullable()->after('title');
            }

            if (!Schema::hasColumn('treatments', 'instructions')) {
                $table->text('instructions')->after('purpose');
            }

            if (Schema::hasColumn('treatments', 'diagnosis')) {
                $table->dropColumn('diagnosis');
            }

            if (Schema::hasColumn('treatments', 'treatment')) {
                $table->dropColumn('treatment');
            }
        });
    }
};
