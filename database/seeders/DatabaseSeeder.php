<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear all tables first
        $this->truncateTables();
        
        $this->call([
            KecamatanSeeder::class,
            KelurahanSeeder::class,
        ]);

        // Create admin user - cek dulu apakah sudah ada
        if (!User::where('email', 'admin@basman.tegal.go.id')->exists()) {
            User::create([
                'name' => 'Admin DLH',
                'email' => 'admin@basman.tegal.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 'aktif'
            ]);
        }

        // Create super admin jika belum ada
        if (!User::where('email', 'superadmin@basman.tegal.go.id')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@basman.tegal.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif'
            ]);
        }
    }

    private function truncateTables(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $tables = [
            'users',
            'bank_sampah_masters',
            'kecamatans',
            'kelurahans',
            'operasionals',
            'laporans',
            'laporan_details',
            'aktivitas_logs',
            'password_resets'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}