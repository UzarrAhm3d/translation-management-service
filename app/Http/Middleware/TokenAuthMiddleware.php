<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['message' => 'Token required'], 401);
        }

        $validTokens = explode(',', env('API_TOKENS', ''));
        
        if (!in_array($token, $validTokens)) {
            return response()->json(['message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
