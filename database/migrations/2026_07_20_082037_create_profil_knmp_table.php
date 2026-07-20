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
        Schema::create('profil_knmp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knmp_id')->nullable();
            $table->integer('jml_kk')->nullable();
            $table->integer('jml_nelayan')->nullable();
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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_knmp');
    }
};
