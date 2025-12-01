<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokters';
    protected $primaryKey = 'dokter_id'; // Sesuai migration

    // PENTING: $fillable harus SAMA PERSIS dengan nama kolom di Migration database
    protected $fillable = [
    'user_id',
    'nama',
    'no_sip', // Tambahkan ini
    'spesialisasi',
    'nomor_telepon',
    'email',
];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}