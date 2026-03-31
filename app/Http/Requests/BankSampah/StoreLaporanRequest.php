<?php

namespace App\Http\Requests\BankSampah;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniquePeriodeLaporan;
use App\Rules\MaxSampahTerkelola;
use App\Rules\TotalRincianSampah;

class StoreLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isBankSampah();
    }

    public function rules(): array
    {
        $bankSampahId = auth()->user()->bank_sampah_master_id;

        return [
            'periode' => [
                'required',
                'date',
                new UniquePeriodeLaporan($bankSampahId)
            ],
            'jumlah_sampah_masuk' => ['required', 'regex:/^\d+(\.\d{1,3})?$/'], // Angka dengan max 3 desimal
            'jumlah_sampah_terkelola' => [
                'required',
                'regex:/^\d+(\.\d{1,3})?$/',
                new MaxSampahTerkelola($this->jumlah_sampah_masuk)
            ],
            'jumlah_nasabah' => ['required', 'integer', 'min:0'],
            'rincian_sampah' => [
                'required',
                'array',
                new TotalRincianSampah($this->jumlah_sampah_terkelola)
            ],
            'rincian_sampah.*' => ['required', 'regex:/^\d+(\.\d{1,3})?$/']
        ];
    }

    public function attributes(): array
    {
        $jenisSampah = [
            'plastik_keras' => 'Plastik Keras',
            'plastik_fleksibel' => 'Plastik Fleksibel',
            'kertas_karton' => 'Kertas/Karton',
            'logam' => 'Logam',
            'kaca' => 'Kaca',
            'karet_kulit' => 'Karet/Kulit',
            'kain_tekstil' => 'Kain/Tekstil',
            'lainnya' => 'Lainnya'
        ];

        $attributes = [];
        foreach ($jenisSampah as $key => $label) {
            $attributes["rincian_sampah.$key"] = $label;
        }

        return array_merge([
            'periode' => 'Periode',
            'jumlah_sampah_masuk' => 'Jumlah Sampah Masuk',
            'jumlah_sampah_terkelola' => 'Jumlah Sampah Terkelola',
            'jumlah_nasabah' => 'Jumlah Nasabah'
        ], $attributes);
    }
    
    public function messages(): array
    {
        return [
            'jumlah_sampah_masuk.regex' => 'Format angka tidak valid. Contoh: 125.5 atau 125',
            'jumlah_sampah_terkelola.regex' => 'Format angka tidak valid. Contoh: 120.25 atau 120',
            'rincian_sampah.*.regex' => 'Format angka tidak valid. Contoh: 25.5 atau 25',
        ];
    }
}