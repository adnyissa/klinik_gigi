<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienRekamMedisController extends Controller
{
    /**
     * Tampilkan daftar rekam medis milik pasien yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Asumsi: relasi User -> Pasien disimpan di kolom 'pasien_id' pada tabel users
        // Jika struktur berbeda, bagian ini bisa disesuaikan.
        $pasienId = $user->pasien_id ?? null;

        if (!$pasienId) {
            // Jika tidak ditemukan pasien terkait, kembalikan halaman kosong dengan pesan
            $rekamMedis = collect([]);
            $hasPasien = false;

            return view('pasien.rekammedis.index', compact('rekamMedis', 'hasPasien'));
        }

        // Ambil rekam medis berdasarkan konsultasi yang dimiliki pasien ini
        $rekamMedis = RekamMedis::with(['dokter', 'kasir', 'konsultasi'])
            ->whereHas('konsultasi', function ($q) use ($pasienId) {
                $q->where('pasien_id', $pasienId);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $hasPasien = true;

        return view('pasien.rekammedis.index', compact('rekamMedis', 'hasPasien'));
    }
}


