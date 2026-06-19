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
        Schema::connection('mysql_knmp')->table('calon_lokasi_detail', function (Blueprint $table) {
            $table->string('luas_lahan')->nullable()->after('lebar_lahan');
            $table->string('jarak_pantai')->nullable()->after('is_pasang_surut');
            $table->string('jarak_sungai')->nullable()->after('is_das');
            $table->string('lebar_sungai')->nullable()->after('jarak_sungai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('calon_lokasi_detail', function (Blueprint $table) {
            $table->dropColumn(['luas_lahan', 'jarak_pantai', 'jarak_sungai', 'lebar_sungai']);
        });
    }
};
