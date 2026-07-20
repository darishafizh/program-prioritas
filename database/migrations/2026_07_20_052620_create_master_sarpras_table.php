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
        Schema::create('master_sarpras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        $records = [
            ['nama' => 'SPBN', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Docking', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Bengkel', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Waserda', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pabrik Es', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Cold Storage', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'KDRN Dingin', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sentra Kuliner', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kios Pemasaran', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kapal', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Mesin', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Alat Tangkap', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Cool Box', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Roda 3', 'created_at' => now(), 'updated_at' => now()],
        ];

        \Illuminate\Support\Facades\DB::connection('mysql_knmp')->table('master_sarpras')->insert($records);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_sarpras');
    }
};
