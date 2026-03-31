<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\LaporanDetail;
use App\Http\Requests\BankSampah\StoreLaporanRequest;
use App\Http\Requests\BankSampah\UpdateLaporanRequest;
use Illuminate\Http\Request;
use App\Models\AktivitasLog;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:bank_sampah', 'user.status']);
    }

    /* =====================================================
       INDEX
       ===================================================== */
    public function index(Request $request)
    {
        $user = auth()->user();
        $bankSampahId = $user->bank_sampah_master_id;

        $query = Laporan::where('bank_sampah_master_id', $bankSampahId);

        if ($request->filled('tahun')) {
            $query->whereYear('periode', $request->tahun);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporans = $query->orderBy('periode', 'desc')->paginate(10);

        $totalLaporan = Laporan::where('bank_sampah_master_id', $bankSampahId)->count();
        $laporanDisetujui = Laporan::where('bank_sampah_master_id', $bankSampahId)
            ->where('status', 'disetujui')->count();
        $laporanMenunggu = Laporan::where('bank_sampah_master_id', $bankSampahId)
            ->where('status', 'menunggu_verifikasi')->count();
        $laporanPerbaikan = Laporan::where('bank_sampah_master_id', $bankSampahId)
            ->where('status', 'perlu_perbaikan')->count();

        $currentMonth = now()->format('Y-m');
        $hasCurrentMonthReport = Laporan::where('bank_sampah_master_id', $bankSampahId)
            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentMonth])
            ->exists();

        $years = Laporan::where('bank_sampah_master_id', $bankSampahId)
            ->selectRaw('YEAR(periode) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('bank-sampah.laporan.index', compact(
            'laporans',
            'totalLaporan',
            'laporanDisetujui',
            'laporanMenunggu',
            'laporanPerbaikan',
            'hasCurrentMonthReport',
            'currentMonth',
            'years'
        ));
    }

    /* =====================================================
       CHECK EXISTING (AJAX)
       ===================================================== */
    public function checkExisting(Request $request)
    {
        $request->validate([
            'periode' => 'required|date_format:Y-m'
        ]);

        $user = auth()->user();

        $laporan = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$request->periode])
            ->first();

        if ($laporan) {
            return response()->json([
                'exists' => true,
                'edit_url' => route('bank-sampah.laporan.edit', $laporan->id)
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /* =====================================================
       CREATE
       ===================================================== */
    public function create()
    {
        $user = auth()->user();

        $currentMonth = now()->format('Y-m');
        $existing = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentMonth])
            ->first();

        if ($existing) {
            return redirect()->route('bank-sampah.laporan.edit', $existing->id)
                ->with('warning', 'Anda sudah membuat laporan bulan ini. Silahkan edit jika ada perubahan.');
        }

        $lastMonth = now()->subMonth()->format('Y-m');
        $lastReport = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$lastMonth])
            ->with('details')
            ->first();

        $jenisSampah = $this->getJenisSampahList();

        return view('bank-sampah.laporan.create', compact('jenisSampah', 'lastReport'));
    }

    /* =====================================================
       STORE (FORMAT ANGKA FIX)
       ===================================================== */
    public function store(StoreLaporanRequest $request)
    {
        $user = auth()->user();

        $jumlahMasuk = str_replace(',', '.', $request->jumlah_sampah_masuk);
        $jumlahTerkelola = str_replace(',', '.', $request->jumlah_sampah_terkelola);

        $laporan = Laporan::create([
            'bank_sampah_master_id' => $user->bank_sampah_master_id,
            'periode' => $request->periode,
            'jumlah_sampah_masuk' => $jumlahMasuk,
            'jumlah_sampah_terkelola' => $jumlahTerkelola,
            'jumlah_nasabah' => $request->jumlah_nasabah,
            'status' => 'menunggu_verifikasi'
        ]);

        foreach ($request->rincian_sampah as $jenis => $jumlah) {
            $jumlahFormatted = str_replace(',', '.', $jumlah);
            if ($jumlahFormatted > 0) {
                LaporanDetail::create([
                    'laporan_id' => $laporan->id,
                    'jenis_sampah' => $jenis,
                    'jumlah' => $jumlahFormatted
                ]);
            }
        }

        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Membuat Laporan Bulanan',
            'modul' => 'Laporan',
            'deskripsi' => 'Membuat laporan periode ' . $request->periode,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('bank-sampah.laporan.index')
            ->with('success', 'Laporan berhasil dibuat. Menunggu verifikasi dari Admin DLH.');
    }

    /* =====================================================
       SHOW
       ===================================================== */
    public function show(Laporan $laporan)
    {
        if ($laporan->bank_sampah_master_id !== auth()->user()->bank_sampah_master_id) {
            abort(403);
        }

        $laporan->load('details');
        $jenisSampah = $this->getJenisSampahList();

        return view('bank-sampah.laporan.show', compact('laporan', 'jenisSampah'));
    }

    /* =====================================================
       EDIT
       ===================================================== */
    public function edit(Laporan $laporan)
    {
        if ($laporan->bank_sampah_master_id !== auth()->user()->bank_sampah_master_id) {
            abort(403);
        }

        if ($laporan->status === 'disetujui') {
            return redirect()->route('bank-sampah.laporan.show', $laporan)
                ->with('error', 'Laporan yang sudah disetujui tidak dapat diubah.');
        }

        $laporan->load('details');
        $jenisSampah = $this->getJenisSampahList();

        $rincian = [];
        foreach ($laporan->details as $detail) {
            $rincian[$detail->jenis_sampah] = $detail->jumlah;
        }

        return view('bank-sampah.laporan.edit', compact('laporan', 'jenisSampah', 'rincian'));
    }

    /* =====================================================
       UPDATE (FORMAT ANGKA FIX)
       ===================================================== */
    public function update(UpdateLaporanRequest $request, Laporan $laporan)
    {
        if ($laporan->bank_sampah_master_id !== auth()->user()->bank_sampah_master_id) {
            abort(403);
        }

        $laporan->update([
            'jumlah_sampah_masuk' => str_replace(',', '.', $request->jumlah_sampah_masuk),
            'jumlah_sampah_terkelola' => str_replace(',', '.', $request->jumlah_sampah_terkelola),
            'jumlah_nasabah' => $request->jumlah_nasabah,
            'status' => 'menunggu_verifikasi'
        ]);

        $laporan->details()->delete();

        foreach ($request->rincian_sampah as $jenis => $jumlah) {
            $jumlahFormatted = str_replace(',', '.', $jumlah);
            if ($jumlahFormatted > 0) {
                LaporanDetail::create([
                    'laporan_id' => $laporan->id,
                    'jenis_sampah' => $jenis,
                    'jumlah' => $jumlahFormatted
                ]);
            }
        }

        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Mengubah Laporan Bulanan',
            'modul' => 'Laporan',
            'deskripsi' => 'Mengubah laporan periode ' . $laporan->periode,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('bank-sampah.laporan.index')
            ->with('success', 'Laporan berhasil diperbarui. Menunggu verifikasi ulang.');
    }

    /* =====================================================
       DESTROY
       ===================================================== */
    public function destroy(Laporan $laporan)
    {
        if ($laporan->bank_sampah_master_id !== auth()->user()->bank_sampah_master_id) {
            abort(403);
        }

        if ($laporan->status === 'disetujui') {
            return back()->with('error', 'Laporan yang sudah disetujui tidak dapat dihapus.');
        }

        $laporan->delete();

        return redirect()->route('bank-sampah.laporan.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    /* =====================================================
       HELPER
       ===================================================== */
    private function getJenisSampahList()
    {
        return [
            'plastik_keras' => 'Plastik Keras',
            'plastik_fleksibel' => 'Plastik Fleksibel',
            'kertas_karton' => 'Kertas/Karton',
            'logam' => 'Logam',
            'kaca' => 'Kaca',
            'karet_kulit' => 'Karet/Kulit',
            'kain_tekstil' => 'Kain/Tekstil',
            'lainnya' => 'Lainnya'
        ];
    }
}
