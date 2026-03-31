<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSampahMaster;
use App\Models\Laporan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function statistics(Request $request)
    {
        // Filter tahun
        $year = $request->get('year', date('Y'));
        
        // Statistik utama
        $stats = [
            'total_bank_sampah' => BankSampahMaster::count(),
            'bank_sampah_aktif' => BankSampahMaster::whereHas('user', function($q) {
                $q->where('status', 'aktif');
            })->count(),
            'bank_sampah_belum_aktif' => BankSampahMaster::whereHas('user', function($q) {
                $q->where('status', 'menunggu_verifikasi');
            })->count(),
        ];

        // Data laporan per bulan
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = [
                'month' => Carbon::create($year, $i, 1)->translatedFormat('M'),
                'laporan_count' => Laporan::whereYear('periode', $year)
                    ->whereMonth('periode', $i)
                    ->where('status', 'disetujui')
                    ->count(),
                'sampah_terkelola' => Laporan::whereYear('periode', $year)
                    ->whereMonth('periode', $i)
                    ->where('status', 'disetujui')
                    ->sum('jumlah_sampah_terkelola') ?? 0
            ];
        }

        // Data per kecamatan
        $kecamatanData = Kecamatan::withCount(['bankSampahMasters' => function($q) {
            $q->whereHas('user', function($q2) {
                $q2->where('status', 'aktif');
            });
        }])->get();

        // Tahun untuk filter
        $years = range(date('Y') - 5, date('Y'));

        return view('admin.reports.statistics', compact(
            'stats', 
            'monthlyData', 
            'kecamatanData',
            'year',
            'years'
        ));
    }
}