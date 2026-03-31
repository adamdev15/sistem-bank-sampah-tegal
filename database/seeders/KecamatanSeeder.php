<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatans = [
            'Tegal Barat',
            'Tegal Timur',
            'Tegal Selatan',
            'Margadana'
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::create(['nama_kecamatan' => $kecamatan]);
        }
    }
}