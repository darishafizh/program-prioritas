<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $forceRootUrl = env('FORCE_ROOT_URL');
        if ($forceRootUrl) {
            URL::forceRootUrl($forceRootUrl);
            if (str_starts_with($forceRootUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        // Implicitly grant "Super Admin" role all permissions
        // This makes sure Super Admin bypasses all 'permission:' middleware checks
        Gate::before(function ($user, $ability) {
            return $user->isSuperAdmin() ? true : null;
        });

        // Super Admin: manage users
        Gate::define('manage-users', function (User $user) {
            return $user->isSuperAdmin();
        });

        // Super Admin only: CRUD on master data (batch, vendor)
        Gate::define('manage-master', function (User $user) {
            return $user->isSuperAdmin();
        });

        // Super Admin + Verifikator: verify calon lokasi (verif admin, verif teknis)
        Gate::define('verify-calon-lokasi', function (User $user) {
            return $user->isSuperAdmin() || $user->isVerifikator();
        });

        // Super Admin only: manage calon lokasi (upload BA, SK, update status)
        Gate::define('manage-calon-lokasi', function (User $user) {
            return $user->isSuperAdmin();
        });

        // User Daerah only: submit pengajuan
        Gate::define('submit-pengajuan', function (User $user) {
            return $user->isUserDaerah();
        });

        // Super Admin + Admin Roren: import progres harian
        Gate::define('import-progres', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdminRoren();
        });

        // Super Admin only: manage operasional (import usulan, pindah tahap, upload foto)
        Gate::define('manage-operasional', function (User $user) {
            return $user->isSuperAdmin();
        });

        // Super Admin + Admin Roren: generate PDF
        Gate::define('generate-pdf', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdminRoren();
        });

        // Super Admin + Admin Roren + Verifikator: access dashboard/operasional/evaluasi
        Gate::define('access-dashboard', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdminRoren() || $user->isVerifikator();
        });

        Gate::define('access-operasional', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdminRoren() || $user->isVerifikator();
        });

        Gate::define('access-evaluasi', function (User $user) {
            return $user->isSuperAdmin() || $user->isAdminRoren() || $user->isVerifikator();
        });
    }
}
