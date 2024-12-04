<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OasisMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the Authorization header exists
        $authorizationHeader = $request->header('Authorization');

        if (!$authorizationHeader) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the Bearer token is present
        $token = str_replace('Bearer ', '', $authorizationHeader);

        // Check the validity of the token (You can modify this check based on your token validation logic)
        if ($token !== 'QuM8xVcsWZU82nGoICyWtPvFBnO239FRYLFrY6QE61QdTcml0YJzjnsa5klR') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // If the token is valid, continue with the request
        return $next($request);
    }
}