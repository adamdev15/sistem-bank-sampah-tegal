<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function getKelurahan($kecamatanId)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)
            ->orderBy('nama_kelurahan')
            ->get(['id', 'nama_kelurahan']);

        return response()->json($kelurahans);
    }
}