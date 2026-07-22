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
        // Temukan pengguna yang ada indikasi sebagai admin/super_admin
        $users = User::where('name', 'like', '%Super Admin%')
                     ->orWhere('name', 'like', '%Samudra%')
                     ->get();

        if ($users->isEmpty()) {
            $this->error('Tidak ada user Super Admin yang ditemukan.');
            return;
        }

        foreach ($users as $user) {
            // Hapus semua direct permissions (karena harusnya ikut role)
            $user->syncPermissions([]);
            
            // Set ulang role nya menjadi role yang benar 'super_admin'
            $user->syncRoles(['super_admin']);
            
            $this->info("Berhasil mengembalikan akses penuh untuk user: " . $user->name);
        }
    }
}
