<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Laporan;

class UniquePeriodeLaporan implements Rule
{
    protected $bankSampahId;
    protected $laporanId;

    public function __construct($bankSampahId, $laporanId = null)
    {
        $this->bankSampahId = $bankSampahId;
        $this->laporanId = $laporanId;
    }

    public function passes($attribute, $value)
    {
        $query = Laporan::where('bank_sampah_master_id', $this->bankSampahId)
            ->whereYear('periode', date('Y', strtotime($value)))
            ->whereMonth('periode', date('m', strtotime($value)));

        if ($this->laporanId) {
            $query->where('id', '!=', $this->laporanId);
        }

        return !$query->exists();
    }

    public function message()
    {
        return 'Sudah ada laporan untuk periode tersebut.';
    }
}