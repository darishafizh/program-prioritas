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
        Schema::connection('mysql_knmp')->create('calon_lokasi_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calon_lokasi_id');
            
            // Data Pengisi
            $table->string('nama_pengisi')->nullable();
            $table->string('jabatan_pengisi')->nullable();
            $table->string('no_hp_pengisi')->nullable();
            
            // Data Lahan
            $table->string('panjang_lahan')->nullable();
            $table->string('lebar_lahan')->nullable();
            $table->string('kemiringan_lahan')->nullable();
            
            // Kuesioner Kelayakan
            $table->string('status_kepemilikan')->nullable();
            $table->string('kesesuaian_rtrw')->nullable();
            $table->string('is_mangrove')->nullable();
            $table->string('is_konservasi')->nullable();
            $table->string('is_hutan_lindung')->nullable();
            $table->string('is_kawasan_budidaya')->nullable();
            $table->string('is_das')->nullable();
            $table->string('tekstur_tanah')->nullable();
            $table->string('salinitas_air')->nullable();
            $table->string('is_pasang_surut')->nullable();
            
            $table->timestamps();

            $table->foreign('calon_lokasi_id')->references('id')->on('calon_lokasi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->dropIfExists('calon_lokasi_detail');
    }
};
