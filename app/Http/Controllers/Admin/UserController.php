<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AktivitasLog;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    /**
     * List user Bank Sampah
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'bank_sampah')
            ->with([
                'bankSampahMaster.kecamatan',
                'bankSampahMaster.kelurahan'
            ]);

        // Filter status akun
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('bankSampahMaster', function ($q2) use ($search) {
                        $q2->where('nama_bank_sampah', 'like', "%{$search}%")
                            ->orWhere('nama_direktur', 'like', "%{$search}%");
                    });
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form reset password user
     */
    public function showResetForm(User $user)
    {
        return view('admin.users.reset-password', compact('user'));
    }

    /**
     * Verifikasi akun user
     */
    public function verify(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:aktif,ditolak',
            'catatan' => 'nullable|string|max:500'
        ]);

        $oldStatus = $user->status;

        $user->update([
            'status' => $request->status
        ]);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Verifikasi Akun Bank Sampah',
            'modul' => 'Users',
            'deskripsi' => 'Mengubah status akun ' . $user->email .
                ' dari ' . $oldStatus . ' menjadi ' . $request->status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->back()
            ->with('success', 'Status akun berhasil diperbarui.');
    }

    /**
     * Reset password user
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'force_password_change' => 'nullable|boolean'
        ]);

        $user->update([
            'password' => Hash::make($request->password),
            'is_temporary_password' => $request->has('force_password_change'),
            'password_changed_at' => $request->has('force_password_change')
                ? null
                : now()
        ]);

        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Reset Password Akun',
            'modul' => 'Users',
            'deskripsi' => 'Reset password untuk akun ' . $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Password berhasil direset.');
    }
}
