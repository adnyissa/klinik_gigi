<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPraktik; // Pastikan Model JadwalPraktik sudah dibuat
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman index publik dengan jadwal dokter yang sudah diolah.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 1. Ambil semua jadwal praktik yang berstatus 'Aktif'
        // Asumsi relasi 'dokter' sudah ada di Model JadwalPraktik
        $jadwals = JadwalPraktik::with('dokter') 
            ->where('status', 'Aktif')
            ->get();

        // Daftar hari yang akan ditampilkan di tabel
        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        // Struktur data akhir yang akan dikirim ke view
        $jadwalDokter = [];
        $no = 1;

        // 2. Olah data: Kelompokkan berdasarkan Dokter dan susun jadwalnya per hari
        foreach ($jadwals as $jadwal) {
            $dokterId = $jadwal->dokter_id;
            $hari = $jadwal->hari;
            
            // Format jam menggunakan Carbon
            $jam = Carbon::parse($jadwal->jam_mulai)->format('H:i') . '-' . Carbon::parse($jadwal->jam_selesai)->format('H:i');

            // Inisialisasi data dokter jika belum ada
            if (!isset($jadwalDokter[$dokterId])) {
                $jadwalDokter[$dokterId] = [
                    'no' => $no++,
                    'nama' => $jadwal->dokter->nama,
                    // Menggunakan spesialisasi dokter sebagai Poli (sesuai data admin)
                    'poli' => $jadwal->dokter->spesialisasi ?? 'Umum', 
                    'dokter_id' => $dokterId,
                    'jadwal_harian' => array_fill_keys($allDays, ''), // Inisialisasi semua hari
                ];
            }

            // Tambahkan jam ke hari yang sesuai
            $jadwalDokter[$dokterId]['jadwal_harian'][$hari] = $jam;
        }
        
        // Konversi array asosiatif menjadi array terindeks untuk memudahkan looping di view
        $jadwalDokter = array_values($jadwalDokter);
        
        // Kirim data yang sudah diolah ke view
        return view('index', [ 
            'jadwalDokter' => $jadwalDokter,
            'allDays' => array_slice($allDays, 0, 6), // Hanya butuh Senin sampai Sabtu
        ]);
    }
}