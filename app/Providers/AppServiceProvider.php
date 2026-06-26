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
        if (!empty($forceRootUrl)) {
            URL::forceRootUrl($forceRootUrl);
            if (strpos($forceRootUrl, 'https://') === 0) {
                URL::forceScheme('https');
            }
        }

        Gate::define('manage-users', function (User $user) {
            return $user->isSuperAdmin();
        });

        Gate::define('manage-data', function (User $user) {
            return $user->isSuperAdmin() || $user->isVerifikator();
        });
        
        Gate::define('view-data', function (User $user) {
            return true; // All authenticated users can view, but filtering is applied in controllers
        });
    }
}
