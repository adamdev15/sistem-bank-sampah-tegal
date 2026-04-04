<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BankSampahMaster;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\AktivitasLog;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $bankSampahs = BankSampahMaster::with(['kecamatan', 'kelurahan', 'user'])
            ->orderBy('nama_bank_sampah')
            ->get()
            ->groupBy(function ($item) {
                return $item->kecamatan->nama_kecamatan . ' - ' . $item->kelurahan->nama_kelurahan;
            });

        return view('auth.register', compact('bankSampahs'));
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'bank_sampah',
            'status' => 'menunggu_verifikasi',
            'bank_sampah_master_id' => $request->bank_sampah_master_id
        ]);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Pendaftaran Akun Baru',
            'modul' => 'Auth',
            'deskripsi' => 'Bank sampah ' . $user->bankSampahMaster->nama_bank_sampah . ' mendaftar',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            event(new Registered($user));
        } catch (TransportExceptionInterface $e) {
            return redirect()->route('login')->with([
                'show_verify_modal' => true,
                'verify_modal_message' => 'Akun berhasil dibuat, tapi email verifikasi belum terkirim karena gangguan SMTP. Silakan login untuk kirim ulang verifikasi.',
                'success' => 'Pendaftaran berhasil! Silakan login, lalu kirim ulang verifikasi email.',
            ]);
        }

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil! Cek email Anda untuk verifikasi akun.');
    }
}