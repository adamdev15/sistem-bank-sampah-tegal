<?php

namespace App\Exports\BankSampah;

use App\Models\Operasional;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OperasionalExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize,
    WithColumnWidths,
    WithEvents
{
    protected Operasional $operasional;
    protected $bankSampah;

    public function __construct(Operasional $operasional, $bankSampah)
    {
        $this->operasional = $operasional;
        $this->bankSampah  = $bankSampah;
    }

    public function collection()
    {
        return collect([$this->operasional]);
    }

    public function headings(): array
    {
        return [
            ['DATA OPERASIONAL BANK SAMPAH'],
            [$this->bankSampah->nama_bank_sampah],
            ['Dinas Lingkungan Hidup Kota Tegal - Sistem BASMAN'],
            ['Tanggal Export: ' . now()->format('d/m/Y H:i')],
            [''],
            ['Kategori', 'Sub Kategori', 'Nilai', 'Keterangan'],
        ];
    }

    public function map($operasional): array
    {
        $rows = [];

        // TENAGA KERJA
        $rows[] = ['TENAGA KERJA', 'Laki-laki', $operasional->tenaga_kerja_laki, 'orang'];
        $rows[] = ['', 'Perempuan', $operasional->tenaga_kerja_perempuan, 'orang'];
        $rows[] = ['', 'Total Tenaga Kerja',
            $operasional->tenaga_kerja_laki + $operasional->tenaga_kerja_perempuan, 'orang'];

        // NASABAH
        $rows[] = ['NASABAH', 'Laki-laki', $operasional->nasabah_laki, 'orang'];
        $rows[] = ['', 'Perempuan', $operasional->nasabah_perempuan, 'orang'];
        $rows[] = ['', 'Total Nasabah',
            $operasional->nasabah_laki + $operasional->nasabah_perempuan, 'orang'];

        // KEUANGAN
        $rows[] = ['KEUANGAN', 'Omset Bulanan',
            'Rp ' . number_format($operasional->omset, 0, ',', '.'), 'Rupiah / bulan'];

        // PENJUALAN
        $rows[] = ['PENJUALAN', 'Tempat Penjualan',
            match ($operasional->tempat_penjualan) {
                'bank_sampah_induk' => 'Bank Sampah Induk',
                'pengepul' => 'Pengepul',
                'lainnya' => $operasional->tempat_penjualan_lainnya ?? 'Lainnya',
                default => '-'
            }, ''
        ];

        // KEGIATAN
        $rows[] = ['KEGIATAN', 'Pengelolaan Sampah', $operasional->kegiatan_pengelolaan, ''];

        // PRODUK
        $rows[] = ['PRODUK', 'Daur Ulang / Kerajinan',
            $operasional->produk_daur_ulang ?: 'Belum diisi', ''];

        // SARANA
        $rows[] = ['SARANA', 'Buku Tabungan',
            $operasional->buku_tabungan === 'ada' ? 'Ada' : 'Tidak Ada', ''];

        $rows[] = ['', 'Sistem Pencatatan', $operasional->sistem_pencatatan, ''];

        $rows[] = ['', 'Timbangan',
            match ($operasional->timbangan) {
                'timbangan_gantung' => 'Timbangan Gantung',
                'timbangan_digital' => 'Timbangan Digital',
                'timbangan_posyandu' => 'Timbangan Posyandu',
                'timbangan_duduk' => 'Timbangan Duduk',
                default => 'Tidak Ada'
            }, ''
        ];

        // INFO BANK SAMPAH
        $rows[] = ['INFO BANK SAMPAH', 'Nama', $this->bankSampah->nama_bank_sampah, ''];
        $rows[] = ['', 'Kecamatan', $this->bankSampah->kecamatan->nama_kecamatan ?? '-', ''];
        $rows[] = ['', 'Kelurahan', $this->bankSampah->kelurahan->nama_kelurahan ?? '-', ''];
        $rows[] = ['', 'RW', $this->bankSampah->rw, ''];
        $rows[] = ['', 'Direktur', $this->bankSampah->nama_direktur, ''];

        // METADATA
        $rows[] = ['METADATA', 'Tanggal Update',
            optional($operasional->updated_at)->format('d/m/Y H:i'), ''];
        $rows[] = ['', 'Tanggal Export', now()->format('d/m/Y H:i'), ''];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:D1');
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('A3:D3');
        $sheet->mergeCells('A4:D4');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1:D4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A6:D6')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C3E50'],
            ],
        ]);

        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle("A6:D{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 30,
            'D' => 20,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $last = $event->sheet->getHighestRow() + 2;

                $event->sheet->setCellValue("A{$last}", 'BASMAN – Bank Sampah Management System');
                $event->sheet->mergeCells("A{$last}:D{$last}");

                $event->sheet->getStyle("A{$last}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function title(): string
    {
        return 'Data Operasional';
    }
}
