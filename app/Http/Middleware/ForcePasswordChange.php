<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->hasTemporaryPassword()) {
            // Kecuali sedang di halaman ganti password atau logout
            if (!$request->is('change-password*') && 
                !$request->is('logout') &&
                !$request->is('password/change*')) {
                return redirect()->route('password.change.required')
                    ->with('warning', 'Anda harus mengganti password sementara terlebih dahulu.');
            }
        }

        return $next($request);
    }
}