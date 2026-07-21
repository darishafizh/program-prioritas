<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyWebhookToken
{
    public function handle(Request $request, Closure $next)
    {
        // Secret token yang bisa diatur di file .env
        $expectedToken = env('WEBHOOK_SECRET', 'token-rahasia-knmp-123');

        // Mendukung pengambilan token dari Header atau dari Query String
        $providedToken = $request->header('X-Webhook-Token') ?? $request->query('token');

        if (!hash_equals($expectedToken, (string) $providedToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Webhook Token'
            ], 401);
        }

        return $next($request);
    }
}
