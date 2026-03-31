<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AktivitasLog;

class ChangePasswordController extends Controller
{
    public function showRequiredForm()
    {
        return view('auth.change-password-required');
    }

    public function forceChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verifikasi password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
            'is_temporary_password' => false,
            'password_changed_at' => now()
        ]);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Mengganti Password Wajib',
            'modul' => 'Auth',
            'deskripsi' => 'User mengganti password sementara',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('home')
            ->with('success', 'Password berhasil diganti. Selamat datang!');
    }
}