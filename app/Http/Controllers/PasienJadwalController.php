<?php

namespace App\Http\Controllers;

use App\Models\JadwalPraktik;
use Illuminate\Http\Request;

class PasienJadwalController extends Controller
{
    /**
     * Tampilkan daftar jadwal praktik dokter yang aktif
     * untuk ditampilkan pada halaman pasien.
     */
    public function index(Request $request)
    {
        // Optional filter berdasarkan hari (Senin, Selasa, dst)
        $hari = $request->get('hari');

        $query = JadwalPraktik::with('dokter')
            ->where('status', 'Aktif');

        if ($hari) {
            $query->where('hari', $hari);
        }

        // Urutkan berdasarkan urutan hari dalam sepekan, lalu jam mulai
        $orderedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $jadwals = $query
            ->orderByRaw("FIELD(hari, '" . implode("','", $orderedDays) . "')")
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        // Daftar hari untuk filter di view
        $daftarHari = $orderedDays;

        return view('pasien.jadwalpraktik.index', compact('jadwals', 'daftarHari', 'hari'));
    }
}


