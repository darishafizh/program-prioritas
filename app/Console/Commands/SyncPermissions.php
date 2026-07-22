<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SyncPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all required permissions for the application and assign them to Super Admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'lihat_dashboard',
            'kelola_master_data',
            'kelola_operasional',
            'kelola_evaluasi',
            'kelola_pengguna',
        ];

        foreach ($permissions as $perm) {
            Permission::findOrCreate($perm, 'web');
        }

        $this->info('Semua permission dasar berhasil dibuat/disinkronisasi!');

        // Optional: Assign all permissions to super admin explicitly just in case
        $role = Role::findOrCreate('Super Admin', 'web');
        $role->syncPermissions($permissions);

        $this->info('Permission berhasil dihubungkan ke role Super Admin!');
    }
}
