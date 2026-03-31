<?php

namespace App\Http\Requests\BankSampah;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MaxSampahTerkelola;
use App\Rules\TotalRincianSampah;

class UpdateLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBankSampah();
    }

    public function rules(): array
    {
        return [
            'jumlah_sampah_masuk' => ['required', 'numeric', 'min:0'],
            'jumlah_sampah_terkelola' => [
                'required',
                'numeric',
                'min:0',
                new MaxSampahTerkelola($this->jumlah_sampah_masuk)
            ],
            'jumlah_nasabah' => ['required', 'integer', 'min:0'],
            'rincian_sampah' => [
                'required',
                'array',
                new TotalRincianSampah($this->jumlah_sampah_terkelola)
            ],
            'rincian_sampah.plastik_keras' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.plastik_fleksibel' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.kertas_karton' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.logam' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.kaca' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.karet_kulit' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.kain_tekstil' => ['required', 'numeric', 'min:0'],
            'rincian_sampah.lainnya' => ['required', 'numeric', 'min:0']
        ];
    }

    public function attributes(): array
    {
        return [
            'jumlah_sampah_masuk' => 'Jumlah Sampah Masuk',
            'jumlah_sampah_terkelola' => 'Jumlah Sampah Terkelola',
            'jumlah_nasabah' => 'Jumlah Nasabah',
            'rincian_sampah.plastik_keras' => 'Plastik Keras',
            'rincian_sampah.plastik_fleksibel' => 'Plastik Fleksibel',
            'rincian_sampah.kertas_karton' => 'Kertas/Karton',
            'rincian_sampah.logam' => 'Logam',
            'rincian_sampah.kaca' => 'Kaca',
            'rincian_sampah.karet_kulit' => 'Karet/Kulit',
            'rincian_sampah.kain_tekstil' => 'Kain/Tekstil',
            'rincian_sampah.lainnya' => 'Lainnya'
        ];
    }
}