<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal_Periksa extends Model
{
    use HasFactory;

    // Nama tabel eksplisit (opsional jika mengikuti konvensi Laravel)
    protected $table = 'jadwal_periksa';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'id_dokter',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'aktif'
    ];

    /**
     * Relasi: Satu jadwal dimiliki oleh satu dokter (User)
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'id_dokter');
    }
}