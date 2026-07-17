<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(100) NULL DEFAULT 'User Daerah'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('Super Admin', 'Admin', 'Verifikator', 'User Daerah', 'Menteri') NULL DEFAULT 'User Daerah'");
    }
};
