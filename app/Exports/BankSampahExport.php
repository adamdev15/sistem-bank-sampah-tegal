<?php

namespace App\Exports;

use App\Models\BankSampahMaster;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BankSampahExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
            'Nama Kecamatan',
            'Nama Kelurahan',
            'RW',
            'Status Terbentuk',
            'Nama Bank Sampah',
            'Nomor SK',
            'Nama Direktur',
            'Nomor HP',
            'Keterangan',
            'Status Akun',
            'Email Akun'
        ];
    }

    public function map($bank): array
    {
        return [
            $bank->id,
            $bank->kecamatan->nama_kecamatan ?? '-',
            $bank->kelurahan->nama_kelurahan ?? '-',
            $bank->rw,
            $bank->status_terbentuk,
            $bank->nama_bank_sampah,
            $bank->nomor_sk,
            $bank->nama_direktur,
            $bank->nomor_hp,
            $bank->keterangan,
            $bank->user ? ucfirst(str_replace('_', ' ', $bank->user->status)) : 'Belum daftar',
            $bank->user ? $bank->user->email : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A' => ['width' => 5],
            'B' => ['width' => 20],
            'C' => ['width' => 20],
            'D' => ['width' => 10],
            'E' => ['width' => 15],
            'F' => ['width' => 30],
            'G' => ['width' => 20],
            'H' => ['width' => 20],
            'I' => ['width' => 15],
            'J' => ['width' => 25],
            'K' => ['width' => 15],
            'L' => ['width' => 25],
        ];
    }
}