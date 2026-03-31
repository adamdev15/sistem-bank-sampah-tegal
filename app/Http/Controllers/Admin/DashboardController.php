<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // PASTIKAN INI
use App\Models\BankSampahMaster;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller // EXTENDS YANG BENAR
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index()
    {
        $totalBankSampah = BankSampahMaster::count();
        $totalBankSampahAktif = BankSampahMaster::whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count();
        
        $totalUsers = User::where('role', 'bank_sampah')->count();
        $pendingUsers = User::where('status', 'menunggu_verifikasi')->count();
        
        // Total sampah terkelola tahun ini
        $currentYear = Carbon::now()->year;
        $totalSampah = Laporan::whereYear('periode', $currentYear)
            ->where('status', 'disetujui')
            ->sum('jumlah_sampah_terkelola');
        
        // Data untuk chart per kecamatan
        $kecamatans = Kecamatan::withCount(['bankSampahMasters' => function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('status', 'aktif');
            });
        }])->get();
        
        // Data untuk chart laporan bulanan
        $monthlyReports = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyReports[$i] = Laporan::whereYear('periode', $currentYear)
                ->whereMonth('periode', $i)
                ->where('status', 'disetujui')
                ->count();
        }
        
        // Bank sampah yang belum melapor bulan ini
        $currentMonth = Carbon::now()->format('Y-m');
        $belumMelapor = BankSampahMaster::whereHas('user', function ($query) {
                $query->where('status', 'aktif');
            })
            ->whereDoesntHave('laporans', function ($query) use ($currentMonth) {
                $query->whereRaw("DATE_FORMAT(periode, '%Y-%m') = ?", [$currentMonth]);
            })
            ->count();

        return view('admin.dashboard.index', compact(
            'totalBankSampah',
            'totalBankSampahAktif',
            'totalUsers',
            'pendingUsers',
            'totalSampah',
            'kecamatans',
            'monthlyReports',
            'belumMelapor'
        ));
    }
}