<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminType
{
    public function handle(Request $request, Closure $next, ...$allowedTypes): Response
    {
        if (!Auth::guard('Admin')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'code' => 401,
            ], 401);
        }

        $userType = Auth::guard('Admin')->user()->type;

        if (!in_array($userType, $allowedTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Access Denied',
                'code' => 403,
            ], 403);
        }

        return $next($request);
    }
}
