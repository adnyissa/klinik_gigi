<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dokter;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Akun ADMIN
        User::create([
            'name' => 'Admin Denta',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Akun DOKTER sederhana untuk login
        $dokterUser = User::create([
            'name' => 'doc',
            'email' => 'doc@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'dokter',
        ]);

        Dokter::create([
            'user_id'       => $dokterUser->id,
            'nama'          => 'doc',
            'no_sip'        => 'SIP-001',
            'spesialisasi'  => 'Dokter Gigi',
            'nomor_telepon' => '081111111111',
            'email'         => 'doc@gmail.com',
        ]);
    }
}