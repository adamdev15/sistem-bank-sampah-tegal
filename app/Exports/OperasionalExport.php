<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OperasionalExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Bank Sampah',
            'Kecamatan',
            'Kelurahan',
            'Tenaga Kerja Laki',
            'Tenaga Kerja Perempuan',
            'Total Tenaga Kerja',
            'Nasabah Laki',
            'Nasabah Perempuan',
            'Total Nasabah',
            'Omset (Rp)',
            'Tempat Penjualan',
            'Kegiatan Pengelolaan',
            'Produk Daur Ulang',
            'Buku Tabungan',
            'Sistem Pencatatan',
            'Timbangan',
            'Alat Pengangkut'
        ];
    }

    public function map($operasional): array
    {
        return [
            $operasional->id,
            $operasional->bankSampahMaster->nama_bank_sampah ?? '-',
            $operasional->bankSampahMaster->kecamatan->nama_kecamatan ?? '-',
            $operasional->bankSampahMaster->kelurahan->nama_kelurahan ?? '-',
            $operasional->tenaga_kerja_laki,
            $operasional->tenaga_kerja_perempuan,
            $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan,
            $operasional->nasabah_laki,
            $operasional->nasabah_perempuan,
            $operasional->nasabah_laki + $operasional->nasabah_perempuan,
            $operasional->omset,
            $operasional->tempat_penjualan,
            $operasional->kegiatan_pengelolaan,
            $operasional->produk_daur_ulang,
            $operasional->buku_tabungan,
            $operasional->sistem_pencatatan,
            $operasional->timbangan,
            $operasional->alat_pengangkut
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 5],
            'B' => ['width' => 30],
            'C' => ['width' => 20],
            'D' => ['width' => 20],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
            'I' => ['width' => 15],
            'J' => ['width' => 15],
            'K' => ['width' => 15],
            'L' => ['width' => 20],
            'M' => ['width' => 25],
            'N' => ['width' => 25],
            'O' => ['width' => 15],
            'P' => ['width' => 20],
            'Q' => ['width' => 15],
            'R' => ['width' => 15],
        ];
    }
}