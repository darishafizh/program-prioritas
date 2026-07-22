<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'mysql_knmp';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql_knmp')->table('profil_knmp', function (Blueprint $table) {
            // Drop unused columns
            $table->dropColumn([
                'komoditas',
                'penjualan_ikan',
                'jml_hari_melaut',
                'pend_avg_saat_ini',
                'pend_avg_intervensi',
                'vol_produksi_daerah',
                'nilai_produksi_daerah',
                'vol_produksi_intervensi',
                'nilai_produksi_intervensi',
                'serapan_tenaga_kerja',
            ]);

            // Add new columns
            $table->integer('jml_kapal')->nullable()->after('jml_nelayan');
            $table->decimal('prod_total_desa', 15, 2)->nullable()->after('jml_kapal');
            $table->string('ukuran_perahu_dominan')->nullable()->after('prod_total_desa');
            $table->string('alat_tangkap_dominan')->nullable()->after('ukuran_perahu_dominan');
            $table->string('komoditas_utama')->nullable()->after('alat_tangkap_dominan');
            $table->decimal('pend_nelayan', 15, 2)->nullable()->after('komoditas_utama');
            $table->decimal('prod_per_trip_per_kapal', 15, 2)->nullable()->after('pend_nelayan');
            $table->integer('jml_trip_per_bulan')->nullable()->after('prod_per_trip_per_kapal');
            $table->decimal('prod_kapal', 15, 2)->nullable()->after('jml_trip_per_bulan');
            $table->decimal('prod_total_kapal', 15, 2)->nullable()->after('prod_kapal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('profil_knmp', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'jml_kapal',
                'prod_total_desa',
                'ukuran_perahu_dominan',
                'alat_tangkap_dominan',
                'komoditas_utama',
                'pend_nelayan',
                'prod_per_trip_per_kapal',
                'jml_trip_per_bulan',
                'prod_kapal',
                'prod_total_kapal',
            ]);

            // Restore unused columns
            $table->string('komoditas')->nullable()->comment('tongkol, tuna');
            $table->string('penjualan_ikan')->nullable();
            $table->integer('jml_hari_melaut')->nullable();
            $table->decimal('pend_avg_saat_ini', 15, 2)->nullable()->comment('juta per orang per bulan');
            $table->decimal('pend_avg_intervensi', 15, 2)->nullable()->comment('juta per orang per bulan');
            $table->decimal('vol_produksi_daerah', 15, 2)->nullable()->comment('ton/tahun');
            $table->decimal('nilai_produksi_daerah', 15, 2)->nullable()->comment('nilai/tahun');
            $table->decimal('vol_produksi_intervensi', 15, 2)->nullable()->comment('ton/tahun');
            $table->decimal('nilai_produksi_intervensi', 15, 2)->nullable()->comment('nilai/tahun');
            $table->integer('serapan_tenaga_kerja')->nullable();
        });
    }
};
