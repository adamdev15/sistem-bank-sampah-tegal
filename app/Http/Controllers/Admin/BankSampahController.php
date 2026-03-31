<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSampahMaster;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Http\Requests\Admin\StoreBankSampahRequest;
use App\Http\Requests\Admin\UpdateBankSampahRequest;
use Illuminate\Http\Request;
use App\Models\AktivitasLog;

class BankSampahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index(Request $request)
    {
        $query = BankSampahMaster::with(['kecamatan', 'kelurahan', 'user']);

        if ($request->kecamatan_id) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        if ($request->status_terbentuk) {
            $query->where('status_terbentuk', $request->status_terbentuk);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_bank_sampah', 'like', "%{$search}%")
                  ->orWhere('nama_direktur', 'like', "%{$search}%")
                  ->orWhere('nomor_sk', 'like', "%{$search}%");
            });
        }

        $bankSampahs = $query->orderBy('created_at', 'desc')->paginate(20);
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('admin.bank-sampah.index', compact('bankSampahs', 'kecamatans', 'kelurahans'));
    }

    /**
     * ✅ CREATE (SUDAH DIGABUNG – TIDAK DUPLIKAT)
     */
    public function create()
    {
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::all();

        return view('admin.bank-sampah.create', compact('kecamatans', 'kelurahans'));
    }

    public function store(StoreBankSampahRequest $request)
    {
        $bankSampah = BankSampahMaster::create($request->validated());

        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambah Data Master Bank Sampah',
            'modul' => 'Bank Sampah Master',
            'deskripsi' => 'Menambah bank sampah: ' . $bankSampah->nama_bank_sampah,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()
            ->route('admin.bank-sampah.show', $bankSampah)
            ->with('success', 'Data bank sampah berhasil ditambahkan.');
    }

    public function show(BankSampahMaster $bankSampah)
    {
        $bankSampah->load([
            'kecamatan',
            'kelurahan',
            'user',
            'operasional',
            'laporans' => fn ($q) => $q->orderBy('periode', 'desc')->limit(12)
        ]);

        return view('admin.bank-sampah.show', compact('bankSampah'));
    }

    public function edit(BankSampahMaster $bankSampah)
    {
        $kecamatans = Kecamatan::all();
        $kelurahans = Kelurahan::where('kecamatan_id', $bankSampah->kecamatan_id)->get();

        return view('admin.bank-sampah.edit', compact('bankSampah', 'kecamatans', 'kelurahans'));
    }

    public function update(UpdateBankSampahRequest $request, BankSampahMaster $bankSampah)
    {
        $bankSampah->update($request->validated());

        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Mengubah Data Master Bank Sampah',
            'modul' => 'Bank Sampah Master',
            'deskripsi' => 'Mengubah data bank sampah: ' . $bankSampah->nama_bank_sampah,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()
            ->route('admin.bank-sampah.show', $bankSampah)
            ->with('success', 'Data bank sampah berhasil diperbarui.');
    }

    public function destroy(Request $request, BankSampahMaster $bankSampah)
    {
        if ($bankSampah->user) {
            return $request->ajax()
                ? response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus bank sampah yang sudah memiliki akun.'
                ], 422)
                : back()->with('error', 'Tidak dapat menghapus bank sampah yang sudah memiliki akun.');
        }

        $bankName = $bankSampah->nama_bank_sampah;
        $bankSampah->delete();

        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus Data Master Bank Sampah',
            'modul' => 'Bank Sampah Master',
            'deskripsi' => 'Menghapus bank sampah: ' . $bankName,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Bank sampah "' . $bankName . '" berhasil dihapus.',
                'redirect' => route('admin.bank-sampah.index')
            ])
            : redirect()->route('admin.bank-sampah.index')
                ->with('success', 'Bank sampah "' . $bankName . '" berhasil dihapus.');
    }

    public function getKelurahan($kecamatanId)
    {
        return response()->json(
            Kelurahan::where('kecamatan_id', $kecamatanId)->get()
        );
    }
}
