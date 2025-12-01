<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    // Nama tabel eksplisit karena migration menggunakan nama "antrian"
    protected $table = 'antrian';

    protected $primaryKey = 'antrian_id';

    protected $fillable = [
        'pasien_id', 'nomor_antrian', 'tgl_kunjungan', 'jam_kunjungan', 'keluhan_awal', 'status'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'pasien_id');
    }
}
