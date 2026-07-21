<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * Usage: middleware('role:super_admin,admin_roren')
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            $role = strtolower(trim($role));
            if ($role === 'super_admin' && $user->isSuperAdmin()) {
                return $next($request);
            }
            if (($role === 'admin_roren' || $role === 'admin') && $user->isAdminRoren()) {
                return $next($request);
            }
            if ($role === 'verifikator' && $user->isVerifikator()) {
                return $next($request);
            }
            if ($role === 'user_daerah' && $user->isUserDaerah()) {
                return $next($request);
            }

            // Fallback for custom roles passed to middleware
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
