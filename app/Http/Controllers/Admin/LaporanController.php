<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use App\Models\AktivitasLog;

class LaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index(Request $request)
    {
        $query = Laporan::with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan', 'details']);
        
        // Filter status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter periode
        if ($request->has('periode') && $request->periode) {
            $query->whereYear('periode', date('Y', strtotime($request->periode)))
                  ->whereMonth('periode', date('m', strtotime($request->periode)));
        }
        
        // Filter kecamatan
        if ($request->has('kecamatan_id') && $request->kecamatan_id) {
            $query->whereHas('bankSampahMaster', function($q) use ($request) {
                $q->where('kecamatan_id', $request->kecamatan_id);
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('bankSampahMaster', function($q) use ($search) {
                $q->where('nama_bank_sampah', 'like', "%{$search}%");
            });
        }
        
        $laporans = $query->orderBy('created_at', 'desc')->paginate(20);
        $kecamatans = \App\Models\Kecamatan::all();
        
        return view('admin.laporan.index', compact('laporans', 'kecamatans'));
    }

    public function show(Laporan $laporan)
    {
        $laporan->load(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan', 'details']);
    
        return view('admin.laporan.show', compact('laporan'));
    }

    public function verify(Request $request, Laporan $laporan)
    {
        $request->validate([
            'status' => 'required|in:disetujui,perlu_perbaikan',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);
        
        $oldStatus = $laporan->status;
        $laporan->update([
            'status' => $request->status,
            'catatan_verifikasi' => $request->catatan_verifikasi
        ]);
        
        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Verifikasi Laporan',
            'modul' => 'Laporan',
            'deskripsi' => 'Mengubah status laporan ' . $laporan->bankSampahMaster->nama_bank_sampah . 
                         ' dari ' . $oldStatus . ' menjadi ' . $request->status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->route('admin.laporan.index')
            ->with('success', 'Laporan berhasil diverifikasi.');
    }
}