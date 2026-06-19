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
        Schema::connection('mysql_knmp')->table('calon_lokasi', function (Blueprint $table) {
            $table->dropColumn(['knmp_id', 'nama_lokasi', 'batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('calon_lokasi', function (Blueprint $table) {
            $table->unsignedBigInteger('knmp_id')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->string('nama_lokasi')->nullable();
        });
    }
};
