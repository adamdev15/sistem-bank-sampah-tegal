<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use App\Models\AktivitasLog;

class WilayahController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function kecamatan()
    {
        $kecamatans = Kecamatan::withCount('kelurahans')->get();
        $kelurahans = Kelurahan::with('kecamatan')->orderBy('nama_kelurahan')->get();
        return view('admin.wilayah.kecamatan', compact('kecamatans', 'kelurahans'));
    }

    public function kelurahan()
    {
        $kelurahans = Kelurahan::with('kecamatan')->get();
        $kecamatans = Kecamatan::all();
        return view('admin.wilayah.kelurahan', compact('kelurahans', 'kecamatans'));
    }

    public function storeKecamatan(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:100|unique:kecamatans'
        ]);
        
        $kecamatan = Kecamatan::create($request->all());
        
        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambah Data Kecamatan',
            'modul' => 'Wilayah',
            'deskripsi' => 'Menambah kecamatan: ' . $kecamatan->nama_kecamatan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->back()->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function storeKelurahan(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:100'
        ]);
        
        $kelurahan = Kelurahan::create($request->all());
        
        // Log aktivitas
        AktivitasLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambah Data Kelurahan',
            'modul' => 'Wilayah',
            'deskripsi' => 'Menambah kelurahan: ' . $kelurahan->nama_kelurahan,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->back()->with('success', 'Kelurahan berhasil ditambahkan.');
    }

    public function updateKecamatan(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:100|unique:kecamatans,nama_kecamatan,' . $kecamatan->id
        ]);

        $kecamatan->update($request->only('nama_kecamatan'));

        return redirect()->back()->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroyKecamatan(Kecamatan $kecamatan)
    {
        if ($kecamatan->kelurahans()->count() > 0) {
            return redirect()->back()->with('error', 'Kecamatan tidak bisa dihapus karena masih memiliki data kelurahan.');
        }

        $kecamatan->delete();
        return redirect()->back()->with('success', 'Kecamatan berhasil dihapus.');
    }

    public function updateKelurahan(Request $request, Kelurahan $kelurahan)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama_kelurahan' => 'required|string|max:100'
        ]);

        $kelurahan->update($request->only('kecamatan_id', 'nama_kelurahan'));
        return redirect()->back()->with('success', 'Kelurahan berhasil diperbarui.');
    }

    public function destroyKelurahan(Kelurahan $kelurahan)
    {
        $kelurahan->delete();
        return redirect()->back()->with('success', 'Kelurahan berhasil dihapus.');
    }
}