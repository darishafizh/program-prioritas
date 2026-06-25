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
        if (!$this->app->environment('local')) {
            URL::forceRootUrl(config('app.url'));
            if (str_contains(config('app.url'), 'https://')) {
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
