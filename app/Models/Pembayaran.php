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
        'rekam_medis_id',
        'pasien_id',
        'kasir_id',
        'tgl_pembayaran',
        'jumlah_dibayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status',
    ];

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id', 'rekam_medis_id');
    }
}