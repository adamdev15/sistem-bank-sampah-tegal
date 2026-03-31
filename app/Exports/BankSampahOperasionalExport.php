<?php

namespace App\Exports;

use App\Models\Operasional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankSampahOperasionalExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $operasional;

    public function __construct(Operasional $operasional)
    {
        $this->operasional = $operasional;
    }

    public function collection()
    {
        return collect([$this->operasional]);
    }

    public function headings(): array
    {
        return [
            'DATA OPERASIONAL BANK SAMPAH',
            '',
            'Nama Bank Sampah',
            'Kecamatan',
            'Kelurahan',
            'RW',
            'Tenaga Kerja Laki-laki',
            'Tenaga Kerja Perempuan',
            'Total Tenaga Kerja',
            'Nasabah Laki-laki',
            'Nasabah Perempuan',
            'Total Nasabah',
            'Omset (Rp)',
            'Tempat Penjualan',
            'Kegiatan Pengelolaan Sampah',
            'Produk Daur Ulang/Kerajinan',
            'Buku Tabungan',
            'Sistem Pencatatan',
            'Timbangan',
            'Alat Pengangkut Sampah',
            'Terakhir Diupdate'
        ];
    }

    public function map($operasional): array
    {
        return [
            '', // Spacer untuk header
            '',
            $operasional->bankSampahMaster->nama_bank_sampah ?? '-',
            $operasional->bankSampahMaster->kecamatan->nama_kecamatan ?? '-',
            $operasional->bankSampahMaster->kelurahan->nama_kelurahan ?? '-',
            $operasional->bankSampahMaster->rw ?? '-',
            $operasional->tenaga_kerja_laki,
            $operasional->tenaga_kerja_perempuan,
            $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan,
            $operasional->nasabah_laki,
            $operasional->nasabah_perempuan,
            $operasional->nasabah_laki + $operasional->nasabah_perempuan,
            number_format($operasional->omset, 0, ',', '.'),
            $operasional->tempat_penjualan,
            $operasional->kegiatan_pengelolaan,
            $operasional->produk_daur_ulang,
            $operasional->buku_tabungan,
            $operasional->sistem_pencatatan,
            $operasional->timbangan,
            $operasional->alat_pengangkut,
            $operasional->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center']
            ],
            3 => ['font' => ['bold' => true]],
            'A' => ['width' => 30],
            'B' => ['width' => 5],
            'C' => ['width' => 25],
            'D' => ['width' => 20],
            'E' => ['width' => 20],
            'F' => ['width' => 10],
            'G' => ['width' => 20],
            'H' => ['width' => 20],
            'I' => ['width' => 20],
            'J' => ['width' => 20],
            'K' => ['width' => 20],
            'L' => ['width' => 20],
            'M' => ['width' => 20],
            'N' => ['width' => 25],
            'O' => ['width' => 30],
            'P' => ['width' => 30],
            'Q' => ['width' => 15],
            'R' => ['width' => 20],
            'S' => ['width' => 15],
            'T' => ['width' => 20],
            'U' => ['width' => 20]
        ];
    }

    public function title(): string
    {
        return 'Data Operasional';
    }
}