<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            $user = $request->user();
            
            // Jika status menunggu verifikasi
            if ($user->status === 'menunggu_verifikasi') {
                auth()->logout();
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun Anda masih menunggu verifikasi dari Admin DLH. ' .
                                  'Silahkan hubungi admin untuk aktivasi akun.'
                    ]);
            }
            
            // Jika status ditolak
            if ($user->status === 'ditolak') {
                auth()->logout();
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun Anda ditolak. ' .
                                  'Silahkan hubungi Admin DLH untuk informasi lebih lanjut.'
                    ]);
            }
            
            // Jika status tidak aktif (selain 'aktif')
            if ($user->status !== 'aktif') {
                auth()->logout();
                return redirect()->route('login')
                    ->withErrors([
                        'email' => 'Akun Anda tidak aktif. ' .
                                  'Silahkan hubungi Admin DLH.'
                    ]);
            }
        }

        return $next($request);
    }
}