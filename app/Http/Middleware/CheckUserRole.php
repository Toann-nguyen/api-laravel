<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ... $roles): Response
    {
        if(!auth()->check()){
            return response()->json([
                'message' => 'Authorized'
            ], 401);
        }

        $userRole = auth()->user()->role()->name ?? null;

        if(!in_array($userRole , $roles)){
            return response()->json([
                'message' => 'Insufficient perrmissions.',
            ], 403);
        }
        return $next($request);
    }
}
