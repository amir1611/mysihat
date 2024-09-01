<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 
class UserAccess
{
    public function handle(Request $request, Closure $next, $userType): Response
    {
        if (Auth::check() && Auth::user()->type == $userType) {
            return $next($request);
        }
    
        return response()->json(['You do not have permission to access this page.'], 403);
    }
}