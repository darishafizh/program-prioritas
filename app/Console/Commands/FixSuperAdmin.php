<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class FixSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Super Admin roles and permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Panggil Seeder untuk memastikan 3 role utama terbuat dengan rapi
        $this->call(\Database\Seeders\RoleAndPermissionSeeder::class);

        // 2. Temukan pengguna super admin (dari nama atau role lama)
        $users = User::where('name', 'like', '%Super Admin%')
                     ->orWhere('name', 'like', '%Samudra%')
                     ->get();

        foreach ($users as $user) {
            $user->syncPermissions([]); // Hapus direct permission
            $user->syncRoles(['super_admin']); // Pasang role super_admin baru
            $this->info("Berhasil mereset akses Super Admin untuk: " . $user->name);
        }

        // 3. Bersihkan role lain yang tidak diinginkan (selain 3 role utama)
        $validRoles = ['super_admin', 'admin', 'user_daerah'];
        \Spatie\Permission\Models\Role::whereNotIn('name', $validRoles)->delete();
        
        $this->info("Pembersihan selesai! Sekarang sistem hanya memiliki 3 role utama.");
    }
}
