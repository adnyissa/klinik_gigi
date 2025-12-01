<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Jadwal;
use App\Models\Antrian;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DokterController extends Controller
{
    public function dashboard()
    {
        $dokterId = Auth::id(); // Ambil user yang login

        // Pasien hari ini
        $pasienHariIni = Pasien::whereDate('created_at', Carbon::today())
                                ->where('dokter_id', $dokterId)
                                ->count();

        // Pasien menunggu
        $pasienMenunggu = Antrian::where('dokter_id', $dokterId)
                                 ->where('status', 'Menunggu')
                                 ->count();

        // Pasien selesai
        $pasienSelesai = Antrian::where('dokter_id', $dokterId)
                                ->where('status', 'Selesai')
                                ->count();

        // Jadwal besok
        $jadwalBesok = Jadwal::where('dokter_id', $dokterId)
                             ->whereDate('tanggal', Carbon::tomorrow())
                             ->count();

        // Antrian saat ini (misal limit 5)
        $antrianPasien = Antrian::where('dokter_id', $dokterId)
                                ->where('status', 'Menunggu')
                                ->orderBy('nomor_antrian', 'asc')
                                ->limit(5)
                                ->get();

        return view('dokter.dashboard', compact(
            'pasienHariIni',
            'pasienMenunggu',
            'pasienSelesai',
            'jadwalBesok',
            'antrianPasien'
        ));
    }
}
