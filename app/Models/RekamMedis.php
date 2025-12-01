<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Pasien; // Tambahkan import untuk model yang baru (jika diperlukan)
use App\Models\Dokter;
use App\Models\Kasir;
use App\Models\Konsultasi;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';
    protected $primaryKey = 'rekam_medis_id';

    // Kolom yang dapat diisi (sesuai migrasi)
    protected $fillable = [
        'konsultasi_id',
        'dokter_id',
        'kasir_id',
        'diagnosis',
        'tindakan',
        'biaya_total',
    ];

    // Relasi ke Konsultasi
    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id', 'konsultasi_id');
    }
    
    // Relasi ke Pasien (Mengambil data pasien melalui relasi Konsultasi)
    // NOTE: Ini akan bekerja jika Model Konsultasi memiliki relasi belongsTo(Pasien::class)
    public function pasien()
    {
        // Memastikan Konsultasi memiliki kolom 'pasien_id'
        // Jika Pasien adalah kolom langsung di tabel rekam_medis, gunakan belongsTo
        // Jika Pasien hanya ada melalui Konsultasi, Anda perlu mendefinisikan relasi Pasien di Konsultasi dulu.
        // CARA PALING UMUM (Bila Konsultasi punya pasien_id):
        return $this->hasOneThrough(
            Pasien::class, 
            Konsultasi::class,
            'konsultasi_id', // Foreign key on the Konsultasi table...
            'pasien_id',     // Foreign key on the Pasien table...
            'konsultasi_id', // Local key on the RekamMedis table...
            'pasien_id'      // Local key on the Konsultasi table...
        );

        // Jika Anda hanya ingin menggunakan Opsi A (Nested Eager Loading), Anda TIDAK perlu menambahkan fungsi ini.
    }


    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id', 'dokter_id');
    }

    // Relasi ke Kasir
    public function kasir()
    {
        return $this->belongsTo(Kasir::class, 'kasir_id', 'kasir_id');
    }
}