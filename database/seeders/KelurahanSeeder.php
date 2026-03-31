<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelurahan;
use App\Models\Kecamatan;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        $dataKelurahan = [
            'Tegal Barat' => [
                'Mintaragen', 'Pekauman', 'Tegalsari', 'Kemandungan'
            ],
            'Tegal Timur' => [
                'Kalinyamat', 'Kejambon', 'Mangkukusuman', 'Panggung'
            ],
            'Tegal Selatan' => [
                'Bandung', 'Debong Lor', 'Kalianyar', 'Pesurungan Lor'
            ],
            'Margadana' => [
                'Cabawan', 'Krandon', 'Margadana', 'Sumurpanggang'
            ]
        ];

        foreach ($dataKelurahan as $kecamatan => $kelurahans) {
            $kec = Kecamatan::where('nama_kecamatan', $kecamatan)->first();
            
            if ($kec) {
                foreach ($kelurahans as $kelurahan) {
                    Kelurahan::create([
                        'kecamatan_id' => $kec->id,
                        'nama_kelurahan' => $kelurahan
                    ]);
                }
            }
        }
    }
}