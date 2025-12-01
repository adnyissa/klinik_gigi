<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $primaryKey = 'pasien_id';

    protected $fillable = [
        'nama','nik','nomor_telepon','tanggal_lahir','jenis_kelamin','alamat','id_dokter'
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'pasien_id', 'pasien_id');
    }
}
