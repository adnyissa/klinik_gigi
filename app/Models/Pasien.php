<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pasien extends Model
{
    use HasFactory;

    // 1. Nama Tabel (Sesuai Migrasi)
    protected $table = 'pasiens';

    // 2. Primary Key (Sesuai Migrasi)
    protected $primaryKey = 'pasien_id';
    
    
    protected $fillable = [
        'nama',
        'nik',
        'tanggal_lahir',  // SEBELUMNYA SALAH (tgl_lahir) -> HARUS tanggal_lahir
        'jenis_kelamin',
        'nomor_telepon',  // SEBELUMNYA SALAH (no_hp) -> HARUS nomor_telepon
        'alamat',
        // 'user_id',     // HATI-HATI: Di migrasi yang kamu kirim TIDAK ADA kolom user_id. 
                          // Kalau di database tidak ada kolom ini, baris ini akan bikin error. 
                          // Saya matikan dulu (comment) biar aman.
    ];

    // 4. Casting (Opsional, tapi bagus untuk format tanggal)
    protected $casts = [
        'tanggal_lahir' => 'date', // Sesuaikan nama kolom
    ];
}