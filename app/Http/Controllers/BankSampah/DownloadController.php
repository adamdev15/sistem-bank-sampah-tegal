<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Operasional;
use App\Models\BankSampahMaster;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BankSampahLaporanExport;
use App\Exports\BankSampahOperasionalExport;
use Carbon\Carbon;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:bank_sampah', 'user.status']);
    }

    /**
     * Display download page
     */
    public function index()
    {
        $user = auth()->user();
        
        // Get bank sampah data
        $bankSampah = BankSampahMaster::with(['kecamatan', 'kelurahan'])
            ->find($user->bank_sampah_master_id);
            
        // Get latest operational data
        $operasional = Operasional::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->first();
            
        // Get all reports
        $laporans = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->with('details')
            ->orderBy('periode', 'desc')
            ->get();

        return view('bank-sampah.download.index', compact('bankSampah', 'operasional', 'laporans'));
    }

    /**
     * Download single report as PDF
     */
    public function laporan(Laporan $laporan)
    {
        // Authorization check
        if ($laporan->bank_sampah_master_id != auth()->user()->bank_sampah_master_id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        // Load relations
        $laporan->load([
            'bankSampahMaster.kecamatan', 
            'bankSampahMaster.kelurahan', 
            'details'
        ]);
        
        // Generate PDF filename
        $filename = 'Laporan-' . $laporan->bankSampahMaster->nama_bank_sampah . '-' . 
                   $laporan->periode->format('F-Y') . '.pdf';
        
        // Clean filename (remove special characters)
        $filename = preg_replace('/[^A-Za-z0-9\-\.]/', '', $filename);
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf.laporan-bank-sampah', compact('laporan'));
        
        return $pdf->download($filename);
    }

    /**
     * Download operational data
     */
    public function operasional(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'pdf');
        
        // Get operational data with relations
        $operasional = Operasional::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan'])
            ->first();

        if (!$operasional) {
            return back()->with('error', 'Data operasional belum diisi.');
        }

        // Generate filename
        $bankName = str_replace(' ', '-', $operasional->bankSampahMaster->nama_bank_sampah);
        $date = date('Y-m-d');
        
        if ($format === 'excel') {
            $filename = 'Data-Operasional-' . $bankName . '-' . $date . '.xlsx';
            
            return Excel::download(
                new BankSampahOperasionalExport($operasional), 
                $filename
            );
        } else {
            $filename = 'Data-Operasional-' . $bankName . '-' . $date . '.pdf';
            
            $pdf = Pdf::loadView('pdf.operasional-bank-sampah', compact('operasional'));
            return $pdf->download($filename);
        }
    }

    /**
     * Download all reports
     */
    public function allReports(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format', 'pdf');
        
        // Get all reports with relations
        $laporans = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->with([
                'bankSampahMaster.kecamatan', 
                'bankSampahMaster.kelurahan', 
                'details'
            ])
            ->orderBy('periode', 'desc')
            ->get();

        if ($laporans->isEmpty()) {
            return back()->with('error', 'Belum ada laporan yang dibuat.');
        }

        // Get bank sampah info for filename
        $bankSampah = BankSampahMaster::find($user->bank_sampah_master_id);
        $bankName = $bankSampah ? str_replace(' ', '-', $bankSampah->nama_bank_sampah) : 'Bank-Sampah';
        $date = date('Y-m-d');
        
        if ($format === 'excel') {
            $filename = 'Semua-Laporan-' . $bankName . '-' . $date . '.xlsx';
            
            return Excel::download(
                new BankSampahLaporanExport($laporans), 
                $filename
            );
        } else {
            $filename = 'Semua-Laporan-' . $bankName . '-' . $date . '.pdf';
            
            $pdf = Pdf::loadView('pdf.semua-laporan-bank-sampah', compact('laporans'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'landscape');
            
            return $pdf->download($filename);
        }
    }

    /**
     * Download reports by period range
     */
    public function reportsByPeriod(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:excel,pdf'
        ]);
        
        $user = auth()->user();
        $startDate = Carbon::parse($request->start_date)->startOfMonth();
        $endDate = Carbon::parse($request->end_date)->endOfMonth();
        $format = $request->format;
        
        // Get reports within period
        $laporans = Laporan::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->whereBetween('periode', [$startDate, $endDate])
            ->with([
                'bankSampahMaster.kecamatan', 
                'bankSampahMaster.kelurahan', 
                'details'
            ])
            ->orderBy('periode', 'asc')
            ->get();
            
        if ($laporans->isEmpty()) {
            return back()->with('error', 'Tidak ada laporan pada periode tersebut.');
        }
        
        // Generate filename
        $bankSampah = BankSampahMaster::find($user->bank_sampah_master_id);
        $bankName = $bankSampah ? str_replace(' ', '-', $bankSampah->nama_bank_sampah) : 'Bank-Sampah';
        $start = $startDate->format('Y-m');
        $end = $endDate->format('Y-m');
        
        if ($format === 'excel') {
            $filename = 'Laporan-' . $bankName . '-' . $start . '-sampai-' . $end . '.xlsx';
            
            return Excel::download(
                new BankSampahLaporanExport($laporans), 
                $filename
            );
        } else {
            $filename = 'Laporan-' . $bankName . '-' . $start . '-sampai-' . $end . '.pdf';
            
            $pdf = Pdf::loadView('pdf.semua-laporan-bank-sampah', [
                'laporans' => $laporans,
                'period' => $startDate->translatedFormat('F Y') . ' - ' . $endDate->translatedFormat('F Y')
            ]);
            
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download($filename);
        }
    }

    /**
     * Preview report before download
     */
    public function previewLaporan(Laporan $laporan)
    {
        // Authorization check
        if ($laporan->bank_sampah_master_id != auth()->user()->bank_sampah_master_id) {
            abort(403);
        }

        $laporan->load([
            'bankSampahMaster.kecamatan', 
            'bankSampahMaster.kelurahan', 
            'details'
        ]);
        
        return view('bank-sampah.download.preview-laporan', compact('laporan'));
    }

    /**
     * Preview operational data before download
     */
    public function previewOperasional()
    {
        $user = auth()->user();
        
        $operasional = Operasional::where('bank_sampah_master_id', $user->bank_sampah_master_id)
            ->with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan'])
            ->first();
            
        if (!$operasional) {
            return back()->with('error', 'Data operasional belum diisi.');
        }
        
        return view('bank-sampah.download.preview-operasional', compact('operasional'));
    }
}