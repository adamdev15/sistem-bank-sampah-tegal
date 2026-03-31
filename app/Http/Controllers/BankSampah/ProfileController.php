<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AktivitasLog;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:bank_sampah', 'user.status']);
    }

    public function index()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster()->with(['kecamatan', 'kelurahan'])->first();
        
        return view('bank-sampah.profile.index', compact('user', 'bankSampah'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed'
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email
        ];
        
        // Jika ada permintaan ganti password
        if ($request->new_password) {
            // Verifikasi password saat ini
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'Password saat ini salah.'
                ]);
            }
            
            $data['password'] = Hash::make($request->new_password);
            $data['is_temporary_password'] = false;
            $data['password_changed_at'] = now();
            
            // Log aktivitas ganti password
            AktivitasLog::create([
                'user_id' => $user->id,
                'aktivitas' => 'Ganti Password',
                'modul' => 'Profile',
                'deskripsi' => 'User mengganti password',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
        }
        
        $user->update($data);
        
        // Log aktivitas update profile
        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Update Profile',
            'modul' => 'Profile',
            'deskripsi' => 'Mengupdate informasi profile',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}