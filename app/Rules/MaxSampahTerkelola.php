<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxSampahTerkelola implements Rule
{
    protected $sampahMasuk;

    public function __construct($sampahMasuk)
    {
        $this->sampahMasuk = $sampahMasuk;
    }

    public function passes($attribute, $value)
    {
        return $value <= $this->sampahMasuk;
    }

    public function message()
    {
        return 'Jumlah sampah terkelola tidak boleh melebihi sampah masuk.';
    }
}