<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AktivitasLog;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Update last login
            $user = Auth::user();

            if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
                $verifyMessage = 'Email belum diverifikasi. Link verifikasi baru sudah dikirim ke email Anda.';

                try {
                    $user->sendEmailVerificationNotification();
                } catch (TransportExceptionInterface $e) {
                    $verifyMessage = 'Email belum diverifikasi. Gagal mengirim ulang email verifikasi karena gangguan SMTP.';
                }

                Auth::logout();

                return back()->withErrors([
                    'email' => $verifyMessage,
                ])->with([
                    'show_verify_modal' => true,
                    'verify_modal_message' => $verifyMessage,
                ])->onlyInput('email');
            }

            $user->updateLastLogin();
            
            // Log aktivitas
            AktivitasLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Login Sistem',
                'modul' => 'Auth',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('bank-sampah.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log aktivitas logout
        if ($user) {
            AktivitasLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Logout Sistem',
                'modul' => 'Auth',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}