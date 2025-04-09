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
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');

            $table->string('title')->after('id');
            $table->text('purpose')->nullable()->after('title');
            $table->text('instructions')->nullable()->after('purpose');
        });
    }

    public function down(): void
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropColumn(['title', 'purpose', 'instructions']);
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
        });
    }
};
