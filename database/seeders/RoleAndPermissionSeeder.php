<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definisikan semua permissions dasar
        $permissions = [
            'lihat_dashboard',
            'kelola_master_data',
            'kelola_operasional',
            'kelola_evaluasi',
            'kelola_pengguna',
        ];

        // Buat permissions jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Definisikan Roles
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleUserDaerah = Role::firstOrCreate(['name' => 'user_daerah', 'guard_name' => 'web']);

        // 3. Assign Permissions ke Role
        // Super Admin mendapatkan semua hak akses
        $roleSuperAdmin->syncPermissions($permissions);

        // Admin: dashboard & evaluasi
        $roleAdmin->syncPermissions([
            'lihat_dashboard',
            'kelola_evaluasi'
        ]);

        // User Daerah: operasional (tambahkan dashboard agar bisa masuk)
        $roleUserDaerah->syncPermissions([
            'lihat_dashboard',
            'kelola_operasional'
        ]);
    }
}
