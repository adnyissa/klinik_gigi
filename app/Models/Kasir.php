<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;

    protected $table = 'kasirs';
    protected $primaryKey = 'kasir_id';

    protected $fillable = [
        'user_id',
        'no_hp',
        'alamat',
        'shift_kerja',
    ];

    // Relasi ke User (untuk ambil Nama & Email)
    public function user()
{
    return $this->belongsTo(User::class);
}
}