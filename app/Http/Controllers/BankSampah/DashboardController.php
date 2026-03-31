<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use App\Models\BankSampahMaster;
use App\Models\Operasional;
use App\Models\Laporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:bank_sampah', 'user.status']);
    }

    public function index()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();
        
        // Hitung total sampah terkelola
        $totalSampah = Laporan::where('bank_sampah_master_id', $bankSampah->id)
            ->where('status', 'disetujui')
            ->sum('jumlah_sampah_terkelola');
            
        // Total laporan
        $totalLaporan = Laporan::where('bank_sampah_master_id', $bankSampah->id)->count();
        
        // Status laporan bulan ini
        $currentMonth = now()->format('Y-m');
        $laporanBulanIni = Laporan::where('bank_sampah_master_id', $bankSampah->id)
            ->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentMonth])
            ->first();
        
        // Laporan terbaru (5 terakhir)
        $recentLaporan = Laporan::where('bank_sampah_master_id', $bankSampah->id)
            ->orderBy('periode', 'desc')
            ->limit(5)
            ->get();

        return view('bank-sampah.dashboard.index', compact(
            'bankSampah',
            'operasional',
            'totalSampah',
            'totalLaporan',
            'laporanBulanIni',
            'recentLaporan'
        ));
    }
}