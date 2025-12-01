<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // HANYA BUAT 1 AKUN ADMIN (Sesuai keinginanmu)
        User::create([
            'name' => 'Admin Denta',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
        
        // Dokter, Kasir, Pasien TIDAK DIBUAT DISINI.
        // Nanti kamu (sebagai Admin) yang input manual lewat menu "Tambah User".
    }
}