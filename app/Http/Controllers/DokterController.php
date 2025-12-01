<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Dokter;
use App\Models\Konsultasi; // Pastikan Model ini ada (Tabel Transaksi Pasien)

class DokterController extends Controller
{
    /**
     * Menampilkan halaman Dashboard Dokter dengan Data Real Database
     */
    public function index()
    {
        // 1. Ambil ID User yang sedang login
        $user = Auth::user();

        // 2. Cari Data Dokter berdasarkan User ID
        // Kita perlu dokter_id untuk memfilter pasien milik dokter ini saja
        $dokter = Dokter::where('user_id', $user->id)->first();

        // Cek jika akun ini belum terdaftar di tabel dokters
        if (!$dokter) {
            // Bisa redirect ke halaman profil atau tampilkan error
            return abort(403, 'Akun Anda belum terhubung dengan profil Dokter.');
        }

        // 3. Setup Tanggal
        $hariIni = Carbon::today();
        $besok   = Carbon::tomorrow();

        // 4. MENGAMBIL DATA STATISTIK DARI DATABASE
        // Asumsi: Tabel 'pendaftarans' punya kolom 'dokter_id', 'status', dan timestamps

        // Total Pasien Hari Ini (Semua Status)
        $pasienHariIni = Konsultasi::where('dokter_id', $dokter->dokter_id)
            ->whereDate('created_at', $hariIni)
            ->count();

        // Pasien Status 'Menunggu' Hari Ini
        $pasienMenunggu = Konsultasi::where('dokter_id', $dokter->dokter_id)
            ->whereDate('created_at', $hariIni)
            ->where('status', 'Menunggu')
            ->count();

        // Pasien Status 'Selesai' Hari Ini
        $pasienSelesai = Konsultasi::where('dokter_id', $dokter->dokter_id)
            ->whereDate('created_at', $hariIni)
            ->where('status', 'Selesai')
            ->count();

        // Jadwal / Janji Temu Besok
        $jadwalBesok = Konsultasi::where('dokter_id', $dokter->dokter_id)
            ->whereDate('created_at', $besok) // Atau kolom 'tanggal_janji' jika ada
            ->count();

        // 5. MENGAMBIL DATA ANTRIAN (TABEL)
        // Mengambil daftar pasien hari ini, diurutkan dari yang paling awal daftar
        $antrianPasien = Konsultasi::with('user') // Eager load relasi ke User/Pasien untuk ambil nama
            ->where('dokter_id', $dokter->dokter_id)
            ->whereDate('created_at', $hariIni)
            ->orderBy('created_at', 'asc') // Urutkan berdasarkan waktu daftar
            ->limit(10) // Batasi 10 baris agar tidak kepanjangan
            ->get();
            
        // Kirim data ke View
        return view('dashboard_dokter', compact(
            'dokter',
            'pasienHariIni',
            'pasienMenunggu',
            'pasienSelesai',
            'jadwalBesok',
            'antrianPasien'
        ));
    }
}