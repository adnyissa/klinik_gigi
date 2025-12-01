<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Jadwal_Periksa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DokterJadwalController extends Controller
{
    public function index()
    {
        // Ambil jadwal dokter yang login
        $jadwalDokter = Jadwal_Periksa::where('id_dokter', Auth::id())
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Ambil seluruh antrian pasien hari ini (belum ada kolom dokter di tabel antrian)
        $antrianPasien = Antrian::with('pasien')
            ->whereDate('tgl_kunjungan', Carbon::today())
            ->orderBy('jam_kunjungan')
            ->orderBy('nomor_antrian')
            ->get();

        // Statistik antrian
        $pasienHariIni  = $antrianPasien->count();
        $pasienMenunggu = $antrianPasien->where('status', 'menunggu')->count();
        $pasienSelesai  = $antrianPasien->where('status', 'selesai')->count();

        // Hitung jadwal besok berdasarkan enum hari (Senin, Selasa, ...)
        $namaHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $hariBesokIndex = Carbon::tomorrow()->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
        $hariBesokNama  = $namaHari[$hariBesokIndex] ?? 'Senin';

        $jadwalBesok = Jadwal_Periksa::where('id_dokter', Auth::id())
            ->where('hari', $hariBesokNama)
            ->count();

        return view('dokter.jadwal', compact(
            'jadwalDokter',
            'antrianPasien',
            'pasienHariIni',
            'pasienMenunggu',
            'pasienSelesai',
            'jadwalBesok'
        ));
    }

    /**
     * Toggle status aktif / non-aktif jadwal.
     */
    public function updateStatus($id)
    {
        $jadwal = Jadwal_Periksa::where('id', $id)
            ->where('id_dokter', Auth::id())
            ->firstOrFail();

        $jadwal->aktif = !$jadwal->aktif;
        $jadwal->save();

        return redirect()->back()->with('success', 'Status jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal dokter.
     */
    public function destroy($id)
    {
        $jadwal = Jadwal_Periksa::where('id', $id)
            ->where('id_dokter', Auth::id())
            ->firstOrFail();

        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Simpan jadwal baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwal_Periksa::create([
            'id_dokter' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'aktif' => true,
        ]);

        return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
    }
}
