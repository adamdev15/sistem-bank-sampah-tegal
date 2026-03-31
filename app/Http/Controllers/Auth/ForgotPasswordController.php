<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AktivitasLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $verifyMessage = 'Email belum diverifikasi. Cek inbox Anda untuk link verifikasi.';

            try {
                $user->sendEmailVerificationNotification();
            } catch (TransportExceptionInterface $e) {
                $verifyMessage = 'Email belum diverifikasi, dan kirim ulang verifikasi gagal karena gangguan SMTP.';
            }

            return back()->withErrors([
                'email' => $verifyMessage,
            ])->with([
                'show_verify_modal' => true,
                'verify_modal_message' => $verifyMessage,
            ])->onlyInput('email');
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            if ($user) {
                AktivitasLog::create([
                    'user_id' => $user->id,
                    'aktivitas' => 'Permintaan Reset Password',
                    'modul' => 'Auth',
                    'deskripsi' => 'Link reset password dikirim ke email: ' . $user->email,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            return back()->with('status', 'Link reset password sudah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) use ($request) {
                $payload = [
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ];

                // Keep reset flow compatible if optional columns are not migrated yet.
                if (Schema::hasColumn('users', 'is_temporary_password')) {
                    $payload['is_temporary_password'] = false;
                }

                if (Schema::hasColumn('users', 'password_changed_at')) {
                    $payload['password_changed_at'] = now();
                }

                $user->forceFill($payload)->save();

                AktivitasLog::create([
                    'user_id' => $user->id,
                    'aktivitas' => 'Reset Password Berhasil',
                    'modul' => 'Auth',
                    'deskripsi' => 'Password berhasil direset melalui email',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}