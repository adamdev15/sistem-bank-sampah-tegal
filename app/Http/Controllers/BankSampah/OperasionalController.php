<?php

namespace App\Http\Controllers\BankSampah;

use App\Http\Controllers\Controller;
use App\Models\Operasional;
use App\Models\BankSampahMaster;
use Illuminate\Http\Request;
use App\Http\Requests\BankSampah\StoreOperasionalRequest;
use App\Models\AktivitasLog;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OperasionalExport;
use Maatwebsite\Excel\Facades\Excel;

class OperasionalController extends Controller
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

        return view('bank-sampah.operasional.index', compact('bankSampah', 'operasional'));
    }

    public function create()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        
        $existing = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();
        
        if ($existing) {
            return redirect()->route('bank-sampah.operasional.index')
                ->with('info', 'Data operasional sudah ada. Silahkan edit data yang sudah ada.');
        }

        return view('bank-sampah.operasional.create', compact('bankSampah'));
    }

    public function store(StoreOperasionalRequest $request)
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        
        $existing = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();
        if ($existing) {
            return redirect()->route('bank-sampah.operasional.index')
                ->with('error', 'Data operasional sudah ada. Silahkan edit data yang sudah ada.');
        }

        $data = $request->validated();
        $data['bank_sampah_master_id'] = $bankSampah->id;
        
        $operasional = Operasional::create($data);

        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Membuat Data Operasional Baru',
            'modul' => 'Operasional',
            'deskripsi' => 'Membuat data operasional untuk bank sampah ' . $bankSampah->nama_bank_sampah,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('bank-sampah.operasional.show')
            ->with('success', 'Data operasional berhasil dibuat.');
    }

    public function edit()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();

        if (!$operasional) {
            return redirect()->route('bank-sampah.operasional.create')
                ->with('warning', 'Data operasional belum diisi. Silahkan isi data terlebih dahulu.');
        }

        return view('bank-sampah.operasional.edit', compact('bankSampah', 'operasional'));
    }

    public function update(StoreOperasionalRequest $request)
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();
        
        if (!$operasional) {
            return redirect()->route('bank-sampah.operasional.create')
                ->with('error', 'Data operasional tidak ditemukan. Silahkan buat data baru.');
        }

        $data = $request->validated();
        $operasional->update($data);

        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Mengupdate Data Operasional',
            'modul' => 'Operasional',
            'deskripsi' => 'Mengupdate data operasional bank sampah ' . $bankSampah->nama_bank_sampah,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('bank-sampah.operasional.show')
            ->with('success', 'Data operasional berhasil diperbarui.');
    }

    public function show()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();

        if (!$operasional) {
            return redirect()->route('bank-sampah.operasional.index')
                ->with('error', 'Data operasional belum diisi.');
        }

        return view('bank-sampah.operasional.show', compact('bankSampah', 'operasional'));
    }

    /**
     * Export data operasional ke PDF
     */
    public function exportPdf()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();

        if (!$operasional) {
            return redirect()->route('bank-sampah.operasional.index')
                ->with('error', 'Data operasional belum diisi.');
        }

        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Export Data Operasional ke PDF',
            'modul' => 'Operasional',
            'deskripsi' => 'Mengekspor data operasional ke format PDF',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        // Load CSS khusus untuk PDF
        $css = file_get_contents(public_path('css/bank/pdf-operasional.css'));
        
        $pdf = Pdf::loadView('bank-sampah.operasional.export.pdf', [
            'bankSampah' => $bankSampah,
            'operasional' => $operasional,
            'css' => $css
        ]);
        
        // Set paper size dan orientation
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->download('data-operasional-' . $bankSampah->id . '-' . date('Ymd') . '.pdf');
    }

    /**
     * Export data operasional ke Excel
     */
    public function exportExcel()
    {
        $user = auth()->user();
        $bankSampah = $user->bankSampahMaster;
        $operasional = Operasional::where('bank_sampah_master_id', $bankSampah->id)->first();

        if (!$operasional) {
            return redirect()->route('bank-sampah.operasional.index')
                ->with('error', 'Data operasional belum diisi.');
        }

        AktivitasLog::create([
            'user_id' => $user->id,
            'aktivitas' => 'Export Data Operasional ke Excel',
            'modul' => 'Operasional',
            'deskripsi' => 'Mengekspor data operasional ke format Excel',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return Excel::download(new OperasionalExport($operasional, $bankSampah), 
            'data-operasional-' . $bankSampah->id . '-' . date('Ymd') . '.xlsx');
    }
}