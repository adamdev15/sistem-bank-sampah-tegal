<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operasional;
use Illuminate\Http\Request;

class OperasionalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'user.status']);
    }

    public function index(Request $request)
    {
        $query = Operasional::with(['bankSampahMaster.kecamatan', 'bankSampahMaster.kelurahan']);
        
        // Filter
        if ($request->has('kecamatan_id') && $request->kecamatan_id) {
            $query->whereHas('bankSampahMaster', function($q) use ($request) {
                $q->where('kecamatan_id', $request->kecamatan_id);
            });
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('bankSampahMaster', function($q) use ($search) {
                $q->where('nama_bank_sampah', 'like', "%{$search}%")
                  ->orWhere('nama_direktur', 'like', "%{$search}%");
            });
        }
        
        $operasionals = $query->orderBy('created_at', 'desc')->paginate(20);
        $kecamatans = \App\Models\Kecamatan::all();
        
        return view('admin.operasional.index', compact('operasionals', 'kecamatans'));
    }
}