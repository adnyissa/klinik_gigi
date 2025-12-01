<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans'; 
    protected $primaryKey = 'pembayaran_id';

    protected $fillable = [
    'rekam_medis_id', // Harus ada
    'pasien_id', 
    'tgl_pembayaran', 
    'total_biaya', // Harus ada
    'metode_pembayaran', 
    'status',
    // ... field lainnya
];
}