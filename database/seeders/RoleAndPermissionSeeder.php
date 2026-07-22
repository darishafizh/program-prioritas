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
        // Pastikan nama role sesuai dengan yang ada di sistem (Super Admin, Admin Roren, Verifikator, dll)
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $roleAdminRoren = Role::firstOrCreate(['name' => 'Admin Roren', 'guard_name' => 'web']);
        $roleVerifikator = Role::firstOrCreate(['name' => 'Verifikator', 'guard_name' => 'web']);
        $roleUserDaerah = Role::firstOrCreate(['name' => 'User Daerah', 'guard_name' => 'web']);
        $roleMenteri = Role::firstOrCreate(['name' => 'Menteri', 'guard_name' => 'web']);

        // 3. Assign Permissions ke Role
        // Super Admin mendapatkan semua hak akses
        $roleSuperAdmin->syncPermissions($permissions);

        // Contoh pembagian akses untuk role lain (Bisa disesuaikan nanti di menu Manajemen Akses)
        $roleAdminRoren->syncPermissions([
            'lihat_dashboard',
            'kelola_operasional',
            'kelola_evaluasi'
        ]);

        $roleVerifikator->syncPermissions([
            'lihat_dashboard',
            'kelola_evaluasi'
        ]);

        $roleUserDaerah->syncPermissions([
            'lihat_dashboard',
            'kelola_operasional'
        ]);
        
        $roleMenteri->syncPermissions([
            'lihat_dashboard'
        ]);
    }
}
