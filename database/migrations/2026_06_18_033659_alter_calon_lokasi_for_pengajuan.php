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
            $table->unsignedBigInteger('knmp_id')->nullable()->change();
            
            $table->string('nama_lokasi')->nullable()->after('knmp_id');
            $table->string('provinsi')->nullable()->after('nama_lokasi');
            $table->string('kabupaten')->nullable()->after('provinsi');
            $table->string('kecamatan')->nullable()->after('kabupaten');
            $table->string('desa')->nullable()->after('kecamatan');
            $table->string('latitude')->nullable()->after('desa');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('calon_lokasi', function (Blueprint $table) {
            $table->unsignedBigInteger('knmp_id')->nullable(false)->change();
            $table->dropColumn(['nama_lokasi', 'provinsi', 'kabupaten', 'kecamatan', 'desa', 'latitude', 'longitude']);
        });
    }
};
