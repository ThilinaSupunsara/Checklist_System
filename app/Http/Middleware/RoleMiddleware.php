<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated and has the required role
        if (!Auth::check() || Auth::user()->role !== $role) {
            // If not, redirect to the dashboard with an error message
            return redirect('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        // If they have the correct role, allow the request to proceed
        return $next($request);
    }
}
