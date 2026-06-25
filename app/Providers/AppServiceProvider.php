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
        if (isset($_SERVER['HTTP_HOST']) && !str_contains($_SERVER['HTTP_HOST'], 'localhost') && !str_contains($_SERVER['HTTP_HOST'], '127.0.0.1')) {
            $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || 
                       (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
                       str_starts_with(config('app.url', ''), 'https://');
            $scheme = $isHttps ? 'https' : 'http';
            
            $basePath = '';
            if (isset($_SERVER['SCRIPT_NAME'])) {
                $basePath = str_replace(['/public/index.php', '/index.php'], '', $_SERVER['SCRIPT_NAME']);
            }
            
            URL::forceRootUrl($scheme . '://' . $_SERVER['HTTP_HOST'] . $basePath);
            if ($isHttps) {
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
