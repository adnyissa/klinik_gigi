<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi: Dokter punya banyak Jadwal Praktik
    public function jadwals()
    {
        return $this->hasMany(JadwalPraktik::class, 'dokter_id');
    }

    // Relasi: Pasien punya banyak Rekam Medis
    public function rekamMedisPasien()
    {
        return $this->hasMany(RekamMedis::class, 'pasien_id');
    }
    
    // Relasi: Dokter menangani banyak Rekam Medis
    public function rekamMedisDokter()
    {
        return $this->hasMany(RekamMedis::class, 'dokter_id');
    }

    // Relasi: Pasien punya banyak Pembayaran/Transaksi
    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'pasien_id');
    }
}
