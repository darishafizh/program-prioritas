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
            $table->string('jml_trip_per_bulan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql_knmp')->table('profil_knmp', function (Blueprint $table) {
            $table->integer('jml_trip_per_bulan')->nullable()->change();
        });
    }
};
