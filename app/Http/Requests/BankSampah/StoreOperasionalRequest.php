<?php

namespace App\Http\Requests\BankSampah;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperasionalRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isBankSampah();
    }

    public function rules()
    {
        return [
            // Tenaga Kerja
            'tenaga_kerja_laki' => ['required', 'integer', 'min:0', 'max:1000'],
            'tenaga_kerja_perempuan' => ['required', 'integer', 'min:0', 'max:1000'],
            
            // Nasabah
            'nasabah_laki' => ['required', 'integer', 'min:0', 'max:10000'],
            'nasabah_perempuan' => ['required', 'integer', 'min:0', 'max:10000'],
            
            // Omset
            'omset' => ['required', 'numeric', 'min:0', 'max:100000000000'],
            
            // Tempat Penjualan
            'tempat_penjualan' => ['required', 'in:bank_sampah_induk,pengepul,lainnya'],
            'tempat_penjualan_lainnya' => ['nullable', 'string', 'max:200', 'required_if:tempat_penjualan,lainnya'],
            
            // Kegiatan & Produk
            'kegiatan_pengelolaan' => ['required', 'string', 'min:10', 'max:1000'],
            'produk_daur_ulang' => ['nullable', 'string', 'max:500'],
            
            // Buku Tabungan
            'buku_tabungan' => ['required', 'in:ada,tidak_ada'],
            
            // Sistem Pencatatan
            'sistem_pencatatan' => ['required', 'in:Manual,Komputerisasi,Aplikasi'],
            
            // Timbangan
            'timbangan' => ['required', 'in:tidak_ada,timbangan_gantung,timbangan_digital,timbangan_posyandu,timbangan_duduk'],
            
            // Alat Pengangkut
            'alat_pengangkut' => ['required', 'in:Tidak_ada,Becak,Gerobak,Tossa,Lainnya'],
            'alat_pengangkut_lainnya' => ['nullable', 'string', 'max:100', 'required_if:alat_pengangkut,Lainnya'],
        ];
    }

    public function messages()
    {
        return [
            'tenaga_kerja_laki.required' => 'Jumlah tenaga kerja laki-laki harus diisi',
            'tenaga_kerja_perempuan.required' => 'Jumlah tenaga kerja perempuan harus diisi',
            'nasabah_laki.required' => 'Jumlah nasabah laki-laki harus diisi',
            'nasabah_perempuan.required' => 'Jumlah nasabah perempuan harus diisi',
            'omset.required' => 'Omset bulanan harus diisi',
            'tempat_penjualan.required' => 'Pilih tempat penjualan',
            'kegiatan_pengelolaan.required' => 'Deskripsi kegiatan pengelolaan harus diisi',
            'buku_tabungan.required' => 'Pilih status buku tabungan',
            'sistem_pencatatan.required' => 'Pilih sistem pencatatan',
            'timbangan.required' => 'Pilih jenis timbangan',
            'alat_pengangkut.required' => 'Pilih alat pengangkut',
            
            'tempat_penjualan_lainnya.required_if' => 'Sebutkan tempat penjualan lainnya',
            'alat_pengangkut_lainnya.required_if' => 'Sebutkan alat pengangkut lainnya',
            
            'tenaga_kerja_laki.min' => 'Jumlah tenaga kerja tidak boleh negatif',
            'tenaga_kerja_perempuan.min' => 'Jumlah tenaga kerja tidak boleh negatif',
            'nasabah_laki.min' => 'Jumlah nasabah tidak boleh negatif',
            'nasabah_perempuan.min' => 'Jumlah nasabah tidak boleh negatif',
            'omset.min' => 'Omset tidak boleh negatif',
        ];
    }

    public function attributes()
    {
        return [
            'tenaga_kerja_laki' => 'Tenaga Kerja Laki-laki',
            'tenaga_kerja_perempuan' => 'Tenaga Kerja Perempuan',
            'nasabah_laki' => 'Nasabah Laki-laki',
            'nasabah_perempuan' => 'Nasabah Perempuan',
            'omset' => 'Omset Bulanan',
            'tempat_penjualan' => 'Tempat Penjualan',
            'tempat_penjualan_lainnya' => 'Tempat Penjualan Lainnya',
            'kegiatan_pengelolaan' => 'Kegiatan Pengelolaan',
            'produk_daur_ulang' => 'Produk Daur Ulang',
            'buku_tabungan' => 'Buku Tabungan',
            'sistem_pencatatan' => 'Sistem Pencatatan',
            'timbangan' => 'Timbangan',
            'alat_pengangkut' => 'Alat Pengangkut',
            'alat_pengangkut_lainnya' => 'Alat Pengangkut Lainnya',
        ];
    }
}