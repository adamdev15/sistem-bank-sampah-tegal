<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankSampahMaster;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BankSampahExport;
use App\Exports\LaporanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index()
    {
        $kecamatans = \App\Models\Kecamatan::all();
        $years = range(date('Y') - 5, date('Y'));
        
        return view('admin.export.index', compact('kecamatans', 'years'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:master,operasional,laporan',
            'format' => 'required|in:excel,pdf',
            'kecamatan_id' => 'nullable|exists:kecamatans,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'tahun' => 'nullable|integer|min:2020|max:' . date('Y')
        ]);

        $data = $this->getExportData($request);
        
        if ($request->format === 'excel') {
            return $this->exportExcel($request->type, $data);
        } else {
            return $this->exportPDF($request->type, $data, $request);
        }
    }

    private function getExportData($request)
    {
        switch ($request->type) {
            case 'master':
                $query = BankSampahMaster::with(['kecamatan', 'kelurahan', 'user']);
                break;
            case 'operasional':
                $query = \App\Models\Operasional::with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan']);
                break;
            case 'laporan':
                $query = Laporan::with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan', 'details']);
                break;
            default:
                return collect();
        }
        
        // Filter kecamatan
        if ($request->kecamatan_id) {
            if ($request->type === 'laporan') {
                $query->whereHas('bankSampahMaster', function ($q) use ($request) {
                    $q->where('kecamatan_id', $request->kecamatan_id);
                });
            } elseif ($request->type === 'master') {
                $query->where('kecamatan_id', $request->kecamatan_id);
            } elseif ($request->type === 'operasional') {
                $query->whereHas('bankSampahMaster', function ($q) use ($request) {
                    $q->where('kecamatan_id', $request->kecamatan_id);
                });
            }
        }
        
        // Filter tanggal untuk laporan
        if ($request->start_date && $request->type === 'laporan') {
            $query->whereDate('periode', '>=', $request->start_date);
        }
        
        if ($request->end_date && $request->type === 'laporan') {
            $query->whereDate('periode', '<=', $request->end_date);
        }
        
        // Filter tahun untuk laporan
        if ($request->tahun && $request->type === 'laporan') {
            $query->whereYear('periode', $request->tahun);
        }
        
        // Order by
        if ($request->type === 'master') {
            $query->orderBy('kecamatan_id')->orderBy('kelurahan_id')->orderBy('rw');
        } elseif ($request->type === 'laporan') {
            $query->orderBy('periode', 'desc');
        }
        
        return $query->get();
    }

    private function exportExcel($type, $data)
    {
        $filename = $type . '_export_' . date('Ymd_His') . '.xlsx';
        
        if ($type === 'master') {
            return Excel::download(new BankSampahExport($data), $filename);
        } else {
            return Excel::download(new LaporanExport($data), $filename);
        }
    }

    private function exportPDF($type, $data, $request)
    {
        $filename = $type . '_export_' . date('Ymd_His') . '.pdf';
        
        // Set title
        $titles = [
            'master' => 'DATA MASTER BANK SAMPAH KOTA TEGAL',
            'operasional' => 'DATA OPERASIONAL BANK SAMPAH KOTA TEGAL',
            'laporan' => 'DATA LAPORAN BULANAN BANK SAMPAH KOTA TEGAL'
        ];
        
        $viewData = [
            'data' => $data,
            'title' => $titles[$type] ?? 'EXPORT DATA',
            'exportDate' => now()->format('d/m/Y H:i:s'),
            'totalData' => $data->count(),
            'filterKecamatan' => null,
            'filterPeriode' => null
        ];
        
        // Tambahkan filter info
        if ($request->kecamatan_id) {
            $kecamatan = \App\Models\Kecamatan::find($request->kecamatan_id);
            if ($kecamatan) {
                $viewData['filterKecamatan'] = $kecamatan->nama_kecamatan;
            }
        }
        
        if ($request->start_date && $request->end_date) {
            $viewData['filterPeriode'] = Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y');
        } elseif ($request->tahun) {
            $viewData['filterPeriode'] = 'Tahun ' . $request->tahun;
        }
        
        // Load view berdasarkan type
        $viewName = 'admin.export.pdf.' . $type;
        
        if (!view()->exists($viewName)) {
            // Fallback
            $pdf = Pdf::loadView('admin.export.pdf.default', $viewData);
        } else {
            $pdf = Pdf::loadView($viewName, $viewData);
        }
        
        // Set paper
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download($filename);
    }
}