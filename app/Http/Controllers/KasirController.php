<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    /**
     * Menampilkan Dashboard Kasir.
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cek apakah user memiliki data profil kasir
        // Asumsi relasi di model User: public function kasir() { return $this->hasOne(Kasir::class); }
        $kasir = $user->kasir;

        // --- CONTOH DATA DUMMY UNTUK STATISTIK ---
        // Nanti Anda bisa menggantinya dengan query database yang asli
        // Misal: Transaksi::whereDate('created_at', today())->count();
        $statistik = [
            'transaksi_hari_ini' => 15,
            'antrian_bayar' => 3,
            'pendapatan_est' => 'Rp 2.5jt',
        ];

        // --- CONTOH DATA DUMMY UNTUK TABEL ANTRIAN ---
        // Nanti diganti dengan: Pembayaran::where('status', 'Pending')->get();
        $antrian_pembayaran = [
            (object)[
                'no_rm' => 'RM-00123',
                'nama_pasien' => 'Budi Santoso',
                'layanan' => 'Cabut Gigi, Pembersihan Karang',
                'total' => 'Rp 450.000',
                'status' => 'Menunggu'
            ],
            (object)[
                'no_rm' => 'RM-00125',
                'nama_pasien' => 'Siti Aminah',
                'layanan' => 'Tambal Gigi Permanen',
                'total' => 'Rp 300.000',
                'status' => 'Menunggu'
            ],
            // ... data lainnya
        ];

        // Panggil view 'kasir.index' (resources/views/kasir/index.blade.php)
        // Kirim data statistik dan antrian ke view
        return view('kasir.dashboard', compact('kasir', 'statistik', 'antrian_pembayaran'));
    }
}