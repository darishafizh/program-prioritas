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
        $connection = 'mysql_knmp';

        // 1. Table Utama Calon Lokasi
        Schema::connection($connection)->create('calon_lokasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knmp_id');
            $table->unsignedBigInteger('user_id')->nullable(); // Pengusul
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->enum('status_tahapan', [
                'pengajuan', 
                'verif_admin', 
                'ba_aktivasi', 
                'verif_teknis', 
                'ba_calon', 
                'penetapan', 
                'ditolak'
            ])->default('pengajuan');
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign Keys (Assumption knmp table exists in the same connection)
            // $table->foreign('knmp_id')->references('id')->on('knmp')->onDelete('cascade');
        });

        // 2. Table Tahap Pengajuan
        Schema::connection($connection)->create('calon_lokasi_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('dokumen_proposal');
            $table->date('tanggal_pengajuan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });

        // 3. Table Tahap Verifikasi Administrasi
        Schema::connection($connection)->create('calon_lokasi_verif_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('dokumen_hasil_verif')->nullable();
            $table->integer('skor_nilai')->default(0);
            $table->string('status_verif')->default('Proses Review'); // Lolos / Revisi / Ditolak
            $table->date('tanggal_verif')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });

        // 4. Table Tahap BA Aktivasi
        Schema::connection($connection)->create('calon_lokasi_ba_aktivasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('nomor_ba')->nullable();
            $table->date('tanggal_ba')->nullable();
            $table->string('dokumen_ba')->nullable();
            $table->string('status_ba')->default('Menunggu Draft'); // Menunggu Draft / Proses TTD / Selesai
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });

        // 5. Table Tahap Verifikasi Teknis Lapangan
        Schema::connection($connection)->create('calon_lokasi_verif_teknis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('dokumen_laporan')->nullable();
            $table->integer('skor_teknis')->default(0);
            $table->string('status_verif')->default('Proses Survey');
            $table->date('tanggal_verif')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });

        // 6. Table Tahap BA Calon
        Schema::connection($connection)->create('calon_lokasi_ba_calon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('nomor_ba')->nullable();
            $table->date('tanggal_ba')->nullable();
            $table->string('dokumen_ba')->nullable();
            $table->string('status_ba')->default('Menunggu Draft');
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });

        // 7. Table Tahap Penetapan (SK)
        Schema::connection($connection)->create('calon_lokasi_penetapan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('dokumen_sk')->nullable();
            $table->string('status_sk')->default('Menunggu Penerbitan'); // Menunggu / Ditetapkan
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        $connection = 'mysql_knmp';
        
        Schema::connection($connection)->dropIfExists('calon_lokasi_penetapan');
        Schema::connection($connection)->dropIfExists('calon_lokasi_ba_calon');
        Schema::connection($connection)->dropIfExists('calon_lokasi_verif_teknis');
        Schema::connection($connection)->dropIfExists('calon_lokasi_ba_aktivasi');
        Schema::connection($connection)->dropIfExists('calon_lokasi_verif_admin');
        Schema::connection($connection)->dropIfExists('calon_lokasi_pengajuan');
        Schema::connection($connection)->dropIfExists('calon_lokasi');
    }
};
