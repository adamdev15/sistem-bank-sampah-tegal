<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankSampahLaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $laporans;

    public function __construct($laporans)
    {
        $this->laporans = $laporans;
    }

    public function collection()
    {
        return $this->laporans;
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Sampah Masuk (Kg)',
            'Sampah Terkelola (Kg)',
            'Jumlah Nasabah',
            'Status',
            'Plastik Keras (Kg)',
            'Plastik Fleksibel (Kg)',
            'Kertas/Karton (Kg)',
            'Logam (Kg)',
            'Kaca (Kg)',
            'Karet/Kulit (Kg)',
            'Kain/Tekstil (Kg)',
            'Lainnya (Kg)',
            'Total Rincian (Kg)',
            'Catatan Verifikasi'
        ];
    }

    public function map($laporan): array
    {
        $details = [];
        foreach ($laporan->details as $detail) {
            $details[$detail->jenis_sampah] = $detail->jumlah;
        }

        $totalRincian = collect($details)->sum();

        return [
            $laporan->periode->format('F Y'),
            $laporan->jumlah_sampah_masuk,
            $laporan->jumlah_sampah_terkelola,
            $laporan->jumlah_nasabah,
            ucfirst(str_replace('_', ' ', $laporan->status)),
            $details['plastik_keras'] ?? 0,
            $details['plastik_fleksibel'] ?? 0,
            $details['kertas_karton'] ?? 0,
            $details['logam'] ?? 0,
            $details['kaca'] ?? 0,
            $details['karet_kulit'] ?? 0,
            $details['kain_tekstil'] ?? 0,
            $details['lainnya'] ?? 0,
            $totalRincian,
            $laporan->catatan_verifikasi ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 15],
            'B' => ['width' => 15],
            'C' => ['width' => 15],
            'D' => ['width' => 15],
            'E' => ['width' => 15],
            'F' => ['width' => 15],
            'G' => ['width' => 15],
            'H' => ['width' => 15],
            'I' => ['width' => 15],
            'J' => ['width' => 15],
            'K' => ['width' => 15],
            'L' => ['width' => 15],
            'M' => ['width' => 15],
            'N' => ['width' => 15],
            'O' => ['width' => 25]
        ];
    }

    public function title(): string
    {
        return 'Semua Laporan';
    }
}