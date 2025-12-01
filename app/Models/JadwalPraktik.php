<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPraktik extends Model
{
    use HasFactory;

    protected $table = 'jadwal_praktiks';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    // Menambahkan casts untuk memastikan Eloquent memperlakukan ENUM dengan benar
    protected $casts = [
        'hari' => 'string',
        'status' => 'string',
    ];

    // Relasi ke Dokter
    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id', 'dokter_id');
    }
}