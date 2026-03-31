<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya buat jika belum ada
        if (!User::where('email', 'superadmin@basman.tegal.go.id')->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@basman.tegal.go.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif'
            ]);
            
            $this->command->info('Super Admin created successfully.');
        } else {
            $this->command->info('Super Admin already exists.');
        }
    }
}