<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TotalRincianSampah implements Rule
{
    protected $sampahTerkelola;

    public function __construct($sampahTerkelola)
    {
        // Format: replace comma with dot
        $this->sampahTerkelola = str_replace(',', '.', $sampahTerkelola);
    }

    public function passes($attribute, $value)
    {
        $total = 0;
        foreach ($value as $jumlah) {
            $total += (float) str_replace(',', '.', $jumlah);
        }
        
        // Allow small difference (0.001 kg)
        return abs($total - (float) $this->sampahTerkelola) <= 0.001;
    }

    public function message()
    {
        return 'Total rincian jenis sampah harus sama dengan total sampah terkelola.';
    }
}