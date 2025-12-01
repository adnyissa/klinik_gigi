<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon; // Untuk tanggal hari ini
// Import semua Model yang sudah dibuat
use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter; // Sudah diimpor
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\JadwalPraktik; // Tambahkan JadwalPraktik jika ada

class AdminController extends Controller
{
    public function index()
    {
        // 1. Total Pasien (Ambil dari tabel Pasien)
        $totalPasien = Pasien::count();

        // 2. Total Dokter (Ambil dari tabel Dokter)
        // PERBAIKAN: Mengganti 'Dokters::count()' menjadi 'Dokter::count()' (Model singular)
        $totalDokter = Dokter::count();

        // 3. Janji Temu / Antrian HARI INI
        $janjiHariIni = Konsultasi::whereDate('tgl_kunjungan', Carbon::today())->count();

        // 4. Pendapatan HARI INI
        // Menjumlahkan kolom 'jumlah_dibayar' dari tabel pembayaran
        // CATATAN: Mengganti 'total_biaya' (asumsi kolom migrasi yang benar adalah 'jumlah_dibayar')
        $pendapatanHariIni = Pembayaran::whereDate('tgl_pembayaran', Carbon::today())->sum('jumlah_dibayar'); 

        // 5. List 5 Pasien Terbaru (Biar dashboard ada isinya tabel)
        // CATATAN: Relasi Pasien ke User mungkin tidak ada, saya hapus .with('user')
        $pasienTerbaru = Pasien::latest('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalPasien', 
            'totalDokter', 
            'janjiHariIni', 
            'pendapatanHariIni',
            'pasienTerbaru'
        ));
    }
}