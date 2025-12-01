<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Konsultasi;
use App\Models\Dokter;
use Illuminate\Support\Facades\Auth;

class PasienKonsultasiController extends Controller
{
    /**
     * Menampilkan riwayat janji temu DAN data untuk modal pendaftaran.
     */
    public function index()
    {
        $user = Auth::user();
        // Menggunakan optional() agar tidak error jika relasi pasien belum ada
        $pasienId = optional($user->pasien)->pasien_id;

        // 1. Data Riwayat (Tabel)
        if (!$pasienId) {
            // Jika data pasien belum ada, kirim paginator kosong agar tidak error di view
            $riwayat_konsultasi = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
        } else {
            // Ambil data riwayat
            $riwayat_konsultasi = Konsultasi::where('pasien_id', $pasienId)
                ->with(['dokter', 'jadwal'])
                ->orderBy('tgl_kunjungan', 'desc')
                ->paginate(10);
        }

        // 2. Data Dokter (Untuk Dropdown di Modal Pendaftaran)
        // Ambil ID, Nama, dan Spesialisasi dokter untuk ditampilkan di form
        $dokters = Dokter::get(['dokter_id', 'nama', 'spesialisasi']);

        // Kirim kedua data tersebut ke view
        return view('pasien.konsultasi.index', compact('riwayat_konsultasi', 'dokters'));
    }

    /**
     * Menyimpan janji temu baru dari Modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'dokter_id' => 'required|exists:dokters,dokter_id',
            'tgl_kunjungan' => 'required|date|after_or_equal:today',
            'jadwal_id' => 'required|exists:jadwal_praktiks,jadwal_id',
            'keluhan_awal' => 'required|string|max:500',
        ]);
        
        $pasienId = optional(Auth::user()->pasien)->pasien_id;

        if (!$pasienId) {
            return back()->with('error', 'Gagal mendaftar. Data Profil Pasien belum lengkap.');
        }

        // Cek duplikasi: Apakah pasien sudah punya janji di tanggal yang sama?
        $existing = Konsultasi::where('pasien_id', $pasienId)
            ->where('tgl_kunjungan', $request->tgl_kunjungan)
            ->where('status', '!=', 'Batal')
            ->first();

        if ($existing) {
            return back()->with('warning', 'Anda sudah memiliki janji temu aktif pada tanggal tersebut.');
        }

        // Simpan data konsultasi baru
        Konsultasi::create([
            'pasien_id' => $pasienId,
            'dokter_id' => $request->dokter_id,
            'jadwal_id' => $request->jadwal_id,
            'tgl_kunjungan' => $request->tgl_kunjungan,
            'keluhan_awal' => $request->keluhan_awal,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('pasien.konsultasi.index')->with('success', 'Pendaftaran berhasil! Silakan tunggu antrian.');
    }
    
    /**
     * Membatalkan janji temu.
     */
    public function cancel($konsultasi_id)
    {
        $pasienId = optional(Auth::user()->pasien)->pasien_id;
        
        if (!$pasienId) return back();

        $konsultasi = Konsultasi::where('konsultasi_id', $konsultasi_id)
            ->where('pasien_id', $pasienId)
            ->whereIn('status', ['Menunggu']) // Hanya status 'Menunggu' yang bisa dibatalkan
            ->firstOrFail();

        $konsultasi->update(['status' => 'Batal']);

        return back()->with('success', 'Janji temu berhasil dibatalkan.');
    }
    
    /**
     * Halaman Create tidak lagi diperlukan karena sudah pakai Modal.
     * Kita redirect saja ke index jika ada yang mengakses URL-nya langsung.
     */
    public function create() {
        return redirect()->route('pasien.konsultasi.index');
    }
}