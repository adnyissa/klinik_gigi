<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Antrian;
use App\Models\RekamMedis;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Konsultasi;
use App\Models\Kasir;

class DokterDashboardController extends Controller
{
    public function index()
    {
        // Tanggal hari ini & besok
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // Basis query antrian untuk hari ini
        $baseQueryHariIni = Antrian::with('pasien')
            ->whereDate('tgl_kunjungan', $today);

        // Statistik pasien
        $pasienHariIni   = (clone $baseQueryHariIni)->count();
        $pasienMenunggu  = (clone $baseQueryHariIni)->where('status', 'menunggu')->count();
        $pasienSelesai   = (clone $baseQueryHariIni)->where('status', 'selesai')->count();

        // Jumlah jadwal/boking untuk besok (berdasarkan antrian besok)
        $jadwalBesok = Antrian::whereDate('tgl_kunjungan', $tomorrow)->count();

        // Daftar antrian detail untuk tabel
        $antrianPasien = $baseQueryHariIni
            ->orderBy('jam_kunjungan')
            ->orderBy('nomor_antrian')
            ->get();

        // (Opsional) data label & grafik dummy jika nanti ingin dipakai chart
        $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $data   = [0, 0, 0, 0, 0, 0];

        return view('dokter.dashboard', compact(
            'pasienHariIni',
            'pasienMenunggu',
            'pasienSelesai',
            'jadwalBesok',
            'antrianPasien',
            'labels',
            'data'
        ));
    }

    /**
     * Halaman Periksa Pasien:
     * menampilkan daftar antrian "menunggu" & "diperiksa" hari ini
     * agar dokter bisa memilih pasien untuk diperiksa.
     */
    public function periksaPasien()
    {
        $today = Carbon::today();

        $antrianPeriksa = Antrian::with('pasien')
            ->whereDate('tgl_kunjungan', $today)
            ->whereIn('status', ['menunggu', 'diperiksa'])
            ->orderBy('status') // menunggu dulu, lalu diperiksa
            ->orderBy('jam_kunjungan')
            ->orderBy('nomor_antrian')
            ->get();

        // Data pasien untuk dropdown
        $pasiens = Pasien::orderBy('nama')->get();

        return view('dokter.periksa', compact('antrianPeriksa', 'pasiens'));
    }

    /**
     * Halaman Riwayat Pasien:
     * menampilkan riwayat rekam medis pasien yang sudah diperiksa oleh dokter yang login.
     */
    public function riwayatPasien()
    {
        // Ambil dokter_id dari user yang login
        $dokter = Dokter::where('user_id', Auth::id())->first();
        
        if (!$dokter) {
            // Jika dokter tidak ditemukan, kirim data kosong
            $riwayatRekamMedis = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('dokter.riwayat', compact('riwayatRekamMedis'));
        }

        // Ambil riwayat rekam medis yang dibuat oleh dokter ini
        $riwayatRekamMedis = RekamMedis::with(['konsultasi.pasien', 'konsultasi.dokter'])
            ->where('dokter_id', $dokter->dokter_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Data konsultasi untuk dropdown (yang belum punya rekam medis)
        $konsultasis = Konsultasi::with('pasien')
            ->where('dokter_id', $dokter->dokter_id)
            ->where('status', 'Selesai')
            ->whereDoesntHave('rekamMedis')
            ->orderBy('tgl_kunjungan', 'desc')
            ->get();

        return view('dokter.riwayat', compact('riwayatRekamMedis', 'konsultasis'));
    }

    /**
     * Halaman Resep Obat:
     * menampilkan resep obat dari rekam medis yang sudah dibuat oleh dokter yang login.
     */
    public function resepObat()
    {
        // Ambil dokter_id dari user yang login
        $dokter = Dokter::where('user_id', Auth::id())->first();
        
        if (!$dokter) {
            // Jika dokter tidak ditemukan, kirim data kosong
            $resepObat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            return view('dokter.resep', compact('resepObat'));
        }

        // Ambil rekam medis yang dibuat oleh dokter ini (untuk resep obat)
        $resepObat = RekamMedis::with(['konsultasi.pasien', 'konsultasi.dokter'])
            ->where('dokter_id', $dokter->dokter_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Data konsultasi untuk dropdown (yang belum punya rekam medis)
        $konsultasisResep = Konsultasi::with('pasien')
            ->where('dokter_id', $dokter->dokter_id)
            ->where('status', 'Selesai')
            ->whereDoesntHave('rekamMedis')
            ->orderBy('tgl_kunjungan', 'desc')
            ->get();

        return view('dokter.resep', compact('resepObat', 'konsultasisResep'));
    }

    /**
     * Simpan antrian baru.
     */
    public function storeAntrian(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:pasiens,pasien_id',
            'tgl_kunjungan' => 'required|date',
            'jam_kunjungan' => 'required',
            'keluhan_awal' => 'nullable|string|max:255',
        ]);

        // Generate nomor antrian
        $tglKunjungan = Carbon::parse($request->tgl_kunjungan);
        $nomorAntrian = 'A-' . str_pad(
            Antrian::whereDate('tgl_kunjungan', $tglKunjungan)->count() + 1,
            3,
            '0',
            STR_PAD_LEFT
        );

        Antrian::create([
            'pasien_id' => $request->pasien_id,
            'nomor_antrian' => $nomorAntrian,
            'tgl_kunjungan' => $request->tgl_kunjungan,
            'jam_kunjungan' => $request->jam_kunjungan,
            'keluhan_awal' => $request->keluhan_awal,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('success', 'Antrian berhasil ditambahkan.');
    }

    /**
     * Simpan rekam medis baru.
     */
    public function storeRekamMedis(Request $request)
    {
        $request->validate([
            'konsultasi_id' => 'required|exists:konsultasis,konsultasi_id',
            'diagnosis' => 'required|string|max:255',
            'tindakan' => 'required|string',
            'biaya_total' => 'required|numeric|min:0',
        ]);

        // Cek apakah konsultasi sudah punya rekam medis
        $existingRekamMedis = RekamMedis::where('konsultasi_id', $request->konsultasi_id)->first();
        if ($existingRekamMedis) {
            return redirect()->back()->with('error', 'Konsultasi ini sudah memiliki rekam medis.');
        }

        // Ambil dokter_id dari user yang login
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();
        
        // Ambil kasir pertama (atau bisa disesuaikan dengan logika bisnis)
        $kasir = Kasir::first();
        if (!$kasir) {
            return redirect()->back()->with('error', 'Kasir tidak ditemukan. Silakan hubungi administrator.');
        }

        RekamMedis::create([
            'konsultasi_id' => $request->konsultasi_id,
            'dokter_id' => $dokter->dokter_id,
            'kasir_id' => $kasir->kasir_id,
            'diagnosis' => $request->diagnosis,
            'tindakan' => $request->tindakan,
            'biaya_total' => $request->biaya_total,
        ]);

        return redirect()->back()->with('success', 'Rekam medis berhasil ditambahkan.');
    }
}