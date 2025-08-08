<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware is for user management functions only
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Check if user is admin (only admin can manage users)
        if (!Auth::user()->is_admin) {
            return redirect()->route('materials.index')->with('error', 'Akses ditolak. Hanya admin yang dapat mengelola pengguna.');
        }

        return $next($request);
    }
}
