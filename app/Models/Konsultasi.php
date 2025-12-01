<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    // Tentukan Primary Key dan nama tabel
    protected $primaryKey = 'konsultasi_id';
    protected $table = 'konsultasis'; 

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'jadwal_id',
        'tgl_kunjungan',
        'keluhan_awal',
        'status',
    ];

    /**
     * Relasi ke Pasien
     */
    public function pasien()
    {
        // Sesuaikan dengan Primary Key di tabel Pasien jika bukan 'id'
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id'); 
    }

    /**
     * Relasi ke Dokter
     */
    public function dokter()
    {
        // Sesuaikan dengan Primary Key di tabel Dokter jika bukan 'id'
        return $this->belongsTo(Dokter::class, 'dokter_id', 'dokter_id');
    }

    /**
     * Relasi ke Jadwal Praktik
     * Asumsi: Model JadwalPraktik ada dan Primary Key-nya adalah 'jadwal_id'
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPraktik::class, 'jadwal_id', 'jadwal_id');
    }
}