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
        Schema::connection('mysql_knmp')->table('penyedia_jasa_konstruksi', function (Blueprint $table) {
            $table->string('npwp')->nullable()->after('nama');
            $table->string('direktur_utama')->nullable()->after('npwp');
            $table->string('kontak')->nullable()->after('direktur_utama');
            $table->string('kualifikasi_sbu')->nullable()->after('kontak');
            $table->string('status')->default('Aktif')->after('kualifikasi_sbu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('penyedia_jasa_konstruksi', function (Blueprint $table) {
            $table->dropColumn(['npwp', 'direktur_utama', 'kontak', 'kualifikasi_sbu', 'status']);
        });
    }
};
